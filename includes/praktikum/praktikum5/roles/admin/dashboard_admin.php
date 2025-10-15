<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role_aktif'] != 1) {
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
    <title>Dashboard Admin</title>
</head>
<body>
    <div class="header">
        <div class="logo"></div>
        <h2>Halo, <?= htmlspecialchars($nama) ?></h2>
        <div class="nav">
            <a href="data_master.php">Data Master</a>
            <a href="../../auth/logout.php">Logout</a>
        </div>
    </div>
    <div class="content">
        <p>Anda login sebagai <b>Administrator</b>.</p>
        <p>Selamat datang di halaman admin.</p>
    </div>
</body>
</html>