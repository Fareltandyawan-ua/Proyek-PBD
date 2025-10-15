<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role_aktif'] != 5) {
    header("Location: ../../auth/login.php");
    exit();
}
require_once '../../database/dbconnection.php';

$db = new DBConnection();
$db->init_connect();

$iduser = $_SESSION['user']['id'];

// Ambil idpemilik berdasarkan iduser
$idpemilik = null;
$stmt = $db->dbconn->prepare("SELECT idpemilik FROM pemilik WHERE iduser=? LIMIT 1");
$stmt->bind_param("i", $iduser);
$stmt->execute();
$stmt->bind_result($idpemilik);
$stmt->fetch();
$stmt->close();

$not_found = false;
$data_reservasi = [];
if (!$idpemilik) {
    $not_found = true;
} else {
    // Ambil data reservasi berdasarkan idpemilik
    $sql = "SELECT t.idreservasi_dokter, t.no_urut, t.waktu_daftar, t.status, p.nama AS nama_pet
            FROM temu_dokter t
            JOIN pet p ON t.idpet = p.idpet
            WHERE p.idpemilik = ?
            ORDER BY t.waktu_daftar DESC";
    $stmt = $db->dbconn->prepare($sql);
    $stmt->bind_param("i", $idpemilik);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $data_reservasi[] = $row;
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Reservasi Saya</title>
    <link rel="stylesheet" type="text/css" href="../../css/data_master.css">
    <link rel="stylesheet" type="text/css" href="../../css/dashboard_resepsionis.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <div class="header">
        <div class="logo"></div>
        <h2>Daftar Reservasi Anda</h2>
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
                <a href="dashboard_pemilik.php" class="button">Kembali ke Dashboard</a>
            </div>
        <?php } elseif (count($data_reservasi) > 0) { ?>
            <table>
                <thead>
                    <tr>
                        <th>ID Reservasi</th>
                        <th>Nama Pet</th>
                        <th>No Urut</th>
                        <th>Waktu Daftar</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data_reservasi as $r) { ?>
                    <tr>
                        <td><?= htmlspecialchars($r['idreservasi_dokter']) ?></td>
                        <td><?= htmlspecialchars($r['nama_pet']) ?></td>
                        <td><?= htmlspecialchars($r['no_urut']) ?></td>
                        <td><?= htmlspecialchars($r['waktu_daftar']) ?></td>
                        <td><?= htmlspecialchars($r['status']) ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <div style="text-align:center; color:#888; margin-top:32px;">
                Anda belum memiliki data reservasi.
            </div>
        <?php } ?>
        <br>
        <!-- <a href="dashboard_pemilik.php" class="button">< Kembali </a> -->
    </div>
</body>
</html>