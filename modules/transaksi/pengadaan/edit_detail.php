<?php
require_once '../../../classes/Auth.php';
require_once '../../../classes/Database.php';

$auth = new Auth();
$auth->checkRole([1]);
$db = new Database();

if (!isset($_GET['id']) || !isset($_GET['idpengadaan'])) {
    die("Parameter tidak lengkap!");
}

$iddetail = $_GET['id'];
$idpengadaan = $_GET['idpengadaan'];

// Ambil data detail
$detail = $db->fetch("
    SELECT * FROM detail_pengadaan WHERE iddetail_pengadaan = ?
", [$iddetail]);

if (!$detail) {
    die("Data tidak ditemukan!");
}

// Ambil daftar barang
$barangList = $db->fetchAll("SELECT idbarang, nama FROM barang WHERE status=1 ORDER BY nama ASC");

// Jika submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idbarang = $_POST['idbarang'];
    $jumlah   = $_POST['jumlah'];
    $harga    = $_POST['harga'];

    $db->execute("
        UPDATE detail_pengadaan
        SET idbarang=?, jumlah=?, harga_satuan=?
        WHERE iddetail_pengadaan=?
    ", [$idbarang, $jumlah, $harga, $iddetail]);

    header("Location: detail.php?idpengadaan=$idpengadaan");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Detail Pengadaan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">

    <h4 class="mb-4">Edit Detail</h4>

    <form method="POST" class="row g-3">

        <div class="col-md-4">
            <label>Barang</label>
            <select name="idbarang" class="form-select" required>
                <?php foreach ($barangList as $b): ?>
                    <option value="<?= $b['idbarang'] ?>" 
                        <?= $b['idbarang'] == $detail['idbarang'] ? 'selected' : '' ?>>
                        <?= $b['nama'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-4">
            <label>Jumlah</label>
            <input type="number" name="jumlah" value="<?= $detail['jumlah'] ?>" class="form-control" required>
        </div>

        <div class="col-md-4">
            <label>Harga Satuan</label>
            <input type="number" name="harga" value="<?= $detail['harga_satuan'] ?>" class="form-control" required>
        </div>

        <div class="col-12">
            <button class="btn btn-primary">Simpan Perubahan</button>
            <a href="detail.php?idpengadaan=<?= $idpengadaan ?>" class="btn btn-secondary">Kembali</a>
        </div>

    </form>

</div>

</body>
</html>
