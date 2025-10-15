<?php
session_start();
include_once "../../database/dbconnection.php";
include_once "../../class/class_jenis_hewan.php";

$db = new DBConnection();
$db->init_connect();
$dbconn = $db->dbconn;

// Proses tambah jenis
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nama_jenis_hewan']) && !isset($_POST['update_jenis'])) {
    $jenis = new JenisHewan(0, trim($_POST['nama_jenis_hewan']));
    if ($jenis->create($dbconn)) {
        $_SESSION['flash_msg'] = "Jenis hewan berhasil ditambahkan!";
    }
    header("Location: jenis_hewan.php");
    exit();
}

// Proses update jenis
if (isset($_POST['update_jenis'], $_POST['idjenis_hewan'], $_POST['nama_jenis_hewan'])) {
    $jenis = new JenisHewan((int) $_POST['idjenis_hewan'], trim($_POST['nama_jenis_hewan']));
    if ($jenis->update($dbconn)) {
        $_SESSION['flash_msg'] = "Jenis hewan berhasil diupdate!";
    }
    header("Location: jenis_hewan.php");
    exit();
}

// Proses delete jenis
if (isset($_GET['delete_jenis'])) {
    $jenis = JenisHewan::getById($dbconn, (int) $_GET['delete_jenis']);
    if ($jenis && $jenis->delete($dbconn)) {
        $_SESSION['flash_msg'] = "Jenis hewan berhasil dihapus!";
    }
    header("Location: jenis_hewan.php");
    exit();
}

// Ambil semua jenis hewan
$jenis_hewan = JenisHewan::getAll($dbconn);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../../css/jenis_hewan.css">
    <title>Jenis Hewan</title>
</head>

<body>
    <div class="header">
        <div class="logo"></div>
        <h2>Jenis Hewan</h2>
        <div class="nav">
            <a href="../../auth/logout.php">Logout</a>
        </div>
    </div>
    <div class="content">
        <?php if (isset($_SESSION['flash_msg'])): ?>
            <div class="msg"><?= $_SESSION['flash_msg'];
            unset($_SESSION['flash_msg']); ?></div>
        <?php endif; ?>
        <button class="tambah-btn" onclick="window.location.href='jenis_hewan.php?tambah_jenis=1'">Tambah Jenis
            Hewan</button>
        <table>
            <tr>
                <th>ID</th>
                <th>Nama Jenis Hewan</th>
                <th>Aksi</th>
            </tr>
            <?php foreach ($jenis_hewan as $jenis): ?>
                <tr>
                    <td><?= $jenis->getIdjenis_hewan() ?></td>
                    <td><?= htmlspecialchars($jenis->getNamaJenisHewan()) ?></td>
                    <td>
                        <a class="aksi-link" href="jenis_hewan.php?edit_jenis=<?= $jenis->getIdjenis_hewan() ?>">update</a>
                        <a class="aksi-link" href="jenis_hewan.php?delete_jenis=<?= $jenis->getIdjenis_hewan() ?>"
                            onclick="return confirm('Hapus jenis hewan ini?')">delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <br>
        <a class="aksi-link" href="data_master.php"><- Kembali ke Data Master</a>

                <!-- Form Tambah Jenis -->
                <?php if (isset($_GET['tambah_jenis']) && !isset($_GET['edit_jenis'])): ?>
                    <div class="form-popup">
                        <form method="post">
                            <label>Nama Jenis Hewan:</label>
                            <input type="text" name="nama_jenis_hewan" required>
                            <button type="submit">Simpan</button>
                            <button type="button" onclick="window.location.href='jenis_hewan.php'">Batal</button>
                        </form>
                    </div>
                <?php endif; ?>

                <!-- Form Edit Jenis -->
                <?php if (isset($_GET['edit_jenis'])):
                    $edit_jenis = JenisHewan::getById($dbconn, (int) $_GET['edit_jenis']);
                    if ($edit_jenis): ?>
                        <div class="form-popup">
                            <form method="post">
                                <input type="hidden" name="update_jenis" value="1">
                                <input type="hidden" name="idjenis_hewan" value="<?= $edit_jenis->getIdjenis_hewan() ?>">
                                <label>Nama Jenis Hewan:</label>
                                <input type="text" name="nama_jenis_hewan"
                                    value="<?= htmlspecialchars($edit_jenis->getNamaJenisHewan()) ?>" required>
                                <button type="submit">Update</button>
                                <button type="button" onclick="window.location.href='jenis_hewan.php'">Batal</button>
                            </form>
                        </div>
                    <?php endif; endif; ?>
    </div>
</body>

</html>
<?php $dbconn->close(); ?>