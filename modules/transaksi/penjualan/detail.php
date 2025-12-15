<?php

require_once '../../../classes/Auth.php';
require_once '../../../classes/Database.php';

$auth = new Auth();
$auth->checkRole([1]);

$db = new Database();

// Cek ID Penjualan
if (!isset($_GET['idpenjualan'])) {
    die("ID Penjualan tidak ditemukan!");
}

$idpenjualan = (int) $_GET['idpenjualan'];

// Fetch sale details from the database
$sale = null;
if ($idpenjualan > 0) {
    $query = "SELECT p.idpenjualan, p.created_at, p.subtotal_nilai, p.ppn, p.total_nilai, u.username 
              FROM penjualan p 
              JOIN user u ON p.iduser = u.iduser 
              WHERE p.idpenjualan = ?";
    $sale = $db->fetch($query, [$idpenjualan]);
}

// Fetch sale details items
$sale_items = [];
if ($idpenjualan > 0) {
    $query_items = "SELECT dp.iddetail_penjualan, dp.jumlah, dp.harga_satuan, dp.subtotal, b.nama 
                    FROM detail_penjualan dp 
                    JOIN barang b ON dp.idbarang = b.idbarang 
                    WHERE dp.idpenjualan = ?";
    $sale_items = $db->fetchAll($query_items, [$idpenjualan]);
}



?><!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Penjualan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #eef2f7; }
        .card-custom { background: white; border-radius: 12px; padding: 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .summary-box { font-size: 1.1rem; }
        .summary-value { font-weight: bold; font-size: 1.3rem; color: #4a4ae4; }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark px-3" style="background: linear-gradient(135deg,#667eea,#764ba2)">
        <span class="navbar-brand fw-bold"><i class="fas fa-shopping-cart me-2"></i>Detail Penjualan #<?= $sale ? $sale['idpenjualan'] : '' ?></span>
        <a href="index.php" class="btn btn-light btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
    </nav>

    <div class="container mt-4">
        <?php if ($sale): ?>
        <div class="card-custom mb-4">
            <h5 class="fw-bold mb-3"><i class="fas fa-info-circle me-1"></i>Ringkasan Penjualan</h5>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-2">ID Penjualan: <strong><?= $sale['idpenjualan'] ?></strong></div>
                    <div class="mb-2">Tanggal: <?= $sale['created_at'] ?></div>
                    <div class="mb-2">Subtotal: <span class="summary-value">Rp <?= number_format($sale['subtotal_nilai'], 0, ',', '.') ?></span></div>
                    <div class="mb-2">PPN: <?= number_format($sale['ppn'], 0, ',', '.') ?>%</div>
                    <div class="mb-2">Total: <span class="summary-value text-success">Rp <?= number_format($sale['total_nilai'], 0, ',', '.') ?></span></div>
                    <div class="mb-2">Dibuat oleh: <?= htmlspecialchars($sale['username']) ?></div>
                </div>
            </div>
        </div>

        <div class="card-custom mb-4">
            <h5 class="fw-bold mb-3"><i class="fas fa-table me-2"></i>Detail Item Penjualan</h5>
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Barang</th>
                        <th>Jumlah</th>
                        <th>Harga Satuan</th>
                        <th>Subtotal</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($sale_items)): ?>
                        <tr><td colspan="6" class="text-center text-muted">Belum ada detail penjualan.</td></tr>
                    <?php else: ?>
                    <?php foreach ($sale_items as $i => $item): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><?= htmlspecialchars($item['nama']) ?></td>
                            <td><?= $item['jumlah'] ?></td>
                            <td>Rp <?= number_format($item['harga_satuan'], 0, ',', '.') ?></td>
                            <td>Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></td>
                            <td class="text-center">
                                <a href="edit_detail.php?iddetail=<?= $item['iddetail_penjualan'] ?? '' ?>&idpenjualan=<?= $idpenjualan ?>" class="btn btn-warning btn-sm me-1" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="hapus_detail.php?iddetail=<?= $item['iddetail_penjualan'] ?? '' ?>&idpenjualan=<?= $idpenjualan ?>" class="btn btn-danger btn-sm" title="Hapus" onclick="return confirm('Yakin ingin menghapus detail penjualan barang <?= htmlspecialchars($item['nama']) ?>?');">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
            <div class="alert alert-warning mt-4">Detail penjualan tidak ditemukan.</div>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>