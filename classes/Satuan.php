<?php
require_once 'Database.php';

class Satuan {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
    }

    // Ambil semua data satuan
    public function getAll() {
        return $this->db->fetchAll("SELECT * FROM v_satuan ORDER BY idsatuan ASC");
    }

    // Ambil satu data berdasarkan ID
    public function getById($id) {
        return $this->db->fetch("SELECT * FROM satuan WHERE idsatuan = ?", [$id]);
    }

    // Tambah satuan baru
    public function add($data) {
        try {
            $stmt = $this->conn->prepare("INSERT INTO satuan (nama_satuan, status) VALUES (:nama_satuan, :status)");
            $stmt->bindParam(':nama_satuan', $data['nama_satuan']);
            $stmt->bindParam(':status', $data['status']);
            $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Gagal menambah satuan: " . $e->getMessage());
        }
    }

    // Update satuan
    public function update($id, $data) {
        try {
            $stmt = $this->conn->prepare("UPDATE satuan SET nama_satuan = :nama_satuan, status = :status WHERE idsatuan = :id");
            $stmt->bindParam(':nama_satuan', $data['nama_satuan']);
            $stmt->bindParam(':status', $data['status']);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Gagal memperbarui satuan: " . $e->getMessage());
        }
    }

    // Hapus satuan
    public function delete($id) {
        return $this->db->execute("DELETE FROM satuan WHERE idsatuan = ?", [$id]);
    }
}
?>
