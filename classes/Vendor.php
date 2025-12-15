<?php
require_once 'Database.php';

class Vendor {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Ambil semua data vendor
    public function getAll() {
        return $this->db->fetchAll("SELECT * FROM v_vendor ORDER BY idvendor ASC");
    }

    // Ambil satu data vendor berdasarkan ID
    public function getById($id) {
        return $this->db->fetch("SELECT * FROM vendor WHERE idvendor = ?", [$id]);
    }

    // Tambah vendor baru
    public function add($data) {
        $sql = "INSERT INTO vendor (nama_vendor, badan_hukum, status) VALUES (?, ?, ?)";
        return $this->db->execute($sql, [
            $data['nama_vendor'],
            $data['badan_hukum'],
            $data['status']
        ]);
    }

    // Update data vendor
    public function update($id, $data) {
        $sql = "UPDATE vendor SET nama_vendor=?, badan_hukum=?, status=? WHERE idvendor=?";
        return $this->db->execute($sql, [
            $data['nama_vendor'],
            $data['badan_hukum'],
            $data['status'],
            $id
        ]);
    }

    // Hapus vendor
    public function delete($id) {
        return $this->db->execute("DELETE FROM vendor WHERE idvendor = ?", [$id]);
    }
}
?>
