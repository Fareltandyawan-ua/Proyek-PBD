<?php
session_start();
include_once "../../database/dbconnection.php";
include_once "../../class/class_ras_hewan.php";
include_once "../../class/class_jenis_hewan.php";

$db = new DBConnection();
$db->init_connect();
$dbconn = $db->dbconn;

// Ambil semua jenis hewan
$jenis_hewan = JenisHewan::getAll($dbconn);

// Proses tambah ras
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idjenis_hewan'], $_POST['nama_ras']) && empty($_POST['update_ras'])) {
    $ras = new RasHewan(0, trim($_POST['nama_ras']), (int) $_POST['idjenis_hewan']);
    if ($ras->create($dbconn)) {
        $_SESSION['flash_msg'] = "Ras berhasil ditambahkan!";
    }
    header("Location: ras_hewan.php");
    exit();
}

// Proses update ras
if (isset($_POST['update_ras'], $_POST['idras_hewan'], $_POST['nama_ras'], $_POST['idjenis_hewan'])) {
    $ras = new RasHewan((int) $_POST['idras_hewan'], trim($_POST['nama_ras']), (int) $_POST['idjenis_hewan']);
    if ($ras->update($dbconn)) {
        $_SESSION['flash_msg'] = "Ras berhasil diupdate!";
    }
    header("Location: ras_hewan.php");
    exit();
}

// Proses delete ras
if (isset($_GET['delete_ras'])) {
    $ras = RasHewan::getById($dbconn, (int) $_GET['delete_ras']);
    if ($ras && $ras->delete($dbconn)) {
        $_SESSION['flash_msg'] = "Ras berhasil dihapus!";
    }
    header("Location: ras_hewan.php");
    exit();
}

// Ambil semua ras hewan per jenis
$ras_per_jenis = [];
foreach ($jenis_hewan as $jenis) {
    $ras_per_jenis[$jenis->getIdjenis_hewan()] = RasHewan::getByJenis($dbconn, $jenis->getIdjenis_hewan());
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../../css/ras_hewan.css">
    <title>Ras Hewan</title>
    <script>
        function showForm(idjenis, idras = null, nama_ras = '') {
            document.getElementById('popup-form').classList.add('active');
            document.getElementById('idjenis_input').value = idjenis;
            if (idras) {
                document.getElementById('idras_input').value = idras;
                document.getElementById('nama_ras_input').value = nama_ras;
                document.getElementById('update_ras_input').value = '1';
                document.getElementById('form-title').innerText = 'Edit Ras Hewan';
            } else {
                document.getElementById('idras_input').value = '';
                document.getElementById('nama_ras_input').value = '';
                document.getElementById('update_ras_input').value = '';
                document.getElementById('form-title').innerText = 'Tambah Ras Hewan';
            }
        }
        function hideForm() {
            document.getElementById('popup-form').classList.remove('active');
        }
    </script>
</head>

<body>
    <div class="header">
        <div class="logo"></div>
        <h2>Ras Hewan</h2>
        <div class="nav">
            <a href="../../auth/logout.php">Logout</a>
        </div>
    </div>
    <div class="content">
        <?php if (isset($_SESSION['flash_msg'])): ?>
            <div class="msg"><?= $_SESSION['flash_msg'];
            unset($_SESSION['flash_msg']); ?></div>
        <?php endif; ?>
        <table>
            <tr>
                <th width="10%">ID</th>
                <th width="20%">Jenis Hewan</th>
                <th width="50%">Ras Hewan</th>
                <th width="20%">Aksi</th>
            </tr>
            <?php foreach ($jenis_hewan as $jenis): ?>
                <tr>
                    <td><?= $jenis->getIdjenis_hewan() ?></td>
                    <td><?= htmlspecialchars($jenis->getNamaJenisHewan()) ?></td>
                    <td style="text-align:left">
                        <?php foreach ($ras_per_jenis[$jenis->getIdjenis_hewan()] as $ras): ?>
                            <?= htmlspecialchars($ras->getNamaRas()) ?>
                            <a class="aksi-link" href="javascript:void(0)"
                                onclick="showForm(<?= $jenis->getIdjenis_hewan() ?>, <?= $ras->getIdrasHewan() ?>, '<?= htmlspecialchars(addslashes($ras->getNamaRas())) ?>')">update</a>
                            <a class="aksi-link" href="ras_hewan.php?delete_ras=<?= $ras->getIdrasHewan() ?>">delete</a>
                            <br>
                        <?php endforeach; ?>
                    </td>
                    <td>
                        <a class="aksi-link" href="javascript:void(0)"
                            onclick="showForm(<?= $jenis->getIdjenis_hewan() ?>)">Tambah Ras</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <br>
        <a class="aksi-link" href="data_master.php"><- Kembali ke Data Master</a>
    </div>

    <!-- Popup Form Tambah/Edit Ras -->
    <div class="popup-form" id="popup-form">
        <div class="popup-content">
            <h3 id="form-title">Tambah Ras Hewan</h3>
            <form method="post" action="ras_hewan.php">
                <input type="hidden" name="idjenis_hewan" id="idjenis_input" value="">
                <input type="hidden" name="idras_hewan" id="idras_input" value="">
                <input type="hidden" name="update_ras" id="update_ras_input" value="">
                <label>Nama Ras Hewan:</label>
                <input type="text" name="nama_ras" id="nama_ras_input" required>
                <br>
                <button type="submit" class="tambah-btn">Simpan</button>
                <button type="button" class="tambah-btn" onclick="hideForm()">Batal</button>
            </form>
        </div>
    </div>
</body>

</html>
<?php $dbconn->close(); ?>