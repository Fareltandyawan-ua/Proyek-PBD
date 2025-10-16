<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role_aktif'] != 5) {
    header("Location: ../../auth/login.php");
    exit();
}
$nama = $_SESSION['user']['nama'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../../css/dashboard_admin.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pemilik</title>
</head>
<body>
    <div class="header">
        <div class="logo"></div>
        <h2>Halo, <?= htmlspecialchars($nama) ?></h2>
        <div class="nav">
            <a href="../../auth/logout.php">Logout</a>
        </div>
    </div>
    <div class="content">
        <p>Anda login sebagai <b>Pemilik</b>.</p>
        <p>Selamat datang di halaman pemilik.</p>
        <br>
        <a class="menu-link" href="daftar_pet.php">Daftar Pet</a>
        <a class="menu-link" href="daftar_reservasi.php">Daftar Reservasi</a>
        <a class="menu-link" href="daftar_rekam_medis.php">Daftar Rekam Medis & Detail</a>
    </div>
</body>
</html>