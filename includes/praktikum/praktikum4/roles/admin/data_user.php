<?php
session_start();
include_once "../../database/dbconnection.php";
include_once "../../database/classes.php";

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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen User</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; }
        .header { background-color: #4FC3F7; color: white; padding: 10px 20px; display: flex; justify-content: space-between; align-items: center; }
        .logo { width: 40px; height: 40px; background-color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; }
        .nav a { color: white; text-decoration: none; margin-left: 20px; }
        .content { padding: 20px; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #888; padding: 8px; text-align: center; }
        th { background-color: #e0e0e0; }
        .aksi-link { color: #1a0dab; text-decoration: underline; cursor: pointer; margin: 0 5px; }
        .tambah-btn { margin-bottom: 10px; padding: 6px 16px; background: #4FC3F7; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .tambah-btn:hover { background: #039be5; }
        .msg { color: green; margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo"></div>
        <h2>Data User</h2>
        <div class="nav">
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
        <a class="aksi-link" href="data_master.php"><- Kembali ke Data Master</a>
    </div>
</body>
</html>
<?php $dbconn->close(); ?>
