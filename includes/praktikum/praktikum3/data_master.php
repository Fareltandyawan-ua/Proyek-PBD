<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role_aktif'] != 1) {
    header("Location: login.php");
    exit();
}
$nama = $_SESSION['user']['nama'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Master</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; }
        .header { background-color: #4FC3F7; color: white; padding: 10px 20px; display: flex; justify-content: space-between; align-items: center; }
        .logo { width: 40px; height: 40px; background-color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; }
        .nav a { color: white; text-decoration: none; margin-left: 20px; }
        .content { padding: 20px; }
        .menu-link { display: block; margin: 10px 0 0 0; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo"></div>
        <h2>Data Master</h2>
        <div class="nav">
            <a href="dashboard_admin.php">Dashboard</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>
    <div class="content">
        <a class="menu-link" href="data_user.php">Data User</a>
        <a class="menu-link" href="datamaster_role_user.php">Manajemen Role</a>
    </div>
</body>
</html>
