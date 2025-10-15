<?php
require_once '../../database/dbconnection.php';
require_once '../../class/class_rekam_medis.php';

$db = new DBConnection();
$db->init_connect();
$rekamMedis = new RekamMedis($db->dbconn);

// Ambil data reservasi dari temu_dokter
$reservasi = $db->send_query('SELECT * FROM temu_dokter');

// CRUD
if (isset($_POST['tambah'])) {
    $rekamMedis->create($_POST);
    header('Location: rekam_medis.php');
    exit;
}
if (isset($_POST['update'])) {
    $rekamMedis->update($_POST);
    header('Location: rekam_medis.php');
    exit;
}
if (isset($_GET['delete'])) {
    $rekamMedis->delete($_GET['delete']);
    header('Location: rekam_medis.php');
    exit;
}
$edit_data = null;
if (isset($_GET['edit'])) {
    $edit_data = $rekamMedis->getById($_GET['edit']);
}
$data = $rekamMedis->getAllWithReservasi();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Rekam Medis</title>
    <link rel="stylesheet" href="../../css/data_master.css">
    <link rel="stylesheet" href="../../css/rekam_medis_perawat.css">
</head>

<body>
    <div class="header">
        <div class="logo"></div>
        <h2>Rekam Medis</h2>
        <div class="nav">
            <a href="../../auth/logout.php">Logout</a>
        </div>
    </div>
    <div class="pemilik-container">
        <form method="post">
            <?php if ($edit_data) { ?>
                <input type="hidden" name="idrekam_medis" value="<?= htmlspecialchars($edit_data['idrekam_medis']) ?>">
            <?php } ?>
            <select name="idreservasi_dokter" required onchange="this.form.submit()">
                <option value="">Pilih Reservasi</option>
                <?php foreach ($reservasi['data'] as $r) {
                    $selected = ($edit_data && $edit_data['idreservasi_dokter'] == $r['idreservasi_dokter']) || (isset($_POST['idreservasi_dokter']) && $_POST['idreservasi_dokter'] == $r['idreservasi_dokter']) ? 'selected' : '';
                    echo '<option value="' . $r['idreservasi_dokter'] . '" ' . $selected . '>No: ' . $r['no_urut'] . ' | Pet: ' . $r['idpet'] . '</option>';
                } ?>
            </select>
            <input type="text" name="anamnesa" placeholder="Anamnesa"
                value="<?= $edit_data ? htmlspecialchars($edit_data['anamnesa']) : ($_POST['anamnesa'] ?? '') ?>"
                required>
            <input type="text" name="temuan_klinis" placeholder="Temuan Klinis"
                value="<?= $edit_data ? htmlspecialchars($edit_data['temuan_klinis']) : '' ?>" required>
            <input type="text" name="diagnosa" placeholder="Diagnosa"
                value="<?= $edit_data ? htmlspecialchars($edit_data['diagnosa']) : '' ?>" required>
            <?php
            // Ambil semua dokter pemeriksa (idrole_user) dari temu_dokter sesuai reservasi yang dipilih
            $dokter_pilihan = [];
            $selected_reservasi = null;
            if ($edit_data) {
                $selected_reservasi = $edit_data['idreservasi_dokter'];
            } elseif (isset($_POST['idreservasi_dokter'])) {
                $selected_reservasi = $_POST['idreservasi_dokter'];
            }
            if ($selected_reservasi) {
                $q = $db->send_query("SELECT t.idrole_user, u.nama FROM temu_dokter t JOIN role_user ru ON t.idrole_user = ru.idrole_user JOIN user u ON ru.iduser = u.iduser WHERE t.idreservasi_dokter = " . intval($selected_reservasi));
                if (!empty($q['data'])) {
                    $dokter_pilihan = $q['data'];
                }
            }
            if (count($dokter_pilihan) === 1) {
                $dokter = $dokter_pilihan[0];
                echo '<input type="hidden" name="dokter_pemeriksa" value="' . $dokter['idrole_user'] . '">';
                echo '<input type="text" value="' . htmlspecialchars($dokter['nama']) . '" readonly>';
            } elseif (count($dokter_pilihan) > 1) {
                echo '<select name="dokter_pemeriksa" required>';
                echo '<option value="">Pilih Dokter Pemeriksa</option>';
                foreach ($dokter_pilihan as $dokter) {
                    $selected = ($edit_data && $edit_data['dokter_pemeriksa'] == $dokter['idrole_user']) ? 'selected' : '';
                    echo '<option value="' . $dokter['idrole_user'] . '" ' . $selected . '>' . htmlspecialchars($dokter['nama']) . '</option>';
                }
                echo '</select>';
            } else {
                echo '<input type="text" value="Tidak ada dokter terdaftar pada reservasi ini" readonly style="background:#eee;color:#888;">';
            }
            ?>
            <button type="submit" name="<?= $edit_data ? 'update' : 'tambah' ?>"
                class="button"><?= $edit_data ? 'Update' : 'Tambah' ?></button>
            <?php if ($edit_data) { ?>
                <a href="rekam_medis.php" class="button batal">Batal</a>
            <?php } ?>
        </form>
        <table>
            <tr>
                <th>ID</th>
                <th>Reservasi</th>
                <th>Anamnesa</th>
                <th>Temuan Klinis</th>
                <th>Diagnosa</th>
                <th>Dokter Pemeriksa</th>
                <th>Aksi</th>
            </tr>
            <?php foreach ($data as $row) { ?>
                <tr>
                    <td><?= $row['idrekam_medis'] ?></td>
                    <td><?= $row['idreservasi_dokter'] ?></td>
                    <td><?= htmlspecialchars($row['anamnesa']) ?></td>
                    <td><?= htmlspecialchars($row['temuan_klinis']) ?></td>
                    <td><?= htmlspecialchars($row['diagnosa']) ?></td>
                    <td><?= htmlspecialchars($row['dokter_pemeriksa']) ?></td>
                    <td>
                        <a href="?edit=<?= $row['idrekam_medis'] ?>" class="button">Edit</a>
                        <a href="?delete=<?= $row['idrekam_medis'] ?>" class="button" style="background:#d90429;"
                            onclick="return confirm('Hapus data ini?')">Delete</a>
                        <a href="detail_rekam_medis.php?idrekam_medis=<?= $row['idrekam_medis'] ?>" class="button"
                            style="background:#43aa8b;">Lihat Detail</a>
                    </td>
                </tr>
            <?php } ?>
        </table>
        <br>
         <a href="dashboard_perawat.php" class="button" style="background:#4FC3F7;margin-top:16px;">< Kembali </a>
    </div>
</body>

</html>