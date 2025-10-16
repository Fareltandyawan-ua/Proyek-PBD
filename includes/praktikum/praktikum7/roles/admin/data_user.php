<?php
session_start();
include_once "../../database/dbconnection.php";
include_once "../../class/class_user.php";

// Inisialisasi koneksi database
$db = new DBConnection();
$db->init_connect();
$dbconn = $db->dbconn; // Ambil objek mysqli dari properti dbconn

// Ambil data user dari class User (OOP)
$users = User::getAll($dbconn);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../../css/data_user.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen User</title>
</head>
<body>
    <div class="header">
        <div class="logo"></div>
        <h2>Data User</h2>
        <div class="nav">
            <a href="dashboard_admin.php">Dashboard</a>
            <a href="../../auth/logout.php">Logout</a>
        </div>
    </div>
    <div class="content">
        <?php if (isset($_SESSION['flash_msg'])): ?>
            <div class="msg"><?= $_SESSION['flash_msg']; unset($_SESSION['flash_msg']); ?></div>
        <?php endif; ?>
        <button class="tambah-btn" onclick="window.location.href='user_management/tambah_user.php'">Tambah User</button>
        <table>
            <tr>
                <th>ID User</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Aksi</th>
            </tr>
            <?php foreach($users as $user): ?>
            <tr>
                <td><?= htmlspecialchars($user->getIduser()) ?></td>
                <td><?= htmlspecialchars($user->getNama()) ?></td>
                <td><?= htmlspecialchars($user->getEmail()) ?></td>
                <td>
                    <a class="aksi-link" href="user_management/edit_user.php?id=<?= $user->getIduser() ?>">Edit</a>
                    <a class="aksi-link" href="user_management/reset_password.php?id=<?= $user->getIduser() ?>">Reset Password</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <br>
        <a href="data_master.php" class="btn-kembali">
            <span class="btn-icon">←</span>
            <span class="btn-text">Kembali</span>
        </a>
    </div>
</body>
</html>
<?php $dbconn->close(); ?>
