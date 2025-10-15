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
<link>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../../css/data_master.css">
    <title>Data Master</title>
</head>
<body>
    <div class="header">
        <div class="logo"></div>
        <h2>Data Master</h2>
        <div class="nav">
            <a href="dashboard_admin.php">Dashboard</a>
            <a href="../../auth/logout.php">Logout</a>
        </div>
    </div>
    <div class="content">
        <a class="menu-link" href="data_user.php">Data User</a>
        <a class="menu-link" href="datamaster_role_user.php">Manajemen Role</a>
        <a class="menu-link" href="jenis_hewan.php">Jenis Hewan</a>
        <a class="menu-link" href="ras_hewan.php">Ras Hewan</a>
        <a class="menu-link" href="data_pemilik.php">Data Pemilik</a>
        <a class="menu-link" href="data_pet.php">Data Pet</a>
        <a class="menu-link" href="data_kategori.php">Data Kategori</a>
        <a class="menu-link" href="data_kategori_klinis.php">Data Kategori Klinis</a>
        <a class="menu-link" href="data_kode_tindakan_terapi.php">Data Kode Tindakan Terapi</a>
    </div>
</body>
</html>
