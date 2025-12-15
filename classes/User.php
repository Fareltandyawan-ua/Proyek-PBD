<?php
require_once 'Database.php';

class User {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAll() {
        $sql = "SELECT u.iduser, u.username, u.status, r.nama_role 
                FROM user u 
                LEFT JOIN role r ON u.idrole = r.idrole 
                ORDER BY u.iduser ASC";
        return $this->db->fetchAll($sql);
    }

    public function getById($id) {
        return $this->db->fetch("SELECT * FROM v_user WHERE iduser = ?", [$id]);
    }

    public function add($username, $password, $idrole, $status) {
        $sql = "INSERT INTO user (username, password, idrole, status) VALUES (?, ?, ?, ?)";
        return $this->db->execute($sql, [$username, $password, $idrole, $status]);
    }

    public function update($id, $username, $idrole, $status) {
        $sql = "UPDATE user SET username=?, idrole=?, status=? WHERE iduser=?";
        return $this->db->execute($sql, [$username, $idrole, $status, $id]);
    }

    public function delete($id) {
        $sql = "DELETE FROM user WHERE iduser=?";
        return $this->db->execute($sql, [$id]);
    }
}
?>
