<?php
require_once 'Database.php';

class Satuan {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // 🔹 Ambil semua data satuan
    public function getAll() {
        return $this->db->fetchAll("SELECT * FROM satuan ORDER BY idsatuan ASC");
    }

    // 🔹 Ambil satu data berdasarkan ID
    public function getById($id) {
        return $this->db->fetch("SELECT * FROM satuan WHERE idsatuan = ?", [$id]);
    }

    // 🔹 Tambah satuan baru
    public function add($data) {
        $sql = "INSERT INTO satuan (nama_satuan) VALUES (?)";
        return $this->db->execute($sql, [$data['nama_satuan']]);
    }

    // 🔹 Update satuan
    public function update($id, $data) {
        $sql = "UPDATE satuan SET nama_satuan=? WHERE idsatuan=?";
        return $this->db->execute($sql, [$data['nama_satuan'], $id]);
    }

    // 🔹 Hapus satuan
    public function delete($id) {
        return $this->db->execute("DELETE FROM satuan WHERE idsatuan = ?", [$id]);
    }
}
?>
