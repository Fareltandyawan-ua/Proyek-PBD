<?php
session_start();
require_once '../../classes/Auth.php';
require_once '../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    try {
        $db = new DBConnection();
        $db->init_connect();
        $dbconn = $db->dbconn;

        // Ambil data user dan role
        $query = "SELECT u.*, r.nama_role 
                  FROM user u 
                  JOIN role r ON u.idrole = r.idrole 
                  WHERE username = ? AND password = ?";
        $stmt = $dbconn->prepare($query);
        $stmt->execute([$username, $password]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $_SESSION['user'] = [
                'iduser' => $user['iduser'],
                'username' => $user['username'],
                'role' => $user['nama_role']
            ];

            // Arahkan berdasarkan role
            if ($user['nama_role'] === 'Admin') {
                header("Location: dashboard/admin/index.php");
                exit;
            } elseif ($user['nama_role'] === 'Super Admin') {
                header("Location: dashboard/superadmin/index.php");
                exit;
            } else {
                header("Location: login.php?error=Role tidak dikenal");
                exit;
            }
        } else {
            header("Location: login.php?error=Username atau password salah");
            exit;
        }
    } catch (PDOException $e) {
        die("Koneksi gagal: " . $e->getMessage());
    }
}
?>
