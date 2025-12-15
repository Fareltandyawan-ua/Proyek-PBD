<?php
include '../../config/database.php';

if (!isset($_GET['id'])) {
    exit('ID tidak ditemukan');
}
$id = $_GET['id'];

// Ambil data margin_penjualan
$stmt = $pdo->prepare("SELECT * FROM margin_penjualan WHERE idmargin_penjualan = ?");
$stmt->execute([$id]);
$margin = $stmt->fetch();

if (!$margin) {
    exit('Data margin tidak ditemukan!');
}

// Proses hapus jika ada konfirmasi POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("DELETE FROM margin_penjualan WHERE idmargin_penjualan = ?");
    $stmt->execute([$id]);
    header("Location: index.php?success=deleted");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Hapus Margin Penjualan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <div class="card p-4">
        <h4 class="mb-3 text-danger">Konfirmasi Hapus Margin Penjualan</h4>
        <p>Anda yakin ingin menghapus margin <strong><?= htmlspecialchars($margin['persen']) ?>%</strong>?</p>
        <form method="post">
            <button type="submit" class="btn btn-danger">Hapus</button>
            <a href="index.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
</body>
</html>