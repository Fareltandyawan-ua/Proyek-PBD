<?php
session_start();
include_once "../../database/dbconnection.php";
include_once "../../class/class_pemilik.php";

$db = new DBConnection();
$db->init_connect();
$dbconn = $db->dbconn;

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Buat instance Pemilik dengan data dari form
    $pemilik = new Pemilik(
        $dbconn,
        0, // idpemilik (auto increment)
        $_POST['no_wa'],
        $_POST['alamat'],
        0, // iduser (akan dibuat otomatis)
        $_POST['nama'],
        $_POST['email'],
        $_POST['password']
    );
    
    // Cek email sudah ada atau belum
    if ($pemilik->checkEmailExists($_POST['email'])) {
        $msg = "Email sudah terdaftar, silakan gunakan email lain!";
    } else {
        // Proses registrasi pemilik baru
        $result = $pemilik->createWithUser();
        $msg = $result['message'];
    }
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
                if (strpos($msg, 'sudah terdaftar') !== false || strpos($msg, 'gagal') !== false || strpos($msg, 'Gagal') !== false) {
                    $msg_class = 'msg-error';
                }
            ?>
            <div class="<?= $msg_class ?>">
                <?= htmlspecialchars($msg) ?>
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