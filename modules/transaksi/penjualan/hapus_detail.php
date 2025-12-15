<?php
require_once '../../../classes/Auth.php';
require_once '../../../classes/Database.php';

$auth = new Auth();
$auth->checkRole([1]);
$db = new Database();

$iddetail = $_GET['iddetail'] ?? $_POST['iddetail'] ?? null;
$idpenjualan = $_GET['idpenjualan'] ?? $_POST['idpenjualan'] ?? null;

if (!$iddetail || !$idpenjualan) {
    header('Location: detail.php?idpenjualan=' . urlencode($idpenjualan));
    exit;
}

// Ambil data detail
$detail = $db->fetch("SELECT * FROM detail_penjualan WHERE iddetail_penjualan = ?", [$iddetail]);
if (!$detail) {
    header('Location: detail.php?idpenjualan=' . urlencode($idpenjualan));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db->execute("DELETE FROM detail_penjualan WHERE iddetail_penjualan = ?", [$iddetail]);
    header('Location: detail.php?idpenjualan=' . urlencode($idpenjualan) . '&success=Detail berhasil dihapus');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Hapus Detail Penjualan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <div class="card p-4">
        <h4 class="mb-3 text-danger">Konfirmasi Hapus Detail Penjualan</h4>
        <p>Anda yakin ingin menghapus detail penjualan barang <strong><?= htmlspecialchars($detail['idbarang']) ?></strong>?</p>
        <form method="post">
            <input type="hidden" name="iddetail" value="<?= $iddetail ?>">
            <input type="hidden" name="idpenjualan" value="<?= $idpenjualan ?>">
            <button type="submit" class="btn btn-danger">Hapus</button>
            <a href="detail.php?idpenjualan=<?= $idpenjualan ?>" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
</body>
</html>
