<?php
require_once '../../database/dbconnection.php';
require_once '../../class/class_detail_rekam_medis.php';

$db = new DBConnection();
$db->init_connect();
$detail = new DetailRekamMedis($db->dbconn);

// Ambil data rekam medis dan kode tindakan terapi
$rekam_medis = $db->send_query('SELECT * FROM rekam_medis');
$kode_terapi = $db->send_query('SELECT * FROM kode_tindakan_terapi');

// CRUD
if (isset($_POST['tambah'])) {
    $detail->create($_POST);
    header('Location: detail_rekam_medis.php');
    exit;
}
if (isset($_POST['update'])) {
    $detail->update($_POST);
    header('Location: detail_rekam_medis.php');
    exit;
}
if (isset($_GET['delete'])) {
    $detail->delete($_GET['delete']);
    header('Location: detail_rekam_medis.php');
    exit;
}
$edit_data = null;
if (isset($_GET['edit'])) {
    $edit_data = $detail->getById($_GET['edit']);
}
// Filter by rekam medis jika ada
$idrekam_medis = isset($_GET['idrekam_medis']) ? intval($_GET['idrekam_medis']) : null;
$data = $idrekam_medis ? $detail->getAllByRekamMedis($idrekam_medis) : [];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Rekam Medis</title>
    <link rel="stylesheet" href="../../css/detail_rekam_medis_perawat.css">
</head>
<body>
<div class="header">
    <div class="logo"></div>
    <h2>Detail Rekam Medis</h2>
    <div class="nav">
        <a href="dashboard_perawat.php">Dashboard</a>
        <a href="rekam_medis.php">Rekam Medis</a>
        <a href="detail_rekam_medis.php">Detail Rekam Medis</a>
        <a href="../../auth/logout.php">Logout</a>
    </div>
</div>
<div class="pemilik-container">
    <form method="post">
        <?php if ($edit_data) { ?>
            <input type="hidden" name="iddetail_rekam_medis" value="<?= htmlspecialchars($edit_data['iddetail_rekam_medis']) ?>">
        <?php } ?>
        <select name="idrekam_medis" required>
            <option value="">Pilih Rekam Medis</option>
            <?php foreach ($rekam_medis['data'] as $r) {
                $selected = ($edit_data && $edit_data['idrekam_medis'] == $r['idrekam_medis']) ? 'selected' : '';
                echo '<option value="' . $r['idrekam_medis'] . '" ' . $selected . '>ID: ' . $r['idrekam_medis'] . ' | Diagnosa: ' . htmlspecialchars($r['diagnosa']) . '</option>';
            } ?>
        </select>
        <select name="idkode_tindakan_terapi" required>
            <option value="">Pilih Tindakan Terapi</option>
            <?php foreach ($kode_terapi['data'] as $k) {
                $selected = ($edit_data && $edit_data['idkode_tindakan_terapi'] == $k['idkode_tindakan_terapi']) ? 'selected' : '';
                echo '<option value="' . $k['idkode_tindakan_terapi'] . '" ' . $selected . '>Kode: ' . htmlspecialchars($k['kode']) . ' | ' . htmlspecialchars($k['deskripsi_tindakan_terapi']) . '</option>';
            } ?>
        </select>
        <input type="text" name="detail" placeholder="Detail" value="<?= $edit_data ? htmlspecialchars($edit_data['detail']) : '' ?>" required>
        <button type="submit" name="<?= $edit_data ? 'update' : 'tambah' ?>" class="button"><?= $edit_data ? 'Update' : 'Tambah' ?></button>
        <?php if ($edit_data) { ?>
            <a href="detail_rekam_medis.php" class="button batal">Batal</a>
        <?php } ?>
    </form>
    <?php if ($idrekam_medis) { ?>
    <table>
        <tr><th>ID</th><th>Tindakan Terapi</th><th>Detail</th><th>Aksi</th></tr>
        <?php foreach ($data as $row) { ?>
            <tr>
                <td><?= $row['iddetail_rekam_medis'] ?></td>
                <td><?= htmlspecialchars($row['kode']) ?> - <?= htmlspecialchars($row['deskripsi_tindakan_terapi']) ?></td>
                <td><?= htmlspecialchars($row['detail']) ?></td>
                <td>
                    <a href="?edit=<?= $row['iddetail_rekam_medis'] ?>&idrekam_medis=<?= $idrekam_medis ?>" class="button">Edit</a>
                    <a href="?delete=<?= $row['iddetail_rekam_medis'] ?>&idrekam_medis=<?= $idrekam_medis ?>" class="button" style="background:#d90429;" onclick="return confirm('Hapus data ini?')">Delete</a>
                </td>
            </tr>
        <?php } ?>
    </table>
    <a href="rekam_medis.php" class="button" style="background:#4FC3F7;margin-top:16px;">Kembali ke Rekam Medis</a>
    <?php } ?>
</div>
</body>
</html>
