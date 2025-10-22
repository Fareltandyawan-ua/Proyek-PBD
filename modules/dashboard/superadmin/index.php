<?php
require_once '../../../classes/Auth.php';
require_once '../../../classes/Dashboard.php';

$auth = new Auth();
$auth->checkLogin([2]);

$dashboard = new Dashboard();

// Check login
$auth->checkLogin();

// Get user data
$userData = $auth->getUserData();

// Get statistics
$stats = $dashboard->getStatistics();

// Get recent data
$recentBarang = $dashboard->getRecentBarang();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistem Pengadaan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #667eea;
            --secondary-color: #764ba2;
            --success-color: #06d6a0;
            --warning-color: #f9844a;
        }

        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
        }

        .navbar {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            height: 56px;
        }

        .sidebar {
            background: #fff;
            min-height: calc(100vh - 56px);
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            position: fixed;
            top: 56px;
            left: 0;
            width: 250px;
            z-index: 1000;
            overflow-y: auto;
        }

        .sidebar .nav-link {
            color: #495057;
            padding: 12px 20px;
            border-radius: 8px;
            margin: 2px 10px;
            transition: all 0.3s ease;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
        }

        .main-content {
            margin-left: 250px;
            margin-top: 56px; /* Add margin top for navbar */
            padding: 30px 20px 20px 20px; /* Add extra top padding */
            min-height: calc(100vh - 56px);
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: transform 0.3s ease;
            margin-bottom: 20px;
        }

        .card:hover {
            transform: translateY(-3px);
        }

        .stat-card {
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
        }

        .icon-primary { background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%); }
        .icon-success { background: linear-gradient(135deg, var(--success-color) 0%, #00b894 100%); }
        .icon-warning { background: linear-gradient(135deg, var(--warning-color) 0%, #e84393 100%); }
        .icon-info { background: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%); }

        .welcome-card {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            margin-bottom: 30px !important;
            min-height: 120px; /* Ensure minimum height */
        }

        .welcome-card .card-body {
            padding: 25px;
        }

        .welcome-card h3 {
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 8px;
            line-height: 1.2;
        }

        .welcome-card p {
            font-size: 1rem;
            margin-bottom: 5px;
        }

        .welcome-card small {
            font-size: 0.85rem;
        }

        .user-avatar {
            font-size: 80px;
            opacity: 0.2;
        }

        /* Stats card improvements */
        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: #2c3e50;
        }

        .stat-label {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .sidebar {
                margin-left: -250px;
                transition: margin-left 0.3s ease;
            }
            
            .sidebar.show {
                margin-left: 0;
            }
            
            .main-content {
                margin-left: 0;
                padding: 20px 15px;
            }

            .welcome-card h3 {
                font-size: 1.5rem;
            }

            .user-avatar {
                font-size: 60px;
            }
        }

        @media (max-width: 576px) {
            .main-content {
                padding: 15px 10px;
            }

            .welcome-card .card-body {
                padding: 20px;
            }

            .welcome-card h3 {
                font-size: 1.3rem;
            }
        }

        /* Table improvements */
        .table th {
            background-color: #f8f9fa;
            border-top: none;
            font-weight: 600;
            color: #495057;
        }

        .badge {
            font-size: 0.75rem;
            padding: 0.35em 0.65em;
        }

        /* Card header improvements */
        .card-header {
            background: white !important;
            border-bottom: 1px solid #e9ecef;
            padding: 1rem 1.5rem;
        }

        .card-title {
            margin: 0;
            font-size: 1rem;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container-fluid">
            <button class="btn btn-outline-light d-lg-none me-2" type="button" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
            <a class="navbar-brand fw-bold" href="#">
                <i class="fas fa-warehouse me-2"></i>
                Sistem Pengadaan
            </a>
            <div class="navbar-nav ms-auto">
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle me-2"></i>
                        <?= htmlspecialchars($userData['username']) ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><h6 class="dropdown-header"><?= htmlspecialchars($userData['role_name']) ?></h6></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="../../auth/logout.php">
                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                        </a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div class="p-3">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link active" href="index.php">
                        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../barang/index.php">
                        <i class="fas fa-box me-2"></i>Data Barang
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../satuan/index.php">
                        <i class="fas fa-weight me-2"></i>Data Satuan
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../vendor/index.php">
                        <i class="fas fa-truck me-2"></i>Data Vendor
                    </a>
                </li>
                <!-- <li class="nav-item">
                    <a class="nav-link" href="../user/index.php">
                        <i class="fas fa-users me-2"></i>Data User
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../pengadaan/index.php">
                        <i class="fas fa-shopping-cart me-2"></i>Pengadaan
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../penerimaan/index.php">
                        <i class="fas fa-clipboard-check me-2"></i>Penerimaan
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../penjualan/index.php">
                        <i class="fas fa-cash-register me-2"></i>Penjualan
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../retur/index.php">
                        <i class="fas fa-undo me-2"></i>Retur
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../margin/index.php">
                        <i class="fas fa-percentage me-2"></i>Margin
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../kartu_stok/index.php">
                        <i class="fas fa-clipboard-list me-2"></i>Kartu Stok
                    </a>
                </li> -->
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Welcome Card -->
        <div class="card welcome-card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8 col-sm-7">
                        <h3>Selamat Datang, <?= htmlspecialchars($userData['username']) ?>!</h3>
                        <p class="mb-1 opacity-75">Anda login sebagai <strong><?= htmlspecialchars($userData['role_name']) ?></strong></p>
                        <small class="opacity-75">Dashboard Sistem Pengadaan - <?= date('d F Y') ?></small>
                    </div>
                    <div class="col-md-4 col-sm-5 text-end">
                        <i class="fas fa-user-circle user-avatar"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card stat-card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="stat-label" style="color: var(--primary-color);">Total Barang</div>
                                <div class="stat-number"><?= number_format($stats['total_barang']) ?></div>
                            </div>
                            <div class="col-auto">
                                <div class="stat-icon icon-primary">
                                    <i class="fas fa-box"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card stat-card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="stat-label" style="color: var(--success-color);">Total Satuan</div>
                                <div class="stat-number"><?= number_format($stats['total_satuan']) ?></div>
                            </div>
                            <div class="col-auto">
                                <div class="stat-icon icon-success">
                                    <i class="fas fa-weight"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card stat-card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="stat-label" style="color: var(--warning-color);">Total User</div>
                                <div class="stat-number"><?= number_format($stats['total_user']) ?></div>
                            </div>
                            <div class="col-auto">
                                <div class="stat-icon icon-warning">
                                    <i class="fas fa-users"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card stat-card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="stat-label" style="color: #74b9ff;">Total Pengadaan</div>
                                <div class="stat-number"><?= number_format($stats['total_pengadaan']) ?></div>
                            </div>
                            <div class="col-auto">
                                <div class="stat-icon icon-info">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Data -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title">
                            <i class="fas fa-chart-bar me-2" style="color: var(--primary-color);"></i>
                            Data Barang Terbaru
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nama Barang</th>
                                        <th>Jenis</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($recentBarang)): ?>
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">
                                            <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                            Belum ada data barang
                                        </td>
                                    </tr>
                                    <?php else: ?>
                                        <?php foreach ($recentBarang as $barang): ?>
                                        <tr>
                                            <td><strong><?= $barang['idbarang'] ?></strong></td>
                                            <td><?= htmlspecialchars($barang['nama']) ?></td>
                                            <td>
                                                <?php
                                                switch($barang['jenis']) {
                                                    case '1': echo '<span class="badge bg-primary">Makanan</span>'; break;
                                                    case '2': echo '<span class="badge bg-info">Minuman</span>'; break;
                                                    case '3': echo '<span class="badge bg-success">Bahan Pokok</span>'; break;
                                                    default: echo '<span class="badge bg-secondary">Lainnya</span>';
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check me-1"></i>Aktif
                                                </span>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('show');
        });

        // Auto refresh statistics setiap 30 detik
        setInterval(function() {
            // Bisa ditambahkan AJAX untuk refresh data statistik
        }, 30000);
    </script>
</body>
</html>