<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role_aktif'] != 3) {
    header("Location: ../../auth/login.php");
    exit();
}
$nama = $_SESSION['user']['nama'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../../css/dashboard_perawat.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Perawat</title>
</head>
<body>
    <div class="header">
        <div class="logo"></div>
        <h2>Halo, <?= htmlspecialchars($nama) ?></h2>
         <div class="nav">
        <a href="dashboard_perawat.php">Dashboard</a>
        <a href="rekam_medis.php">Rekam Medis</a>
        <a href="detail_rekam_medis.php">Detail Rekam Medis</a>
        <a href="../../auth/logout.php">Logout</a>
    </div>
    </div>
    <div class="content">
        <p>Selamat datang di halaman Perawat.</p>
        <p>Anda login sebagai <b>Perawat</b>.</p>
    </div>
</body>
</html>