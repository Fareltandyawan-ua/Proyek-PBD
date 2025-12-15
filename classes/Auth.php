<?php
require_once 'Database.php';

class Auth
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function login($username, $password)
    {
        try {
            $sql = "SELECT u.iduser, u.username, u.password, u.idrole, r.nama_role 
                    FROM user u 
                    JOIN role r ON u.idrole = r.idrole 
                    WHERE u.username = ?";

            $user = $this->db->fetch($sql, [$username]);

            // Ganti dengan password_verify jika password di-hash
            if ($user && $password === $user['password']) {
                $_SESSION['iduser']    = $user['iduser'];
                $_SESSION['username']  = $user['username'];
                $_SESSION['role_id']   = $user['idrole'];
                $_SESSION['role_name'] = $user['nama_role'];
                return true;
            }
            return false;
        } catch (Exception $e) {
            return false;
        }
    }

    // Ambil data user dari session
    public function getUser()
    {
        if (isset($_SESSION['iduser'])) {
            return [
                'iduser'    => $_SESSION['iduser'],
                'username'  => $_SESSION['username'],
                'role_id'   => $_SESSION['role_id'],
                'role_name' => $_SESSION['role_name']
            ];
        }
        return null;
    }

    public function logout()
    {
        session_destroy();
        header('Location: ../auth/login.php');
        exit;
    }

    public function checkLogin()
    {
        if (!isset($_SESSION['iduser'])) {
            header('Location: ../auth/login.php');
            exit;
        }
    }

    public function checkRole($allowed_roles = [])
    {
        $this->checkLogin();

        if (!empty($allowed_roles) && !in_array($_SESSION['role_id'], $allowed_roles)) {
            // Arahkan ke halaman yang sesuai dengan role aktif
            if ($_SESSION['role_id'] == 1) {
                header('Location: ../../dashboard/admin/index.php?error=access_denied');
            } elseif ($_SESSION['role_id'] == 2) {
                header('Location: ../../dashboard/superadmin/index.php?error=access_denied');
            } else {
                header('Location: ../../auth/login.php');
            }
            exit;
        }
    }

    public function isLoggedIn()
    {
        return isset($_SESSION['iduser']);
    }

    public function getUserData()
    {
        return $this->getUser();
    }

    public function getUserId()
    {
        return $_SESSION['iduser'] ?? 0;
    }

    public function getUsername()
    {
        return $_SESSION['username'] ?? '';
    }

    public function getUserRole()
    {
        return $_SESSION['role_name'] ?? '';
    }

    public function getRoleId()
    {
        return $_SESSION['role_id'] ?? 0;
    }
}
?>