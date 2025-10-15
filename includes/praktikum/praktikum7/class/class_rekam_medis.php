<?php
class RekamMedis {
    private $db;
    public function __construct($dbconn) {
        $this->db = $dbconn;
    }
    public function create($data) {
        $stmt = $this->db->prepare('INSERT INTO rekam_medis (created_at, anamnesa, temuan_klinis, diagnosa, idreservasi_dokter, dokter_pemeriksa) VALUES (NOW(), ?, ?, ?, ?, ?)');
        $stmt->bind_param('sssii', $data['anamnesa'], $data['temuan_klinis'], $data['diagnosa'], $data['idreservasi_dokter'], $data['dokter_pemeriksa']);
        $stmt->execute();
        $stmt->close();
    }
    public function update($data) {
        $stmt = $this->db->prepare('UPDATE rekam_medis SET anamnesa=?, temuan_klinis=?, diagnosa=?, idreservasi_dokter=?, dokter_pemeriksa=? WHERE idrekam_medis=?');
        $stmt->bind_param('sssiii', $data['anamnesa'], $data['temuan_klinis'], $data['diagnosa'], $data['idreservasi_dokter'], $data['dokter_pemeriksa'], $data['idrekam_medis']);
        $stmt->execute();
        $stmt->close();
    }
    public function delete($id) {
        // Hapus dulu detail rekam medis yang berelasi
        $stmt1 = $this->db->prepare('DELETE FROM detail_rekam_medis WHERE idrekam_medis=?');
        $stmt1->bind_param('i', $id);
        $stmt1->execute();
        $stmt1->close();
        // Baru hapus rekam medis
        $stmt2 = $this->db->prepare('DELETE FROM rekam_medis WHERE idrekam_medis=?');
        $stmt2->bind_param('i', $id);
        $stmt2->execute();
        $stmt2->close();
    }
    public function getById($id) {
        $stmt = $this->db->prepare('SELECT * FROM rekam_medis WHERE idrekam_medis=?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        $stmt->close();
        return $data;
    }
    public function getAll() {
        $result = $this->db->query('SELECT * FROM rekam_medis');
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }
    public function getAllWithReservasi() {
    // Kolom yang benar dari temu_dokter: no_urut, waktu_daftar, status, idpet, idreservasi_dokter, idkode_user
    $sql = 'SELECT r.*, t.no_urut, t.waktu_daftar, t.status, t.idpet, t.idrole_user FROM rekam_medis r LEFT JOIN temu_dokter t ON r.idreservasi_dokter = t.idreservasi_dokter';
        $result = $this->db->query($sql);
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }
    public static function getByPemilik($db, $idpemilik) {
        $sql = "SELECT r.*, p.nama AS nama_pet
                FROM rekam_medis r
                JOIN temu_dokter t ON r.idreservasi_dokter = t.idreservasi_dokter
                JOIN pet p ON t.idpet = p.idpet
                WHERE p.idpemilik = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $idpemilik);
        $stmt->execute();
        return $stmt->get_result();
    }
}
