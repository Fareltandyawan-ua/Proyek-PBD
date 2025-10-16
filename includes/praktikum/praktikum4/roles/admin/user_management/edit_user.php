<?php
session_start();
include_once "../../../database/dbconnection.php";
include_once "../../../database/classes.php";

// Inisialisasi koneksi database
$db = new DBConnection();
$db->init_connect();
$dbconn = $db->dbconn;

if (!isset($_GET['id'])) {
    header("Location: ../data_user.php");
    exit();
}
$iduser = (int)$_GET['id'];

// Ambil data user dengan OOP
$user = User::getById($dbconn, $iduser);
if (!$user) {
    $_SESSION['flash_msg'] = "User tidak ditemukan!";
    header("Location: ../data_user.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_nama = $_POST['nama'];
    if (!empty($new_nama)) {
        // Update nama user dengan OOP
        $user = new User($iduser, $new_nama, $user->getEmail(), $user->getPassword(), true);
        if ($user->update($dbconn)) {
            $_SESSION['flash_msg'] = "Nama user berhasil diubah!";
            header("Location: ../data_user.php");
            exit();
        } else {
            $_SESSION['flash_msg'] = "Gagal mengubah nama user.";
        }
    } else {
        $_SESSION['flash_msg'] = "Nama tidak boleh kosong!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit User</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; }
        .container { max-width: 400px; margin: 40px auto; background: #f7f7f7; padding: 24px; border-radius: 8px; }
        label { display: block; margin-bottom: 6px; }
        input { width: 100%; padding: 8px; margin-bottom: 14px; border: 1px solid #ccc; border-radius: 4px; }
        button { background: #4FC3F7; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; }
        button:hover { background: #039be5; }
        .msg { color: red; margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Nama User</h2>
        <?php if (isset($_SESSION['flash_msg'])): ?>
            <div class="msg"><?= $_SESSION['flash_msg']; unset($_SESSION['flash_msg']); ?></div>
        <?php endif; ?>
        <form method="post">
            <label>Nama</label>
            <input type="text" name="nama" value="<?= htmlspecialchars($user->getNama()) ?>" required>
            <button type="submit">Simpan</button>
            <button type="button" onclick="window.location.href='../data_user.php'">Kembali</button>
        </form>
    </div>
</body>
</html>
<?php $dbconn->close(); ?>
