<?php
require_once 'Database.php';

class Role {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAll() {
        return $this->db->fetchAll("SELECT * FROM role ORDER BY idrole ASC");
    }

    public function getById($id) {
        return $this->db->fetch("SELECT * FROM role WHERE idrole = ?", [$id]);
    }

    public function add($nama_role) {
        return $this->db->execute("INSERT INTO role (nama_role) VALUES (?)", [$nama_role]);
    }

    public function update($id, $nama_role) {
        return $this->db->execute("UPDATE role SET nama_role=? WHERE idrole=?", [$nama_role, $id]);
    }

    public function delete($id) {
        return $this->db->execute("DELETE FROM role WHERE idrole=?", [$id]);
    }
}
?>
