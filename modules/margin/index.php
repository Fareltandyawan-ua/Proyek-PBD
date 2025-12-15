<?php
require_once '../../classes/Auth.php';
require_once '../../classes/Database.php';

$auth = new Auth();
$auth->checkRole([1]); // hanya admin

$db = new Database();

// Filter status
$statusFilter = $_GET['status'] ?? '1'; // default: aktif

if ($statusFilter === '1') {
    $marginList = $db->fetchAll("SELECT * FROM v_margin_penjualan_aktif WHERE status = 1 ORDER BY idmargin_penjualan ASC");
} elseif ($statusFilter === '0') {
    $marginList = $db->fetchAll("SELECT * FROM v_margin_penjualan WHERE status = 0 ORDER BY idmargin_penjualan ASC");
} else {
    $marginList = $db->fetchAll("SELECT * FROM v_margin_penjualan ORDER BY idmargin_penjualan ASC");
}

$success = $_GET['success'] ?? '';
$error = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Margin Penjualan - Dashboard Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Navbar */
        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.15);
        }

        /* Sidebar */
        .sidebar {
            background: #fff;
            width: 250px;
            position: fixed;
            top: 56px;
            bottom: 0;
            left: 0;
            box-shadow: 2px 0 8px rgba(0, 0, 0, 0.1);
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

        /* Main content */
        .main-content {
            margin-left: 250px;
            padding: 30px;
            margin-top: 70px;
        }

        .header-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 12px;
            padding: 15px 20px;
            margin-bottom: 25px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }

        .alert-custom {
            position: fixed;
            top: 80px;
            right: 20px;
            min-width: 300px;
            z-index: 9999;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
            }

            .sidebar {
                display: none;
            }
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-dark fixed-top">
        <div class="container-fluid">
            <span class="navbar-brand fw-bold"><i class="fas fa-percent me-2"></i>Data Margin Penjualan</span>
            <a href="../dashboard/admin/index.php" class="btn btn-light btn-sm">
                <i class="fas fa-home me-1"></i>Dashboard
            </a>
        </div>
    </nav>

    <!-- Sidebar -->
    <div class="sidebar">
        <a href="../dashboard/admin/index.php"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a>
        <a href="../barang/index.php"><i class="fas fa-box me-2"></i>Data Barang</a>
        <a href="../satuan/index.php"><i class="fas fa-weight me-2"></i>Data Satuan</a>
        <a href="../vendor/index.php"><i class="fas fa-truck me-2"></i>Data Vendor</a>
        <a href="#" class="active"><i class="fas fa-percent me-2"></i>Margin Penjualan</a>
        <a href="../kartu_stok/index.php"><i class="fas fa-chart-line me-2"></i>Kartu Stok</a>
        <style>
            a[data-bs-toggle="collapse"] i.fa-chevron-right {
                transition: transform 0.2s ease-in-out;
            }

            a[data-bs-toggle="collapse"][aria-expanded="true"] i.fa-chevron-right {
                transform: rotate(90deg);
            }
        </style>
        <!-- Menu Transaksi -->
        <li class="nav-item">
            <a class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse"
                href="#collapseTransaksi" role="button" aria-expanded="false" aria-controls="collapseTransaksi">
                <span><i class="fas fa-exchange-alt me-2"></i>Transaksi</span>
                <i class="fas fa-chevron-right small"></i>
            </a>
            <div class="collapse ps-3" id="collapseTransaksi">
                <ul class="nav flex-column ms-2 mt-1">
                    <li class="nav-item">
                        <a class="nav-link py-1" href="../transaksi/pengadaan/index.php">
                            <i class="fas fa-boxes me-2"></i>Pengadaan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link py-1" href="../transaksi/penerimaan/index.php">
                            <i class="fas fa-inbox me-2"></i>Penerimaan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link py-1" href="../transaksi/penjualan/index.php">
                            <i class="fas fa-shopping-cart me-2"></i>Penjualan
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        <a href="../auth/logout.php" class="text-danger"><i class="fas fa-sign-out-alt me-2"></i>Logout</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="header-card d-flex justify-content-between align-items-center">
            <h4 class="mb-0"><i class="fas fa-percent me-2"></i>Daftar Margin Penjualan</h4>
            <a href="tambah.php" class="btn btn-success btn-sm">
                <i class="fas fa-plus me-1"></i>Tambah Margin
            </a>
        </div>
        <!-- Filter Form -->
        <form method="GET" class="mb-3 d-flex align-items-center gap-2">
            <label for="status" class="fw-semibold text-dark mb-0">Tampilkan:</label>
            <select name="status" id="status" class="form-select form-select-sm" onchange="this.form.submit()"
                style="min-width: 140px;">
                <option value="" <?= (isset($_GET['status']) && $_GET['status'] === '') ? 'selected' : '' ?>>Semua Margin
                </option>
                <option value="1" <?= (!isset($_GET['status']) || $_GET['status'] === '1') ? 'selected' : '' ?>>Aktif
                </option>
                <option value="0" <?= (isset($_GET['status']) && $_GET['status'] === '0') ? 'selected' : '' ?>>Tidak Aktif
                </option>
            </select>
        </form>

        <!-- Alert -->
        <?php if ($success): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                <?= ($success == 'added') ? "Margin berhasil ditambahkan!" :
                    (($success == 'updated') ? "Margin berhasil diperbarui!" : "Margin berhasil dihapus!") ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php elseif ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i><?= htmlspecialchars($error) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Data Table -->
        <div class="card p-3">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Dibuat</th>
                            <th>Persentase</th>
                            <th>Status</th>
                            <th>User</th>
                            <th>Diperbarui</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($marginList)): ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="fas fa-inbox fa-2x mb-2 d-block"></i>Tidak ada data margin penjualan.
                                </td>
                            </tr>
                        <?php else:
                            foreach ($marginList as $i => $m): ?>
                                <tr>
                                    <td><?= $i + 1 ?></td>
                                    <td><?= htmlspecialchars($m['created_at']) ?></td>
                                    <td><?= htmlspecialchars($m['persen']) ?>%</td>
                                    <td>
                                        <?= ($m['status'] == '1')
                                            ? '<span class="badge bg-success"><i class="fas fa-check"></i> Aktif</span>'
                                            : '<span class="badge bg-secondary"><i class="fas fa-ban"></i> Tidak Aktif</span>' ?>
                                    </td>
                                    <td>
                                        <?php
                                        $user = $db->fetch("SELECT username FROM user WHERE iduser = ?", [$m['iduser']]);
                                        echo htmlspecialchars($user ? $user['username'] : '-');
                                        ?>
                                    </td>
                                    <td><?= htmlspecialchars($m['updated_at']) ?></td>
                                    <td class="text-center">
                                        <a href="edit.php?id=<?= $m['idmargin_penjualan'] ?>" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="hapus.php?id=<?= $m['idmargin_penjualan'] ?>" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Yakin ingin menghapus data ini?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto close alert
        setTimeout(() => {
            const alert = document.querySelector('.alert');
            if (alert) new bootstrap.Alert(alert).close();
        }, 3000);
    </script>
</body>

</html>