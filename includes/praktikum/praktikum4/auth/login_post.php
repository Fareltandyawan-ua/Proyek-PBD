<?php
include_once "../database/dbconnection.php";
include_once "../database/classes.php";
session_start();

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

try {
    $db = new DBConnection();
    $db->init_connect();
    $dbconn = $db->dbconn;

    $userData = User::login($dbconn, $email, $password);
    $_SESSION['user'] = $userData;

    // Redirect berdasarkan role
    switch ($userData['role_aktif']) {
        case 1: header("Location: ../roles/admin/dashboard_admin.php"); break;
        case 2: header("Location: ../roles/dokter/dashboard_dokter.php"); break;
        case 3: header("Location: ../roles/perawat/dashboard_perawat.php"); break;
        case 4: header("Location: ../roles/resepsionis/dashboard_resepsionis.php"); break;
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
    if (isset($db)) $db->close_connection();
}
?>