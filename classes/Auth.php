<?php
require_once 'Database.php';

class Auth {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    public function login($username, $password) {
        try {
            $sql = "SELECT u.iduser, u.username, u.password, u.idrole, r.nama_role 
                    FROM user u 
                    JOIN role r ON u.idrole = r.idrole 
                    WHERE u.username = ?";
            
            $user = $this->db->fetch($sql, [$username]);
            
            if ($user && $password === $user['password']) {
                $_SESSION['user_id'] = $user['iduser'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role_id'] = $user['idrole'];
                $_SESSION['role_name'] = $user['nama_role'];
                return true;
            }
            return false;
        } catch(Exception $e) {
            return false;
        }
    }
    
    public function logout() {
        session_destroy();
        header('Location: ../auth/login.php');
        exit;
    }
    
    public function checkLogin() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ../auth/login.php');
            exit;
        }
    }
    
    public function checkRole($allowed_roles = []) {
        $this->checkLogin();
        
        if (!empty($allowed_roles) && !in_array($_SESSION['role_id'], $allowed_roles)) {
            header('Location: ../dashboard/index.php?error=access_denied');
            exit;
        }
    }
    
    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
    
    public function getUserData() {
        if ($this->isLoggedIn()) {
            return [
                'id' => $_SESSION['user_id'],
                'username' => $_SESSION['username'],
                'role_id' => $_SESSION['role_id'],
                'role_name' => $_SESSION['role_name']
            ];
        }
        return null;
    }
    
    public function getUserId() {
        return $_SESSION['user_id'] ?? 0;
    }
    
    public function getUsername() {
        return $_SESSION['username'] ?? '';
    }
    
    public function getUserRole() {
        return $_SESSION['role_name'] ?? '';
    }
    
    public function getRoleId() {
        return $_SESSION['role_id'] ?? 0;
    }
}
?>