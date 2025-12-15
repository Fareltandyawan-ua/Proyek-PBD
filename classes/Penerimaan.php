<?php
require_once 'Database.php';

class Penerimaan
{
    private $db;
    private $conn;

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->conn = $this->db->getConnection();
    }

    /**
     * Ambil semua data penerimaan untuk ditampilkan di index
     */
    public function getAll()
    {
        $sql = "SELECT 
                    r.idpenerimaan,
                    r.created_at,
                    r.status,
                    r.idpengadaan,
                    u.username AS penerima,
                    v.nama_vendor,
                    p.total_nilai
                FROM penerimaan r
                LEFT JOIN user u ON r.iduser = u.iduser
                LEFT JOIN pengadaan p ON r.idpengadaan = p.idpengadaan
                LEFT JOIN vendor v ON p.idvendor = v.idvendor
                ORDER BY r.created_at DESC";
        return $this->db->fetchAll($sql);
    }

    /**
     * Ambil list pengadaan yang belum selesai untuk dropdown
     */
    public function getPengadaanPending()
    {
        $sql = "SELECT 
                    p.idpengadaan, 
                    p.timestamp,
                    p.status,
                    v.nama_vendor,
                    p.total_nilai
                FROM pengadaan p 
                LEFT JOIN vendor v ON p.idvendor = v.idvendor 
                WHERE p.status IN ('1', 'P')
                ORDER BY p.timestamp DESC";
        return $this->db->fetchAll($sql);
    }

    /**
     * Ambil detail barang dari pengadaan yang dipilih
     */
    public function getBarangByPengadaan($idpengadaan)
    {
        $sql = "SELECT 
                    dp.iddetail_pengadaan,
                    dp.idbarang, 
                    b.nama, 
                    dp.jumlah,
                    dp.harga_satuan,
                    dp.sub_total,
                    s.nama_satuan,
                    COALESCE(SUM(dpr.jumlah_terima), 0) AS total_diterima,
                    (dp.jumlah - COALESCE(SUM(dpr.jumlah_terima), 0)) AS sisa
                FROM detail_pengadaan dp
                LEFT JOIN barang b ON dp.idbarang = b.idbarang
                LEFT JOIN satuan s ON b.idsatuan = s.idsatuan
                LEFT JOIN detail_penerimaan dpr ON dpr.idbarang = dp.idbarang 
                    AND dpr.idpenerimaan IN (
                        SELECT idpenerimaan FROM penerimaan WHERE idpengadaan = ?
                    )
                WHERE dp.idpengadaan = ?
                GROUP BY dp.iddetail_pengadaan, dp.idbarang, b.nama, dp.jumlah, dp.harga_satuan, dp.sub_total, s.nama_satuan
                HAVING sisa > 0
                ORDER BY b.nama";

        return $this->db->fetchAll($sql, [$idpengadaan, $idpengadaan]);
    }

    /**
     * FUNGSI UTAMA: Simpan transaksi penerimaan
     */
    public function simpan($idpengadaan, $iduser, $barangData)
    {
        try {
            // Mulai transaksi
            $this->conn->beginTransaction();

            // 1. INSERT ke tabel penerimaan
            $stmt = $this->conn->prepare("
                INSERT INTO penerimaan (idpengadaan, iduser, created_at, status)
                VALUES (?, ?, NOW(), 'P')
            ");
            $stmt->execute([$idpengadaan, $iduser]);
            $idpenerimaan = $this->conn->lastInsertId();

            // 2. Proses setiap barang
            foreach ($barangData as $idbarang => $row) {

                $jumlah_terima = (int) ($row['jumlah_terima'] ?? 0);

                // Skip jika jumlah 0
                if ($jumlah_terima <= 0)
                    continue;

                $harga_satuan = (float) ($row['harga_satuan'] ?? 0);
                $sub_total_terima = $jumlah_terima * $harga_satuan;

                // VALIDASI: Ambil data detail pengadaan dan total yang sudah diterima
                $detailPengadaan = $this->conn->prepare("
                    SELECT 
                        dp.jumlah, 
                        dp.idbarang, 
                        dp.harga_satuan,
                        COALESCE((
                            SELECT SUM(dpr.jumlah_terima)
                            FROM detail_penerimaan dpr
                            JOIN penerimaan pr ON dpr.idpenerimaan = pr.idpenerimaan
                            WHERE pr.idpengadaan = dp.idpengadaan 
                            AND dpr.idbarang = dp.idbarang
                        ), 0) AS total_diterima
                    FROM detail_pengadaan dp
                    WHERE dp.idpengadaan = ? AND dp.idbarang = ?
                ");
                $detailPengadaan->execute([$idpengadaan, $idbarang]);
                $detail = $detailPengadaan->fetch(PDO::FETCH_ASSOC);

                if (!$detail) {
                    throw new Exception("Detail pengadaan tidak ditemukan untuk barang ID: " . $idbarang);
                }

                $sisaBelumTerima = $detail['jumlah'] - $detail['total_diterima'];

                // Cek apakah jumlah terima melebihi sisa
                if ($jumlah_terima > $sisaBelumTerima) {
                    throw new Exception("Jumlah diterima melebihi sisa pesanan! Sisa: " . $sisaBelumTerima);
                }

                // 3. INSERT detail_penerimaan (tanpa iddetail_pengadaan)
                $stmtDetail = $this->conn->prepare("
                    INSERT INTO detail_penerimaan 
                    (idpenerimaan, idbarang, jumlah_terima, harga_satuan_terima, sub_total_terima)
                    VALUES (?, ?, ?, ?, ?)
                ");
                $stmtDetail->execute([
                    $idpenerimaan,
                    $idbarang,
                    $jumlah_terima,
                    $harga_satuan,
                    $sub_total_terima
                ]);

                // 4. UPDATE KARTU STOK
                // Ambil stok terakhir
                $cekStok = $this->conn->prepare("
                    SELECT stock 
                    FROM kartu_stok 
                    WHERE idbarang = ?
                    ORDER BY idkartu_stok DESC
                    LIMIT 1
                ");
                $cekStok->execute([$idbarang]);
                $lastStock = $cekStok->fetchColumn();

                if ($lastStock === false) {
                    $lastStock = 0; // Jika belum ada riwayat stok
                }

                // Hitung stok baru
                $stockAkhir = $lastStock + $jumlah_terima;

                // Insert ke kartu_stok
                $insertKS = $this->conn->prepare("
                    INSERT INTO kartu_stok 
                    (jenis_transaksi, masuk, keluar, stock, created_at, idtransaksi, idbarang)
                    VALUES ('M', ?, 0, ?, NOW(), ?, ?)
                ");
                $insertKS->execute([
                    $jumlah_terima,
                    $stockAkhir,
                    $idpenerimaan,
                    $idbarang
                ]);
            }

            // 5. CEK STATUS PENGADAAN (Apakah sudah lengkap semua?)
            $this->updateStatusPengadaan($idpengadaan);

            // Commit transaksi
            $this->conn->commit();
            return $idpenerimaan;

        } catch (Exception $e) {
            $this->conn->rollBack();
            throw $e;
        }
    }

    /**
     * Update status pengadaan berdasarkan total penerimaan
     */
    private function updateStatusPengadaan($idpengadaan)
    {
        // Cek apakah semua barang sudah diterima lengkap
        $cek = $this->conn->prepare("
            SELECT 
                dp.idbarang,
                dp.jumlah AS jumlah_pesan,
                COALESCE((
                    SELECT SUM(dpr.jumlah_terima)
                    FROM detail_penerimaan dpr
                    JOIN penerimaan pr ON dpr.idpenerimaan = pr.idpenerimaan
                    WHERE pr.idpengadaan = dp.idpengadaan
                    AND dpr.idbarang = dp.idbarang
                ), 0) AS total_terima
            FROM detail_pengadaan dp
            WHERE dp.idpengadaan = ?
        ");
        $cek->execute([$idpengadaan]);
        $items = $cek->fetchAll(PDO::FETCH_ASSOC);

        $semuaLengkap = true;
        foreach ($items as $item) {
            if ($item['total_terima'] < $item['jumlah_pesan']) {
                $semuaLengkap = false;
                break;
            }
        }

        // Update status pengadaan
        $statusBaru = $semuaLengkap ? '2' : '1'; // 2 = Selesai, 1 = Proses

        $update = $this->conn->prepare("
            UPDATE pengadaan 
            SET status = ? 
            WHERE idpengadaan = ?
        ");
        $update->execute([$statusBaru, $idpengadaan]);
    }

    /**
     * Ambil detail penerimaan berdasarkan ID
     */
    public function getPenerimaanById($id)
    {
        $sql = "SELECT 
                    r.idpenerimaan,
                    r.created_at,
                    r.status,
                    r.idpengadaan,
                    u.username AS penerima,
                    v.nama_vendor,
                    p.timestamp AS tgl_pengadaan
                FROM penerimaan r
                LEFT JOIN user u ON r.iduser = u.iduser
                LEFT JOIN pengadaan p ON r.idpengadaan = p.idpengadaan
                LEFT JOIN vendor v ON p.idvendor = v.idvendor
                WHERE r.idpenerimaan = ?";

        return $this->db->fetch($sql, [$id]);
    }

    /**
     * Ambil detail barang yang diterima
     */
    public function getDetailItems($idpenerimaan)
    {
        return $this->db->fetchAll("
            SELECT 
                d.iddetail_penerimaan,
                d.idbarang,
                b.nama,
                s.nama_satuan,
                d.jumlah_terima,
                d.harga_satuan_terima,
                d.sub_total_terima
            FROM detail_penerimaan d
            LEFT JOIN barang b ON d.idbarang = b.idbarang
            LEFT JOIN satuan s ON b.idsatuan = s.idsatuan
            WHERE d.idpenerimaan = ?
        ", [$idpenerimaan]);
    }

    /**
     * Hapus penerimaan (rollback stok)
     */
    public function hapus($idpenerimaan)
    {
        try {
            $this->conn->beginTransaction();

            // 1. Ambil semua detail untuk rollback stok
            $details = $this->getDetailItems($idpenerimaan);

            foreach ($details as $detail) {
                $idbarang = $detail['idbarang'];
                $jumlah = $detail['jumlah_terima'];

                // Ambil stok terakhir
                $cekStok = $this->conn->prepare("
                    SELECT stock 
                    FROM kartu_stok 
                    WHERE idbarang = ?
                    ORDER BY idkartu_stok DESC
                    LIMIT 1
                ");
                $cekStok->execute([$idbarang]);
                $lastStock = $cekStok->fetchColumn() ?: 0;

                // Kurangi stok
                $stockAkhir = $lastStock - $jumlah;

                // Insert kartu stok (keluar untuk rollback)
                $insertKS = $this->conn->prepare("
                    INSERT INTO kartu_stok 
                    (jenis_transaksi, masuk, keluar, stock, created_at, idtransaksi, idbarang)
                    VALUES ('K', 0, ?, ?, NOW(), ?, ?)
                ");
                $insertKS->execute([
                    $jumlah,
                    $stockAkhir,
                    $idpenerimaan,
                    $idbarang
                ]);
            }

            // 2. Hapus detail penerimaan
            $stmtDetail = $this->conn->prepare("DELETE FROM detail_penerimaan WHERE idpenerimaan = ?");
            $stmtDetail->execute([$idpenerimaan]);

            // 3. Ambil idpengadaan sebelum dihapus
            $penerimaan = $this->getPenerimaanById($idpenerimaan);
            $idpengadaan = $penerimaan['idpengadaan'];

            // 4. Hapus header penerimaan
            $stmtHeader = $this->conn->prepare("DELETE FROM penerimaan WHERE idpenerimaan = ?");
            $stmtHeader->execute([$idpenerimaan]);

            // 5. Update status pengadaan
            $this->updateStatusPengadaan($idpengadaan);

            $this->conn->commit();
            return true;

        } catch (Exception $e) {
            $this->conn->rollBack();
            throw $e;
        }
    }

    public function updateHeader($idpenerimaan, $idpengadaan, $status)
    {
        $this->db->execute(
            "UPDATE penerimaan SET idpengadaan=?, status=? WHERE idpenerimaan=?",
            [$idpengadaan, $status, $idpenerimaan]
        );
    }

public function getDetailById($iddetail) {
    return $this->db->fetch(
        "SELECT d.*, b.nama 
         FROM detail_penerimaan d 
         LEFT JOIN barang b ON d.idbarang = b.idbarang 
         WHERE d.iddetail_penerimaan = ?", 
        [$iddetail]
    );
}
    public function updateDetailItem($iddetail, $jumlah_terima, $harga_satuan_terima, $sub_total_terima)
    {
        $this->db->execute(
            "UPDATE detail_penerimaan SET jumlah_terima=?, harga_satuan_terima=?, sub_total_terima=? WHERE iddetail_penerimaan=?",
            [$jumlah_terima, $harga_satuan_terima, $sub_total_terima, $iddetail]
        );
    }

    public function generateIdPenerimaan()
    {
        $stmt = $this->db->query("SELECT IFNULL(MAX(idpenerimaan), 0) + 1 AS idp FROM penerimaan");
        $row = $stmt->fetch();
        return $row['idp'];
    }

    public function deleteDetailItems($idpenerimaan)
    {
        $this->db->execute("DELETE FROM detail_penerimaan WHERE idpenerimaan = ?", [$idpenerimaan]);
    }

    public function deletePenerimaan($idpenerimaan)
    {
        $this->db->execute("DELETE FROM penerimaan WHERE idpenerimaan = ?", [$idpenerimaan]);
    }
}
?>