<?php
require_once '../../../classes/Auth.php';
require_once '../../../classes/Database.php';

$auth = new Auth();
$auth->checkRole([1]); // Role admin

$db = new Database();

$query = "
    SELECT 
        p.idpenjualan,
        p.created_at,
        p.subtotal_nilai,
        p.ppn,
        p.total_nilai,
        u.username AS kasir,
        m.persen AS margin_persen
    FROM penjualan p
    LEFT JOIN user u ON p.iduser = u.iduser
    LEFT JOIN margin_penjualan m ON p.idmargin_penjualan = m.idmargin_penjualan
    ORDER BY p.created_at DESC
";

$data_penjualan = $db->fetchAll($query);

$isTransaksiActive = strpos($_SERVER['PHP_SELF'], '/transaksi/') !== false;
$success = $_GET['success'] ?? '';
$error = $_GET['error'] ?? '';
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Penjualan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .sidebar {
            background: #fff;
            width: 250px;
            position: fixed;
            top: 56px;
            bottom: 0;
            left: 0;
            box-shadow: 2px 0 8px rgba(0, 0, 0, 0.1);
            padding-top: 20px;
        }

        .sidebar a {
            color: #495057;
            display: block;
            padding: 10px 20px;
            border-radius: 8px;
            margin: 5px 15px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .main-content {
            margin-left: 250px;
            padding: 30px;
            margin-top: 70px;
        }

        .header-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 12px;
            padding: 20px 25px;
            margin-bottom: 25px;
        }

        table th {
            background-color: #f8f9fa;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
        }

        a[data-bs-toggle="collapse"] i.fa-chevron-right {
            transition: transform 0.2s ease-in-out;
        }

        a[data-bs-toggle="collapse"][aria-expanded="true"] i.fa-chevron-right {
            transform: rotate(90deg);
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-dark fixed-top">
        <div class="container-fluid">
            <span class="navbar-brand fw-bold"><i class="fas fa-shopping-cart me-2"></i>Data Penjualan</span>
            <a href="../../dashboard/admin/index.php" class="btn btn-light btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </nav>

    <!-- Sidebar -->
    <div class="sidebar">
        <a href="../../dashboard/admin/index.php"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a>
        <a href="../../barang/index.php"><i class="fas fa-box me-2"></i>Data Barang</a>
        <a href="../../satuan/index.php"><i class="fas fa-weight me-2"></i>Data Satuan</a>
        <a href="../../vendor/index.php"><i class="fas fa-truck me-2"></i>Data Vendor</a>
        <a href="../../margin/index.php"><i class="fas fa-chart-line me-2"></i>Margin Penjualan</a>
        <a href="../../kartu_stok/index.php"><i class="fas fa-chart-line me-2"></i>Kartu Stok</a>
        <li class="nav-item">
            <a class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse"
                href="#collapseTransaksi" role="button"
                aria-expanded="<?= $isTransaksiActive ? 'true' : 'false' ?>"
                aria-controls="collapseTransaksi">
                <span><i class="fas fa-exchange-alt me-2"></i>Transaksi</span>
                <i class="fas fa-chevron-right small"></i>
            </a>
            <div class="collapse<?= $isTransaksiActive ? ' show' : '' ?> ps-3" id="collapseTransaksi">
                <ul class="nav flex-column ms-2 mt-1">
                    <li class="nav-item">
                        <a class="nav-link py-1" href="../pengadaan/index.php">
                            <i class="fas fa-boxes me-2"></i>Pengadaan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link py-1" href="../penerimaan/index.php">
                            <i class="fas fa-inbox me-2"></i>Penerimaan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active py-1" href="#">
                            <i class="fas fa-shopping-cart me-2"></i>Penjualan
                        </a>
                    </li>
                </ul>
            </div>
        </li>
        <a href="../../auth/logout.php" class="text-danger"><i class="fas fa-sign-out-alt me-2"></i>Logout</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="header-card">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                <div>
                    <h4 class="mb-1">
                        <i class="fas fa-shopping-cart me-2"></i>Data Penjualan
                    </h4>
                    <p class="mb-0 text-white-75">Daftar seluruh transaksi penjualan barang</p>
                </div>
                <a href="tambah.php" class="btn btn-light fw-semibold">
                    <i class="fas fa-plus me-1"></i> Tambah Penjualan
                </a>
            </div>
        </div>

        <?php if ($success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <div class="card shadow-sm">
            <div class="card-body">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Kasir</th>
                            <th>Subtotal</th>
                            <th>PPN</th>
                            <th>Total</th>
                            <th>Margin</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($data_penjualan)): ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                    Belum ada data penjualan.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($data_penjualan as $index => $row): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><?= date('d F Y', strtotime($row['created_at'])) ?></td>
                                    <td><?= htmlspecialchars($row['kasir']) ?></td>
                                    <td>Rp <?= number_format($row['subtotal_nilai'], 0, ',', '.') ?></td>
                                    <td><?= number_format($row['ppn'], 0, ',', '.') ?>%</td>
                                    <td><strong>Rp <?= number_format($row['total_nilai'], 0, ',', '.') ?></strong></td>
                                    <td><?= $row['margin_persen'] !== null ? htmlspecialchars($row['margin_persen'] . '%') : '-' ?></td>
                                    <td class="text-center">
                                        <a href="detail.php?idpenjualan=<?= $row['idpenjualan'] ?>" class="btn btn-primary btn-sm mb-1">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="edit.php?idpenjualan=<?= $row['idpenjualan'] ?>" class="btn btn-warning btn-sm mb-1">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="hapus.php?idpenjualan=<?= $row['idpenjualan'] ?>" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Yakin ingin menghapus?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>