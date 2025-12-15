<?php
require_once '../../../classes/Auth.php';
require_once '../../../classes/Penerimaan.php';

$auth = new Auth();
$auth->checkRole([1]);

$penerimaan = new Penerimaan();

$iddetail = $_GET['iddetail'] ?? null;
$idpenerimaan = $_GET['idpenerimaan'] ?? null;
if (!$iddetail || !$idpenerimaan) {
    header('Location: detail.php?idpenerimaan=' . $idpenerimaan . '&error=ID tidak ditemukan');
    exit;
}

$detail = $penerimaan->getDetailById($iddetail);
if (!$detail) {
    die("Data detail tidak ditemukan!");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jumlah_terima = (int)$_POST['jumlah_terima'];
    $harga_satuan_terima = (int)$_POST['harga_satuan_terima'];
    $sub_total_terima = $jumlah_terima * $harga_satuan_terima;
    $penerimaan->updateDetailItem($iddetail, $jumlah_terima, $harga_satuan_terima, $sub_total_terima);
    header("Location: detail.php?idpenerimaan=" . $idpenerimaan . "&success=Detail berhasil diupdate");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Detail Penerimaan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <div class="card card-custom">
        <div class="card-body">
            <h4 class="mb-3">Edit Detail Barang</h4>
            <form method="POST">
                <div class="mb-3">
                    <label>Nama Barang</label>
                    <input type="text" class="form-control" value="<?= htmlspecialchars($detail['nama']) ?>" readonly>
                </div>
                <div class="mb-3">
                    <label>Jumlah Terima</label>
                    <input type="number" name="jumlah_terima" class="form-control" value="<?= $detail['jumlah_terima'] ?>" min="1" required>
                </div>
                <div class="mb-3">
                    <label>Harga Satuan</label>
                    <input type="number" name="harga_satuan_terima" class="form-control" value="<?= $detail['harga_satuan_terima'] ?>" min="0" readonly>
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="detail.php?idpenerimaan=<?= $idpenerimaan ?>" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
</body>
</html>