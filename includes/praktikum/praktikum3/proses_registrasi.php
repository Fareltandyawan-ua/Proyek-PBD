<?php
include_once("dbconnection.php");
include_once("classes.php");

session_start();

// Inisialisasi koneksi database
$db = new DBConnection();
$db->init_connect();
$dbconn = $db->dbconn;

$nama = $_POST['nama'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$retype_password = $_POST['retype_password'] ?? '';

if ($password !== $retype_password) {
    $_SESSION['flash_msg'] = "Password dan Retype Password tidak cocok.";
    $_SESSION['flash_success'] = false;
    header("location: login.php");
    exit();
}

// CEK EMAIL SUDAH ADA ATAU BELUM
$stmt = $dbconn->prepare("SELECT iduser FROM user WHERE email=?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    $_SESSION['flash_msg'] = "Email sudah terdaftar. Silakan gunakan email lain.";
    $_SESSION['flash_success'] = false;
    $stmt->close();
    $dbconn->close();
    header("location: login.php");
    exit();
}
$stmt->close();

// Buat user baru dengan OOP
$user = new User(0, $nama, $email, $password); // password akan di-hash otomatis di constructor
if ($user->create($dbconn)) {
    $_SESSION['flash_msg'] = "Registrasi berhasil.";
    $_SESSION['flash_success'] = true;
    $dbconn->close();
    header("location: login.php");
    exit();
} else {
    $_SESSION['flash_msg'] = "Terjadi kesalahan. Silakan coba lagi.";
    $_SESSION['flash_success'] = false;
    $dbconn->close();
    header("location: login.php");
    exit();
}
?>