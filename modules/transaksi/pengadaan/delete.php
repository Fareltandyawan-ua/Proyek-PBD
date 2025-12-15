<?php
require_once '../../../classes/Auth.php';
require_once '../../../classes/Database.php';

$auth = new Auth();
$auth->checkRole([1]);

$db = new Database();

if (!isset($_GET['idpengadaan'])) {
    die("ID Pengadaan tidak ditemukan!");
}

$idpengadaan = $_GET['idpengadaan'];

// Cek apakah pengadaan ada
$cek = $db->fetch("SELECT * FROM pengadaan WHERE idpengadaan = ?", [$idpengadaan]);
if (!$cek) {
    die("Pengadaan tidak ditemukan!");
}

// Hapus detail dulu (FK constraint)
$db->execute("DELETE FROM detail_pengadaan WHERE idpengadaan = ?", [$idpengadaan]);

// Hapus master pengadaan
$db->execute("DELETE FROM pengadaan WHERE idpengadaan = ?", [$idpengadaan]);

header("Location: index.php?success=deleted");
exit;
