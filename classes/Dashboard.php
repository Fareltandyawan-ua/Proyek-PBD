<?php
require_once 'Database.php';

class Dashboard {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }
    
    public function getStatistics() {
        $stats = [];
        
        // Count barang
        try {
            $result = $this->db->fetch("SELECT COUNT(*) as total FROM barang WHERE status = 1");
            $stats['total_barang'] = $result['total'];
        } catch(Exception $e) {
            $stats['total_barang'] = 0;
        }
        
        // Count user
        try {
            $result = $this->db->fetch("SELECT COUNT(*) as total FROM user");
            $stats['total_user'] = $result['total'];
        } catch(Exception $e) {
            $stats['total_user'] = 0;
        }
        
        // Count pengadaan
        try {
            $result = $this->db->fetch("SELECT COUNT(*) as total FROM pengadaan");
            $stats['total_pengadaan'] = $result['total'];
        } catch(Exception $e) {
            $stats['total_pengadaan'] = 0;
        }
        
        // Count satuan
        try {
            $result = $this->db->fetch("SELECT COUNT(*) as total FROM satuan");
            $stats['total_satuan'] = $result['total'];
        } catch(Exception $e) {
            $stats['total_satuan'] = 0;
        }
        
        return $stats;
    }
    
    public function getRecentBarang($limit = 5) {
        try {
            return $this->db->fetchAll("SELECT * FROM V_BARANG_DETAIL LIMIT ?", [$limit]);
        } catch(Exception $e) {
            return [];
        }
    }
}
?>