<?php
require_once '../../../classes/Auth.php';
require_once '../../../classes/Database.php';

$auth = new Auth();
$auth->checkRole([1]);

$db = new Database();

$idpenjualan = $_GET['idpenjualan'] ?? null;
if (!$idpenjualan) {
    header('Location: index.php?error=ID tidak ditemukan');
    exit;
}

// Ambil data penjualan
$query = "SELECT * FROM penjualan WHERE idpenjualan = ?";
$penjualan = $db->fetch($query, [$idpenjualan]);
if (!$penjualan) {
    header('Location: index.php?error=Data tidak ditemukan');
    exit;
}

// Proses hapus
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db->execute("DELETE FROM penjualan WHERE idpenjualan = ?", [$idpenjualan]);
    header("Location: index.php?success=Berhasil dihapus");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Hapus Penjualan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <div class="card p-4">
        <h4 class="mb-3 text-danger">Konfirmasi Hapus Penjualan</h4>
        <p>Anda yakin ingin menghapus penjualan dengan total <strong>Rp <?= number_format($penjualan['total_nilai'],0,',','.') ?></strong>?</p>
        <form method="post">
            <button type="submit" class="btn btn-danger">Hapus</button>
            <a href="index.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
</body>
</html>