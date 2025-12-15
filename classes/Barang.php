<?php
require_once 'Database.php';

class Barang
{
    private $db;
    private $conn;

    public function __construct()
    {
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
    }

    // Ambil semua barang
    public function getAll()
    {
        return $this->db->fetchAll("SELECT * FROM V_BARANG_DETAIL ORDER BY idbarang ASC");
    }

    // Ambil barang berdasarkan ID
    public function getById($id)
    {
        return $this->db->fetch("SELECT * FROM barang WHERE idbarang = ?", [$id]);
    }

    // Tambah barang
    public function add($data)
    {
        try {
            $stmt = $this->conn->prepare("CALL sp_tambah_barang(:jenis, :nama, :idsatuan, :status)");
            $stmt->bindParam(':jenis', $data['jenis']);
            $stmt->bindParam(':nama', $data['nama']);
            $stmt->bindParam(':harga', $data['harga']);
            $stmt->bindParam(':idsatuan', $data['idsatuan']);
            $stmt->bindParam(':status', $data['status']);
            $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Gagal menambah barang: " . $e->getMessage());
        }
    }

    // Update barang
    public function update($id, $data)
    {
        try {
            $sql = "UPDATE barang 
                    SET nama = :nama, jenis = :jenis, idsatuan = :idsatuan, harga = :harga, status = :status
                    WHERE idbarang = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':nama', $data['nama']);
            $stmt->bindParam(':jenis', $data['jenis']);
            $stmt->bindParam(':idsatuan', $data['idsatuan']);
            $stmt->bindParam(':harga', $data['harga']);
            $stmt->bindParam(':status', $data['status']);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Gagal memperbarui barang: " . $e->getMessage());
        }
    }

    // Hapus barang
    public function delete($id)
    {
        return $this->db->execute("DELETE FROM barang WHERE idbarang = ?", [$id]);
    }

    // Ambil Semua Barang
    // public function getAll($status = null) {
    //     $query = "SELECT * FROM v_barang";
    //     if ($status !== null && $status !== 'all') {
    //         $query .= " WHERE status = ?";
    //         return $this->db->fetchAll($query, [$status]);
    //     }
    //     return $this->db->fetchAll($query);
    // }
}
?>