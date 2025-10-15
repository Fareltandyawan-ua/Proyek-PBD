<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role_aktif'] != 5) {
    header("Location: ../../auth/login.php");
    exit();
}
require_once '../../database/dbconnection.php';
require_once '../../class/class_pet.php';

$db = new DBConnection();
$db->init_connect();

$iduser = $_SESSION['user']['id'];

// Query idpemilik berdasarkan iduser
$idpemilik = null;
$stmt = $db->dbconn->prepare("SELECT idpemilik FROM pemilik WHERE iduser=? LIMIT 1");
$stmt->bind_param("i", $iduser);
$stmt->execute();
$stmt->bind_result($idpemilik);
$stmt->fetch();
$stmt->close();

$not_found = false;
if (!$idpemilik) {
    $not_found = true;
    $data_pet = [];
} else {
    $data_pet = Pet::getByPemilik($db->dbconn, $idpemilik);
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Daftar Pet Saya</title>
    <link rel="stylesheet" type="text/css" href="../../css/data_pet.css">
    <link rel="stylesheet" type="text/css" href="../../css/data_master.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <div class="header">
        <div class="logo"></div>
        <h2>Daftar Pet Anda</h2>
        <div class="nav">
            <a href="dashboard_pemilik.php">Dashboard</a>
            <a href="../../auth/logout.php">Logout</a>
        </div>
    </div>
    <div class="content">
        <?php if ($not_found) { ?>
            <div class="notfound-box">
                <h3>Data Pemilik Tidak Ditemukan</h3>
                <p>
                    Maaf, data pemilik untuk akun Anda belum terdaftar.<br>
                    Silakan hubungi admin atau resepsionis untuk melakukan registrasi data pemilik.<br>
                    <br>
                    <b>Tips:</b> Pastikan akun Anda sudah terhubung dengan data pemilik di sistem.
                </p>
                <a href="dashboard_pemilik.php" class="button" style="background:#4FC3F7;margin-top:16px;">
                     Kembali ke Dashboard </a>
            </div>
        <?php } elseif (count($data_pet) > 0) { ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Tanggal Lahir</th>
                        <th>Warna/Tanda</th>
                        <th>Jenis Kelamin</th>
                        <th>ID Ras Hewan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data_pet as $pet) { ?>
                        <tr>
                            <td><?= htmlspecialchars($pet['idpet']) ?></td>
                            <td><?= htmlspecialchars($pet['nama']) ?></td>
                            <td><?= htmlspecialchars($pet['tanggal_lahir']) ?></td>
                            <td><?= htmlspecialchars($pet['warna_tanda']) ?></td>
                            <td><?= htmlspecialchars($pet['jenis_kelamin']) ?></td>
                            <td><?= htmlspecialchars($pet['idras_hewan']) ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <br>
            <a href="dashboard_pemilik.php" class="button" style="background:#4FC3F7;margin-top:16px;">
                < Kembali </a>
                <?php } else { ?>
                    <div style="text-align:center; color:#888; margin-top:32px;">
                        Anda belum memiliki data pet.
                    </div>
                    <br>
                    <br>
                <?php } ?>
    </div>
</body>

</html>