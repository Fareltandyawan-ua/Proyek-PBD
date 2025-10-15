<?php
session_start();
include_once "../../database/dbconnection.php";
include_once "../../class/class_pet.php";
$db = new DBConnection();
$db->init_connect();
$dbconn = $db->dbconn;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Proses simpan data pet
    $pet = new Pet(
        0,
        $_POST['nama'],
        $_POST['tanggal_lahir'],
        $_POST['warna_tanda'],
        $_POST['jenis_kelamin'],
        $_POST['idpemilik'],
        $_POST['idras_hewan']
    );
    if ($pet->create($dbconn)) {
        $msg = "Registrasi pet berhasil!";
    } else {
        $msg = "Registrasi gagal!";
    }
}

// Ambil data pemilik untuk dropdown
$pemiliks = [];
$pemilik_result = $dbconn->query("SELECT idpemilik, no_wa, alamat FROM pemilik");
while ($row = $pemilik_result->fetch_assoc()) $pemiliks[] = $row;

// Ambil data ras hewan untuk dropdown
$ras_hewan = [];
$ras_result = $dbconn->query("SELECT idras_hewan, nama_ras FROM ras_hewan");
while ($row = $ras_result->fetch_assoc()) $ras_hewan[] = $row;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Registrasi Pet</title>
    <link rel="stylesheet" type="text/css" href="../../css/dashboard_resepsionis.css">
</head>
<body>
    <div class="header">
        <div class="logo"></div>
        <h2>Registrasi Pet</h2>
        <div class="nav">
            <a href="dashboard_resepsionis.php">Dashboard</a>
            <a href="../../auth/logout.php">Logout</a>
        </div>
    </div>
    <div class="content">
        <?php if (!empty($msg)) echo "<div class='msg'>$msg</div>"; ?>
        <form method="post" class="form-box">
            <label>Nama Pet</label>
            <input type="text" name="nama" required>
            <label>Tanggal Lahir</label>
            <input type="date" name="tanggal_lahir" required>
            <label>Warna Tanda</label>
            <input type="text" name="warna_tanda" required>
            <label>Jenis Kelamin</label>
            <select name="jenis_kelamin" required>
                <option value="">-- Pilih --</option>
                <option value="L">Jantan</option>
                <option value="P">Betina</option>
            </select>
            <label>ID Pemilik</label>
            <select name="idpemilik" required>
                <option value="">-- Pilih Pemilik --</option>
                <?php foreach ($pemiliks as $pemilik): ?>
                    <option value="<?= $pemilik['idpemilik'] ?>">
                        <?= $pemilik['idpemilik'] ?> - <?= htmlspecialchars($pemilik['no_wa']) ?> (<?= htmlspecialchars($pemilik['alamat']) ?>)
                    </option>
                <?php endforeach; ?>
            </select>
            <label>ID Ras Hewan</label>
            <select name="idras_hewan" required>
                <option value="">-- Pilih Ras Hewan --</option>
                <?php foreach ($ras_hewan as $ras): ?>
                    <option value="<?= $ras['idras_hewan'] ?>">
                        <?= $ras['idras_hewan'] ?> - <?= htmlspecialchars($ras['nama_ras']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <div class="form-actions">
                <a class="tambah-btn" href="dashboard_resepsionis.php">Kembali</a>
                <button type="submit" class="tambah-btn">Registrasi</button>
            </div>
        </form>
    </div>
</body>
</html>
<?php $dbconn->close(); ?>