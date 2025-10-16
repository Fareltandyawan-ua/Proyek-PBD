<?php
session_start();
include_once "../../../database/dbconnection.php";
include_once "../../../class/classes.php";

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
    <link rel="stylesheet" type="text/css" href="../../../css/edit_user.css">
    <title>Edit User</title>
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
