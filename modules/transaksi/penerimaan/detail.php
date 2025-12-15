<?php
require_once '../../../classes/Auth.php';
require_once '../../../classes/Penerimaan.php';

$auth = new Auth();
$auth->checkRole([1]);

$penerimaan = new Penerimaan();

// Cek ID
$idpenerimaan = $_GET['idpenerimaan'] ?? null;
if (!$idpenerimaan) {
    header('Location: index.php?error=ID tidak ditemukan');
    exit;
}

// Ambil header penerimaan
$header = $penerimaan->getPenerimaanById($idpenerimaan);

if (!$header) {
    die("Data penerimaan tidak ditemukan!");
}

// Ambil detail penerimaan
$detailList = $penerimaan->getDetailItems($idpenerimaan);

// Hitung total
$grandTotal = 0;
foreach ($detailList as $d) {
    $grandTotal += $d['sub_total_terima'];
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Penerimaan #<?= $idpenerimaan ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .card-custom {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .info-table th {
            width: 200px;
            background-color: #f8f9fa;
            font-weight: 600;
        }

        .badge-status {
            padding: 8px 15px;
            font-size: 14px;
        }

        .total-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }

        .btn-print {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white;
            border: none;
        }

        .btn-print:hover {
            opacity: 0.9;
            color: white;
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-dark">
        <div class="container-fluid">
            <span class="navbar-brand fw-bold">
                <i class="fas fa-file-invoice me-2"></i>Detail Penerimaan #<?= $idpenerimaan ?>
            </span>
            <div>
                <button onclick="window.print()" class="btn btn-print btn-sm me-2">
                    <i class="fas fa-print me-1"></i> Cetak
                </button>
                <a href="index.php" class="btn btn-light btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">

        <!-- Header Informasi -->
        <div class="card-custom">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">
                    <i class="fas fa-info-circle text-primary me-2"></i>Informasi Penerimaan
                </h5>
                <?php if ($header['status'] === 'S'): ?>
                    <span class="badge bg-success badge-status">
                        <i class="fas fa-check-circle me-1"></i>Selesai
                    </span>
                <?php else: ?>
                    <span class="badge bg-warning text-dark badge-status">
                        <i class="fas fa-clock me-1"></i>Proses
                    </span>
                <?php endif; ?>
            </div>

            <table class="table table-bordered info-table mb-0">
                <tr>
                    <th><i class="fas fa-hashtag me-2"></i>ID Penerimaan</th>
                    <td><?= $header['idpenerimaan'] ?></td>
                </tr>
                <tr>
                    <th><i class="fas fa-calendar me-2"></i>Tanggal Penerimaan</th>
                    <td><?= date('d F Y, H:i', strtotime($header['created_at'])) ?> WIB</td>
                </tr>
                <tr>
                    <th><i class="fas fa-file-alt me-2"></i>ID Pengadaan</th>
                    <td>
                        <span class="badge bg-primary">PO #<?= $header['idpengadaan'] ?></span>
                        <small class="text-muted ms-2">
                            (<?= date('d M Y', strtotime($header['tgl_pengadaan'])) ?>)
                        </small>
                    </td>
                </tr>
                <tr>
                    <th><i class="fas fa-truck me-2"></i>Vendor</th>
                    <td><?= htmlspecialchars($header['nama_vendor'] ?? '-') ?></td>
                </tr>
                <tr>
                    <th><i class="fas fa-user me-2"></i>Petugas Penerimaan</th>
                    <td><?= htmlspecialchars($header['penerima'] ?? '-') ?></td>
                </tr>
            </table>
        </div>

        <!-- Detail Barang -->
        <div class="card-custom">
            <h5 class="fw-bold mb-3">
                <i class="fas fa-boxes text-primary me-2"></i>Detail Barang Diterima
            </h5>

            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">
                    <thead class="table-light">
                        <tr class="text-center">
                            <th width="5%">No</th>
                            <th width="30%">Nama Barang</th>
                            <th width="10%">Satuan</th>
                            <th width="15%">Jumlah Terima</th>
                            <th width="15%">Harga Satuan</th>
                            <th width="15%">Subtotal</th>
                            <th width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        foreach ($detailList as $d):
                            ?>
                            <tr>
                                <td class="text-center"><?= $no++ ?></td>
                                <td><?= htmlspecialchars($d['nama']) ?></td>
                                <td class="text-center"><?= htmlspecialchars($d['nama_satuan'] ?? '-') ?></td>
                                <td class="text-center">
                                    <span
                                        class="badge bg-success"><?= number_format($d['jumlah_terima'], 0, ',', '.') ?></span>
                                </td>
                                <td class="text-end">Rp <?= number_format($d['harga_satuan_terima'], 0, ',', '.') ?></td>
                                <td class="text-end fw-semibold">
                                    Rp <?= number_format($d['sub_total_terima'] ?? 0, 0, ',', '.') ?>
                                </td>
                                <td class="text-center">
                                    <a href="edit_detail.php?iddetail=<?= $d['iddetail_penerimaan'] ?>&idpenerimaan=<?= $idpenerimaan ?>"
                                        class="btn btn-warning btn-sm" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="hapus_detail.php?iddetail=<?= $d['iddetail_penerimaan'] ?>&idpenerimaan=<?= $idpenerimaan ?>"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Yakin ingin menghapus detail ini?')" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr class="table-secondary fw-bold">
                            <td colspan="6" class="text-end">TOTAL:</td>
                            <td class="text-end">
                                Rp <?= number_format($grandTotal, 0, ',', '.') ?>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Total Summary Box -->
        <div class="total-box">
            <h6 class="mb-2 text-white-50">Total Nilai Penerimaan</h6>
            <h3 class="fw-bold mb-0">Rp <?= number_format($grandTotal, 0, ',', '.') ?></h3>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Print Styling -->
    <style media="print">
        .navbar,
        .btn {
            display: none !important;
        }

        body {
            background: white !important;
        }

        .card-custom {
            box-shadow: none !important;
            border: 1px solid #ddd !important;
        }
    </style>

</body>

</html>