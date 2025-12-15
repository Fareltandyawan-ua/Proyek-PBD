<?php
require_once '../../classes/Auth.php';
require_once '../../classes/Database.php';

$auth = new Auth();
$auth->checkRole([1]); // Hanya admin

$db = new Database();

// Ambil data kartu stok
$stokList = $db->fetchAll("
    SELECT ks.idkartu_stok, b.nama AS nama_barang, 
           ks.jenis_transaksi, ks.masuk, ks.keluar, ks.stock, ks.created_at
    FROM kartu_stok ks
    JOIN barang b ON ks.idbarang = b.idbarang
    ORDER BY ks.created_at DESC
");

$success = $_GET['success'] ?? '';
$error = $_GET['error'] ?? '';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Stok - Dashboard Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.15);
        }
        .sidebar {
            background: #fff;
            width: 250px;
            position: fixed;
            top: 56px;
            bottom: 0;
            left: 0;
            box-shadow: 2px 0 8px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
            padding-top: 15px;
        }
        .sidebar a {
            color: #495057;
            display: block;
            padding: 10px 20px;
            border-radius: 8px;
            margin: 5px 15px;
            text-decoration: none;
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
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }
        .header-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 12px;
            padding: 15px 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 25px;
        }
        .table thead {
            background: #667eea;
            color: white;
        }
        .badge {
            font-size: 0.8rem;
        }
        @media (max-width: 768px) {
            .main-content { margin-left: 0; }
            .sidebar { display: none; }
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-dark fixed-top">
        <div class="container-fluid">
            <span class="navbar-brand fw-bold"><i class="fas fa-clipboard-list me-2"></i>Kartu Stok</span>
            <a href="../../dashboard/admin/index.php" class="btn btn-light btn-sm">
                <i class="fas fa-home me-1"></i> Dashboard
            </a>
        </div>
    </nav>

    <!-- Sidebar -->
    <div class="sidebar">
        <a href="../dashboard/admin/index.php"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a>
        <a href="../barang/index.php"><i class="fas fa-box me-2"></i>Data Barang</a>
        <a href="../satuan/index.php"><i class="fas fa-weight me-2"></i>Data Satuan</a>
        <a href="../vendor/index.php"><i class="fas fa-truck me-2"></i>Data Vendor</a>
        <a href="../margin/index.php"><i class="fas fa-chart-line me-2"></i>Margin Penjualan</a>
        <a href="#" class="active"><i class="fas fa-chart-line me-2"></i>Kartu Stok</a>
        
        <style>
            a[data-bs-toggle="collapse"] i.fa-chevron-right {
                transition: transform 0.2s ease-in-out;
            }
            a[data-bs-toggle="collapse"][aria-expanded="true"] i.fa-chevron-right {
                transform: rotate(90deg);
            }
        </style>

        <li class="nav-item">
            <a class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse"
                href="#collapseTransaksi" role="button" aria-expanded="true" aria-controls="collapseTransaksi">
                <span><i class="fas fa-exchange-alt me-2"></i>Transaksi</span>
                <i class="fas fa-chevron-right small"></i>
            </a>
            <div class="collapse show ps-3" id="collapseTransaksi">
                <ul class="nav flex-column ms-2 mt-1">
                    <li class="nav-item">
                        <a class="nav-link py-1" href="../transaksi/pengadaan/index.php"><i class="fas fa-boxes me-2"></i>Pengadaan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link py-1" href="../transaksi/penerimaan/index.php"><i class="fas fa-inbox me-2"></i>Penerimaan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link py-1" href="../transaksi/penjualan/index.php"><i class="fas fa-shopping-cart me-2"></i>Penjualan</a>
                    </li>
                </ul>
            </div>
        </li>

        <a href="../../auth/logout.php" class="text-danger">
            <i class="fas fa-sign-out-alt me-2"></i>Logout
        </a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="header-card d-flex justify-content-between align-items-center">
            <h4 class="mb-0"><i class="fas fa-cubes me-2"></i>Daftar Kartu Stok</h4>
        </div>

        <!-- Alert -->
        <?php if ($success): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle me-2"></i>Data berhasil disimpan!
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php elseif ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-triangle me-2"></i><?= htmlspecialchars($error) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Table -->
        <div class="card p-3">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Barang</th>
                            <th>Jenis Transaksi</th>
                            <th>Masuk</th>
                            <th>Keluar</th>
                            <th>Stok Akhir</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($stokList)): ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                    Belum ada data stok
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($stokList as $stok): ?>
                                <tr>
                                    <td><?= $stok['idkartu_stok'] ?></td>
                                    <td><strong><?= htmlspecialchars($stok['nama_barang']) ?></strong></td>
                                    <td>
                                        <?php if ($stok['jenis_transaksi'] == 'M'): ?>
                                            <span class="badge bg-success"><i class="fas fa-arrow-down me-1"></i>Masuk</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger"><i class="fas fa-arrow-up me-1"></i>Keluar</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= $stok['masuk'] ?></td>
                                    <td><?= $stok['keluar'] ?></td>
                                    <td><strong><?= $stok['stock'] ?></strong></td>
                                    <td><?= $stok['created_at'] ?></td>
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
