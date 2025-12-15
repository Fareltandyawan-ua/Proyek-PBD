<?php
require_once '../../../classes/Auth.php';
require_once '../../../classes/Penerimaan.php';

$auth = new Auth();
$auth->checkRole([1]);

$penerimaan = new Penerimaan();

// Cek ID
$id = $_GET['idpenerimaan'] ?? null;
if (!$id) {
    header('Location: index.php?error=ID tidak ditemukan');
    exit;
}

// Proses hapus jika ada konfirmasi POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Hapus detail penerimaan dulu
    $penerimaan->deleteDetailItems($id);
    // Hapus header penerimaan
    $penerimaan->deletePenerimaan($id);

    header('Location: index.php?success=deleted');
    exit;
}

// Ambil header penerimaan untuk konfirmasi
$header = $penerimaan->getPenerimaanById($id);
if (!$header) {
    die("Data penerimaan tidak ditemukan!");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Hapus Penerimaan #<?= $id ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="card p-4">
            <h4 class="mb-3 text-danger"><i class="fas fa-trash me-2"></i>Konfirmasi Hapus Penerimaan</h4>
            <p>Anda yakin ingin menghapus penerimaan <strong>#<?= $id ?></strong> (Vendor: <strong><?= htmlspecialchars($header['nama_vendor'] ?? '-') ?></strong>)?</p>
            <form method="POST">
                <button type="submit" class="btn btn-danger"><i class="fas fa-trash me-1"></i>Hapus</button>
                <a href="index.php" class="btn btn-secondary ms-2">Batal</a>
            </form>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
</body>
</html>