<?php
session_start();

include_once "../../../database/dbconnection.php";
include_once "../../../class/classes.php";

// Inisialisasi koneksi database
$db = new DBConnection();
$db->init_connect();
$dbconn = $db->dbconn; // Ambil objek mysqli dari properti dbconn

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = isset($_POST['role']) ? (int)$_POST['role'] : 2; // default dokter

    // Validasi sederhana
    if (empty($nama) || empty($email) || empty($password)) {
        $_SESSION['flash_msg'] = "Semua field wajib diisi!";
    } else {
        // Cek email sudah ada
        $cek = $dbconn->prepare("SELECT iduser FROM user WHERE email = ?");
        $cek->bind_param("s", $email);
        $cek->execute();
        $cek->store_result();
        if ($cek->num_rows > 0) {
            $_SESSION['flash_msg'] = "Email sudah terdaftar!";
        } else {
            // Gunakan class User untuk insert user
            $user = new User(0, $nama, $email, $password);
            if ($user->create($dbconn)) {
                $iduser = $dbconn->insert_id;
                // Insert ke role_user (boleh tetap manual, atau buat class UserRole jika ingin full OOP)
                $stmt2 = $dbconn->prepare("INSERT INTO role_user (iduser, idrole, status) VALUES (?, ?, 1)");
                $stmt2->bind_param("ii", $iduser, $role);
                $stmt2->execute();
                $_SESSION['flash_msg'] = "User berhasil ditambahkan!";
                header("Location: ../data_user.php");
                exit();
            } else {
                $_SESSION['flash_msg'] = "Gagal menambah user.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../../../css/tambah_user.css">
    <title>Tambah User</title>
</head>
<body>
    <div class="container">
        <h2>Tambah User</h2>
        <?php if (isset($_SESSION['flash_msg'])): ?>
            <div class="msg"><?= $_SESSION['flash_msg']; unset($_SESSION['flash_msg']); ?></div>
        <?php endif; ?>
        <form method="post">
            <label>Nama</label>
            <input type="text" name="nama" required>
            <label>Email</label>
            <input type="email" name="email" required>
            <label>Password</label>
            <input type="password" name="password" required>
            <label>Role</label>
            <select name="role">
                <option value="1">Administrator</option>
                <option value="2">Dokter</option>
                <option value="3">Perawat</option>
                <option value="4">Resepsionis</option>
            </select>
            <button type="submit">Tambah</button>
            <button type="button" onclick="window.location.href='../data_user.php'">Kembali</button>
        </form>
    </div>
</body>
</html>
<?php $dbconn->close(); ?>
