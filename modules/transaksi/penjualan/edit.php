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

// Ambil margin aktif
$margin = $db->fetch("SELECT * FROM margin_penjualan WHERE status = 1 LIMIT 1");
$idmargin_penjualan = $margin ? $margin['idmargin_penjualan'] : null;
$persen_margin = $margin ? $margin['persen'] : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subtotal_nilai = $_POST['subtotal_nilai'];
    $ppn = $_POST['ppn'];
    $total_nilai = $_POST['total_nilai'];
    $iduser = $_POST['iduser'];
    $idmargin_penjualan = $_POST['idmargin_penjualan'];

    $db->execute(
        "UPDATE penjualan SET subtotal_nilai=?, ppn=?, total_nilai=?, iduser=?, idmargin_penjualan=? WHERE idpenjualan=?",
        [$subtotal_nilai, $ppn, $total_nilai, $iduser, $idmargin_penjualan, $idpenjualan]
    );
    header("Location: index.php?success=Berhasil diupdate");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Penjualan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <div class="card p-4">
        <h4 class="mb-3">Edit Penjualan</h4>
        <form method="post">
            <div class="mb-3">
                <label>Subtotal Nilai</label>
                <input type="number" name="subtotal_nilai" class="form-control" min="0" value="<?= htmlspecialchars($penjualan['subtotal_nilai']) ?>" required>
            </div>
            <div class="mb-3">
                <label>PPN (%)</label>
                <input type="number" name="ppn" class="form-control" min="0" value="<?= htmlspecialchars($penjualan['ppn']) ?>" required>
            </div>
            <div class="mb-3">
                <label>Total Nilai</label>
                <input type="number" name="total_nilai" class="form-control" min="0" value="<?= htmlspecialchars($penjualan['total_nilai']) ?>" required>
            </div>
            <input type="hidden" name="iduser" value="<?= htmlspecialchars($penjualan['iduser']) ?>">
            <input type="hidden" name="idmargin_penjualan" value="<?= $idmargin_penjualan ?>">
            <div class="mb-3">
                <label>Margin Penjualan Aktif</label>
                <input type="text" class="form-control" value="<?= $persen_margin ?> %" readonly>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="index.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
</body>
</html>