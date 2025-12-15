<?php
require_once '../../../classes/Auth.php';
require_once '../../../classes/Penerimaan.php';

$auth = new Auth();
$auth->checkRole([1]);

$penerimaan = new Penerimaan();

$idpenerimaan = $_GET['idpenerimaan'] ?? null;
if (!$idpenerimaan) {
    header('Location: index.php?error=ID tidak ditemukan');
    exit;
}

$data = $penerimaan->getPenerimaanById($idpenerimaan);
if (!$data) {
    die("Data penerimaan tidak ditemukan!");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idpengadaan = $_POST['idpengadaan'];
    $status = $_POST['status'];
    $penerimaan->updateHeader($idpenerimaan, $idpengadaan,  $status);
    header("Location: index.php?success=Data penerimaan berhasil diupdate");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Penerimaan #<?= $idpenerimaan ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <div class="card card-custom">
        <div class="card-body">
            <h4 class="mb-3">Edit Penerimaan #<?= $idpenerimaan ?></h4>
            <form method="POST">
                <div class="mb-3">
                    <label>ID Pengadaan</label>
                    <input type="text" name="idpengadaan" class="form-control" value="<?= $data['idpengadaan'] ?>" readonly>
                </div>
                <div class="mb-3">
                    <label>Nama Vendor</label>
                    <input type="text" class="form-control" value="<?= $data['nama_vendor'] ?>" readonly>
                </div>
                <div class="mb-3">
                    <label>Status</label>
                    <select name="status" class="form-select">
                        <option value="P" <?= $data['status']=='P'?'selected':''; ?>>Proses</option>
                        <option value="S" <?= $data['status']=='S'?'selected':''; ?>>Selesai</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="index.php" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
</body>
</html>