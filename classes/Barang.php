<?php
require_once 'Database.php';

class Barang {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // 🔹 Ambil semua barang (misal dari view)
    public function getAll() {
        return $this->db->fetchAll("SELECT * FROM V_BARANG_DETAIL ORDER BY idbarang ASC");
    }

    // 🔹 Ambil barang berdasarkan ID
    public function getById($id) {
        return $this->db->fetch("SELECT * FROM barang WHERE idbarang = ?", [$id]);
    }

    // 🔹 Tambah barang
    public function add($data) {
        $sql = "INSERT INTO barang (nama, jenis, idsatuan, status) VALUES (?, ?, ?, ?)";
        return $this->db->execute($sql, [
            $data['nama'],
            $data['jenis'],
            $data['idsatuan'],
            $data['status']
        ]);
    }

    // 🔹 Update barang
    public function update($id, $data) {
        $sql = "UPDATE barang SET nama=?, jenis=?, idsatuan=?, status=? WHERE idbarang=?";
        return $this->db->execute($sql, [
            $data['nama'],
            $data['jenis'],
            $data['idsatuan'],
            $data['status'],
            $id
        ]);
    }

    // 🔹 Hapus barang
    public function delete($id) {
        return $this->db->execute("DELETE FROM barang WHERE idbarang = ?", [$id]);
    }
}
?>
