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
    $jumlah = $_POST['jumlah'];
    $harga_satuan = $_POST['harga_satuan'];
    $subtotal = $jumlah * $harga_satuan;
    $db->execute(
        "UPDATE detail_penjualan SET jumlah=?, harga_satuan=?, subtotal=? WHERE iddetail_penjualan=?",
        [$jumlah, $harga_satuan, $subtotal, $iddetail]
    );
    header('Location: detail.php?idpenjualan=' . urlencode($idpenjualan) . '&success=Detail berhasil diupdate');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Detail Penjualan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <div class="card p-4">
        <h4 class="mb-3">Edit Detail Penjualan</h4>
        <form method="post">
            <input type="hidden" name="iddetail" value="<?= $iddetail ?>">
            <input type="hidden" name="idpenjualan" value="<?= $idpenjualan ?>">
            <div class="mb-3">
                <label>Jumlah</label>
                <input type="number" name="jumlah" class="form-control" min="1" value="<?= htmlspecialchars($detail['jumlah']) ?>" required>
            </div>
            <div class="mb-3">
                <label>Harga Satuan</label>
                <input type="number" name="harga_satuan" class="form-control" min="0" value="<?= htmlspecialchars($detail['harga_satuan']) ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="detail.php?idpenjualan=<?= $idpenjualan ?>" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
</body>
</html>
