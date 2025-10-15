<?php
session_start();
include_once "../../../database/dbconnection.php";
include_once "../../../class/class_user.php";

// Inisialisasi koneksi database
$db = new DBConnection();
$db->init_connect();
$dbconn = $db->dbconn;

if (!isset($_GET['id'])) {
    header("Location: ../data_user.php");
    exit();
}
$iduser = (int)$_GET['id'];

// Ambil user dengan OOP
$user = User::getById($dbconn, $iduser);
if (!$user) {
    $_SESSION['flash_msg'] = "User tidak ditemukan!";
    header("Location: ../data_user.php");
    exit();
}

// Reset password ke '123456'
$new_password = '123456';
$user = new User($iduser, $user->getNama(), $user->getEmail(), $new_password); // password belum di-hash, biar constructor hash otomatis
if ($user->update($dbconn)) {
    $_SESSION['flash_msg'] = "Password berhasil direset ke: <b>$new_password</b> (hanya tampil sekali, simpan password ini!)";
} else {
    $_SESSION['flash_msg'] = "Gagal reset password.";
}
$dbconn->close();
header("Location: ../data_user.php");
exit();
