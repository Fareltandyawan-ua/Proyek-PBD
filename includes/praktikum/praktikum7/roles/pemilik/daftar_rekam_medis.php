<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role_aktif'] != 5) {
    header("Location: ../../auth/login.php");
    exit();
}
require_once '../../database/dbconnection.php';
require_once '../../class/class_rekam_medis.php';
require_once '../../class/class_detail_rekam_medis.php';

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
$data_rekam = [];
if (!$idpemilik) {
    $not_found = true;
} else {
    // Ambil data rekam medis berdasarkan idpemilik
    $sql = "SELECT r.*, p.nama AS nama_pet
            FROM rekam_medis r
            JOIN temu_dokter t ON r.idreservasi_dokter = t.idreservasi_dokter
            JOIN pet p ON t.idpet = p.idpet
            WHERE p.idpemilik = ?
            ORDER BY r.created_at DESC";
    $stmt = $db->dbconn->prepare($sql);
    $stmt->bind_param("i", $idpemilik);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $data_rekam[] = $row;
    }
    $stmt->close();
    $detail = new DetailRekamMedis($db->dbconn);
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Daftar Rekam Medis & Detail</title>
    <link rel="stylesheet" type="text/css" href="../../css/data_master.css">
    <link rel="stylesheet" type="text/css" href="../../css/daftar_rekam_medis.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <div class="header">
        <div class="logo"></div>
        <h2>Daftar Rekam Medis & Detail</h2>
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
        <?php } elseif (count($data_rekam) > 0) { ?>
        <?php foreach ($data_rekam as $rekam) { ?>
        <div class="rekam-box">
            <h4>ID Rekam Medis: <?= htmlspecialchars($rekam['idrekam_medis']) ?> | Pet:
                <?= htmlspecialchars($rekam['nama_pet']) ?></h4>
            <table>
                <tr>
                    <th>Anamnesa</th>
                    <th>Temuan Klinis</th>
                    <th>Diagnosa</th>
                    <th>Tanggal</th>
                </tr>
                <tr>
                    <td><?= htmlspecialchars($rekam['anamnesa']) ?></td>
                    <td><?= htmlspecialchars($rekam['temuan_klinis']) ?></td>
                    <td><?= htmlspecialchars($rekam['diagnosa']) ?></td>
                    <td><?= htmlspecialchars($rekam['created_at']) ?></td>
                </tr>
            </table>
            <div class="detail-title">Detail Tindakan Terapi:</div>
            <ul>
                <?php
                        $details = $detail->getByRekamMedis($rekam['idrekam_medis']);
                        $has_detail = false;
                        while ($d = $details->fetch_assoc()) {
                            $has_detail = true;
                            echo "<li><b>{$d['kode']}</b> - {$d['deskripsi_tindakan_terapi']}: " . htmlspecialchars($d['detail']) . "</li>";
                        }
                        if (!$has_detail) {
                            echo "<li style='color:#888;'>Belum ada detail tindakan terapi.</li>";
                        }
                        ?>
            </ul>
        </div>
        <?php } ?>
        <?php } else { ?>
        <div style="text-align:center; color:#888; margin-top:32px;">
            Anda belum memiliki data rekam medis.
        </div>
        <?php } ?>
        <br>
        <!-- <a href="dashboard_pemilik.php" class="button" style="background:#4FC3F7;margin-top:16px;">
            < Kembali </a> -->
    </div>
</body>

</html>