<?php
require_once 'Database.php';

class Barang {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }
    
    public function getAll() {
        $sql = "SELECT b.*, s.nama_satuan 
                FROM barang b 
                LEFT JOIN satuan s ON b.idsatuan = s.idsatuan 
                WHERE b.status = 1 
                ORDER BY b.idbarang DESC";
        return $this->db->fetchAll($sql);
    }
    
    public function getById($id) {
        $sql = "SELECT b.*, s.nama_satuan 
                FROM barang b 
                LEFT JOIN satuan s ON b.idsatuan = s.idsatuan 
                WHERE b.idbarang = ?";
        return $this->db->fetch($sql, [$id]);
    }
    
    public function create($data) {
        $sql = "INSERT INTO barang (jenis, nama, idsatuan, status) VALUES (?, ?, ?, 1)";
        return $this->db->execute($sql, [$data['jenis'], $data['nama'], $data['idsatuan']]);
    }
    
    public function update($id, $data) {
        $sql = "UPDATE barang SET jenis = ?, nama = ?, idsatuan = ? WHERE idbarang = ?";
        return $this->db->execute($sql, [$data['jenis'], $data['nama'], $data['idsatuan'], $id]);
    }
    
    public function delete($id) {
        $sql = "UPDATE barang SET status = 0 WHERE idbarang = ?";
        return $this->db->execute($sql, [$id]);
    }
    
    public function count() {
        $sql = "SELECT COUNT(*) as total FROM barang WHERE status = 1";
        $result = $this->db->fetch($sql);
        return $result['total'];
    }
    
    public function getRecent($limit = 5) {
        $sql = "SELECT * FROM barang WHERE status = 1 ORDER BY idbarang DESC LIMIT ?";
        return $this->db->fetchAll($sql, [$limit]);
    }
}
?>