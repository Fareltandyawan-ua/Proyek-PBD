<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role_aktif'] != 2) {
    header("Location: ../../auth/login.php");
    exit();
}
$nama = $_SESSION['user']['nama'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../../css/dashboard_dokter.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Dokter</title>
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
        <p>Selamat datang di halaman Dokter.</p>
        <p>Anda login sebagai <b>Dokter</b>.</p>
    </div>
</body>
</html>