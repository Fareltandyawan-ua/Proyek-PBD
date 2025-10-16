<?php
include_once "dbconnection.php";
include_once "classes.php";
session_start(); // Pindahkan session_start ke awal untuk konsistensi

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

try {
    // Buat objek DBConnection dan inisialisasi koneksi
    $db = new DBConnection();
    $db->init_connect();
    $dbconn = $db->dbconn;
    
    // Panggil function login OOP
    $userData = User::login($dbconn, $email, $password);
    $_SESSION['user'] = $userData;

    // Redirect berdasarkan role
    switch ($userData['role_aktif']) {
        case 1: header("Location: dashboard_admin.php"); break;
        case 2: header("Location: dashboard_dokter.php"); break;
        case 3: header("Location: dashboard_perawat.php"); break;
        case 4: header("Location: dashboard_resepsionis.php"); break;
        default:
            $_SESSION['flash_msg'] = "Role tidak dikenali.";
            header("Location: login.php");
    }
    exit();

} catch (Exception $e) {
    $_SESSION['flash_msg'] = $e->getMessage();
    header("Location: login.php");
    exit();
} finally {
    if (isset($db)) {
        $db->close_connection();
    }
}
?>