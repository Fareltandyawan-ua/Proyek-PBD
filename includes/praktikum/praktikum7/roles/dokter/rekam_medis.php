<?php
require_once '../../database/dbconnection.php';
require_once '../../class/class_rekam_medis.php';
require_once '../../class/class_detail_rekam_medis.php';

$db = new DBConnection();
$db->init_connect();
$rekamMedis = new RekamMedis($db->dbconn);
$detail = new DetailRekamMedis($db->dbconn);

$data = $rekamMedis->getAllWithReservasi();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Rekam Medis</title>
    <link rel="stylesheet" href="../../css/data_master.css">
    <link rel="stylesheet" href="../../css/data_pemilik.css">
    <link rel="stylesheet" href="../../css/rekam_medis_dokter.css">
</head>
<body>
<div class="header">
    <div class="logo"></div>
    <h2>Data Rekam Medis</h2>
    <div class="nav">
        <a href="dashboard_dokter.php">Dashboard</a>
        <a href="rekam_medis.php">Rekam Medis</a>
        <a href="../../auth/logout.php">Logout</a>
    </div>
</div>
<div class="container">
    <table>
        <tr><th>ID</th><th>Reservasi</th><th>Anamnesa</th><th>Temuan Klinis</th><th>Diagnosa</th><th>Dokter Pemeriksa</th><th>Detail</th></tr>
        <?php foreach ($data as $row) { ?>
            <tr>
                <td><?= $row['idrekam_medis'] ?></td>
                <td><?= $row['idreservasi_dokter'] ?></td>
                <td><?= htmlspecialchars($row['anamnesa']) ?></td>
                <td><?= htmlspecialchars($row['temuan_klinis']) ?></td>
                <td><?= htmlspecialchars($row['diagnosa']) ?></td>
                <td><?= htmlspecialchars($row['dokter_pemeriksa']) ?></td>
                <td>
                    <a href="?detail=<?= $row['idrekam_medis'] ?>" class="button-detail">Lihat Detail</a>
                </td>
            </tr>
            <?php if (isset($_GET['detail']) && $_GET['detail'] == $row['idrekam_medis']) {
                $details = $detail->getAllByRekamMedis($row['idrekam_medis']); ?>
                <tr><td colspan="7">
                    <b>Detail Tindakan Terapi:</b>
                    <table class="table-detail" width="100%">
                        <tr><th>ID</th><th>Kode</th><th>Deskripsi</th><th>Detail</th></tr>
                        <?php foreach ($details as $d) { ?>
                            <tr>
                                <td><?= $d['iddetail_rekam_medis'] ?></td>
                                <td><?= htmlspecialchars($d['kode']) ?></td>
                                <td><?= htmlspecialchars($d['deskripsi_tindakan_terapi']) ?></td>
                                <td><?= htmlspecialchars($d['detail']) ?></td>
                            </tr>
                        <?php } ?>
                    </table>
                </td></tr>
            <?php } ?>
        <?php } ?>
    </table>
</div>
</body>
</html>
