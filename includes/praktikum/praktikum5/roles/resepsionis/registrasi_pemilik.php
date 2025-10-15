<?php
session_start();
include_once "../../database/dbconnection.php";
include_once "../../class/class_pemilik.php";

$db = new DBConnection();
$db->init_connect();
$dbconn = $db->dbconn;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Cek email sudah ada atau belum
    $email = $_POST['email'];
    $cek = $dbconn->prepare("SELECT iduser FROM user WHERE email=?");
    $cek->bind_param("s", $email);
    $cek->execute();
    $cek->store_result();
    if ($cek->num_rows > 0) {
        $msg = "Email sudah terdaftar, silakan gunakan email lain!";
    } else {
        // Proses simpan data pemilik
        $pemilik = new pemilik(0, $_POST['no_wa'], $_POST['alamat'], 0, $_POST['nama'], $email, $_POST['password']);
        if ($pemilik->create($dbconn)) {
            $msg = "Registrasi pemilik berhasil!";
        } else {
            $msg = "Registrasi gagal!";
        }
    }
    $cek->close();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Registrasi Pemilik</title>
    <link rel="stylesheet" type="text/css" href="../../css/dashboard_resepsionis.css">
</head>
<body>
    <div class="header">
        <div class="logo"></div>
        <h2>Registrasi Pemilik</h2>
        <div class="nav">
            <a href="dashboard_resepsionis.php">Dashboard</a>
            <a href="../../auth/logout.php">Logout</a>
        </div>
    </div>
    <div class="content">
        <?php if (!empty($msg)): ?>
            <?php
                $msg_class = 'msg-success';
                if ($msg == 'Email sudah terdaftar, silakan gunakan email lain!' || $msg == 'Registrasi gagal!') {
                    $msg_class = 'msg-error';
                }
            ?>
            <div class="<?= $msg_class ?>">
                <?= $msg ?>
            </div>
        <?php endif; ?>
        <form method="post" class="form-box">
            <label>Nama Pemilik</label>
            <input type="text" name="nama" required>
            <label>Email</label>
            <input type="email" name="email" required>
            <label>Password</label>
            <input type="password" name="password" required>
            <label>No. WA</label>
            <input type="text" name="no_wa" required>
            <label>Alamat</label>
            <input type="text" name="alamat" required>
            <div class="form-actions">
                <a class="tambah-btn" href="dashboard_resepsionis.php">Kembali</a>
                <button type="submit" class="tambah-btn">Registrasi</button>
            </div>
        </form>
    </div>
</body>
</html>
<?php $dbconn->close(); ?>