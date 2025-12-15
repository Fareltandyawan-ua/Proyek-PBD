<?php
require_once '../../../classes/Database.php';
$database = new Database();
$db = $database->getConnection();

$inTransaction = false; // <-- Tambahkan flag keamanan

try {

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("Akses tidak valid!");
    }

    // Ambil idpengadaan dan iduser dari POST
    if (!isset($_POST['idpengadaan'])) {
        throw new Exception("Data pengadaan tidak ditemukan!");
    }
    session_start();
    if (!isset($_SESSION['iduser'])) {
        throw new Exception("User belum login!");
    }
    $idpengadaan = $_POST['idpengadaan'];
    $iduser = $_SESSION['iduser'];
    $barang = $_POST['barang'];

    // Mulai transaksi
    $db->beginTransaction();
    $inTransaction = true;

    // Insert ke tabel penerimaan
    $query = "
        INSERT INTO penerimaan
        (created_at, status, idpengadaan, iduser)
        VALUES (NOW(), 1, ?, ?)
    ";
    $stmt = $db->prepare($query);
    $stmt->execute([$idpengadaan, $iduser]);

    // Ambil idpenerimaan yang baru saja dibuat
    $idpenerimaan = $db->lastInsertId();

    foreach ($_POST['barang'] as $idbarang => $item) {
        $jumlah_terima = (int)($item['jumlah_terima'] ?? 0);
        $harga_satuan = (int)($item['harga_satuan'] ?? 0);
        $sub_total_terima = $jumlah_terima * $harga_satuan;

        if ($jumlah_terima <= 0) continue;

        $stmt = $db->prepare("INSERT INTO detail_penerimaan 
            (idpenerimaan, idbarang, jumlah_terima, harga_satuan_terima, sub_total_terima) 
            VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $idpenerimaan,
            $idbarang,
            $jumlah_terima,
            $harga_satuan,
            $sub_total_terima
        ]);
    }

    // Commit transaksi
    $db->commit();
    $inTransaction = false;

    header("Location: index.php?success=added");
    exit;

} catch (Exception $e) {

    // Rollback hanya jika transaksi benar-benar dimulai
    if ($inTransaction) {
        $db->rollBack();
    }

    header("Location: index.php?error=" . urlencode($e->getMessage()));
    exit;
}
?>
