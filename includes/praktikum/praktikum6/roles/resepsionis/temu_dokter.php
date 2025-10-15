<?php
session_start();
include_once "../../database/dbconnection.php";
include_once "../../class/classes.php";
$db = new DBConnection();
$db->init_connect();
$dbconn = $db->dbconn;

// Proses daftar temu dokter
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Hitung no_urut berdasarkan jumlah daftar hari ini
    $result = $dbconn->query("SELECT COUNT(*) AS urut FROM temu_dokter WHERE DATE(waktu_daftar) = CURDATE()");
    $row = $result->fetch_assoc();
    $no_urut = $row['urut'] + 1;

    $stmt = $dbconn->prepare("INSERT INTO temu_dokter (idpet, idrole_user, waktu_daftar, no_urut, status) VALUES (?, ?, NOW(), ?, 'A')");
    $stmt->bind_param("iii", $_POST['idpet'], $_POST['idrole_user'], $no_urut);
    if ($stmt->execute()) {
        $msg = "Berhasil daftar temu dokter!";
    } else {
        $msg = "Gagal daftar!";
    }
}

// Ambil data pet untuk dropdown
$pets = [];
$pet_result = $dbconn->query("SELECT idpet, nama FROM pet");
while ($row = $pet_result->fetch_assoc()) $pets[] = $row;

// Ambil data dokter dari user yang role-nya dokter
$dokters = [];
$dokter_result = $dbconn->query("
    SELECT ru.idrole_user, u.nama 
    FROM user u 
    JOIN role_user ru ON u.iduser = ru.iduser 
    JOIN role r ON ru.idrole = r.idrole 
    WHERE r.nama_role = 'Dokter'
");
while ($row = $dokter_result->fetch_assoc()) $dokters[] = $row;

// Ambil data temu dokter (join ke pet dan dokter)
$temu_dokter = [];
$query = "
    SELECT td.no_urut, td.waktu_daftar, td.status, 
           p.nama AS nama_pet, u.nama AS nama_dokter
    FROM temu_dokter td
    JOIN pet p ON td.idpet = p.idpet
    JOIN role_user ru ON td.idrole_user = ru.idrole_user
    JOIN user u ON ru.iduser = u.iduser
    ORDER BY td.waktu_daftar ASC
";
$result = $dbconn->query($query);
while ($row = $result->fetch_assoc()) $temu_dokter[] = $row;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Temu Dokter</title>
    <link rel="stylesheet" type="text/css" href="../../css/dashboard_resepsionis.css">
</head>
<body>
    <div class="header">
        <div class="logo"></div>
        <h2>Temu Dokter</h2>
        <div class="nav">
            <a href="dashboard_resepsionis.php">Dashboard</a>
            <a href="../../auth/logout.php">Logout</a>
        </div>
    </div>
    <div class="content">
        <?php if (!empty($msg)) echo "<div class='msg'>$msg</div>"; ?>
        <form method="post" class="form-box">
            <label>Pilih Pet</label>
            <select name="idpet" required>
                <option value="">-- Pilih Pet --</option>
                <?php foreach ($pets as $pet): ?>
                    <option value="<?= $pet['idpet'] ?>"><?= htmlspecialchars($pet['nama']) ?></option>
                <?php endforeach; ?>
            </select>
            <label>Pilih Dokter</label>
            <select name="idrole_user" required>
                <option value="">-- Pilih Dokter --</option>
                <?php foreach ($dokters as $dokter): ?>
                    <option value="<?= $dokter['idrole_user'] ?>"><?= htmlspecialchars($dokter['nama']) ?></option>
                <?php endforeach; ?>
            </select>
            <div class="form-actions">
                <a class="tambah-btn" href="dashboard_resepsionis.php">Kembali</a>
                <button type="submit" class="tambah-btn">Daftar Temu Dokter</button>
            </div>
        </form>
        <h3 style="margin-top:40px;">Daftar Temu Dokter</h3>
        <table border="1" cellpadding="8" cellspacing="0" style="width:100%;margin-top:10px;">
            <tr style="background:#e3f2fd;">
                <th>No Urut</th>
                <th>Waktu Daftar</th>
                <th>Nama Pet</th>
                <th>Nama Dokter</th>
                <th>Status</th>
            </tr>
            <?php if (count($temu_dokter) > 0): ?>
                <?php foreach ($temu_dokter as $row): ?>
                <tr>
                    <td style="text-align:center;"><?= $row['no_urut'] ?></td>
                    <td><?= $row['waktu_daftar'] ?></td>
                    <td><?= htmlspecialchars($row['nama_pet']) ?></td>
                    <td><?= htmlspecialchars($row['nama_dokter']) ?></td>
                    <td><?= $row['status'] == 'A' ? 'Aktif' : 'Selesai' ?></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="5" style="text-align:center;">Belum ada data</td></tr>
            <?php endif; ?>
        </table>
    </div>
</body>
</html>
<?php $dbconn->close(); ?>