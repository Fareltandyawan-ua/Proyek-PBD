<?php
session_start();
require_once '../../classes/Auth.php';
require_once '../../classes/Database.php';

$auth = new Auth();
$auth->checkRole([1]); // hanya admin

$db = new Database();

$error = '';
$success = '';

// Pastikan user sudah login
if (!isset($_SESSION['iduser'])) {
    header("Location: ../auth/login.php");
    exit;
}

// Proses simpan data margin penjualan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $persen = trim($_POST['persen'] ?? '');
    $status = isset($_POST['status']) ? (int)$_POST['status'] : 1;
    $iduser = $_SESSION['iduser'];

    if ($persen === '') {
        $error = "Persentase margin tidak boleh kosong!";
    } elseif (!is_numeric($persen)) {
        $error = "Persentase harus berupa angka.";
    } elseif ($persen < 0) {
        $error = "Persentase margin tidak boleh negatif.";
    } else {
        try {
            include '../../config/database.php';

            // Panggil stored procedure
            $stmt = $pdo->prepare("CALL sp_tambah_margin(?, ?)");
            $stmt->execute([$persen, $iduser]);

            header("Location: index.php?success=added");
            exit;
        } catch (Exception $e) {
            $error = "Gagal menambahkan margin: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Margin Penjualan</title>
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
        .btn-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            border: none;
        }
        .btn-gradient:hover {
            opacity: 0.9;
        }
        .sidebar {
            background: #fff;
            width: 250px;
            position: fixed;
            top: 56px;
            bottom: 0;
            left: 0;
            box-shadow: 2px 0 8px rgba(0,0,0,0.1);
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
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-dark fixed-top">
        <div class="container-fluid">
            <span class="navbar-brand fw-bold"><i class="fas fa-percent me-2"></i>Tambah Margin Penjualan</span>
            <a href="../dashboard/admin/index.php" class="btn btn-light btn-sm"><i class="fas fa-home me-1"></i>Dashboard</a>
        </div>
    </nav>

    <!-- Sidebar -->
    <div class="sidebar">
        <a href="../dashboard/admin/index.php"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a>
        <a href="../barang/index.php"><i class="fas fa-box me-2"></i>Data Barang</a>
        <a href="../satuan/index.php"><i class="fas fa-weight me-2"></i>Data Satuan</a>
        <a href="../vendor/index.php"><i class="fas fa-truck me-2"></i>Data Vendor</a>
        <a href="../margin/index.php" class="active"><i class="fas fa-percent me-2"></i>Margin Penjualan</a>
        <li class="nav-item">
            <a class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#collapseTransaksi" role="button" aria-expanded="false" aria-controls="collapseTransaksi">
                <span><i class="fas fa-exchange-alt me-2"></i>Transaksi</span>
                <i class="fas fa-chevron-right small"></i>
            </a>
            <div class="collapse ps-3" id="collapseTransaksi">
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
        <a href="../auth/logout.php" class="text-danger"><i class="fas fa-sign-out-alt me-2"></i>Logout</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="header-card d-flex justify-content-between align-items-center">
            <a href="index.php" class="btn btn-light btn-sm"><i class="fas fa-arrow-left me-1"></i>Kembali</a>
            <h4 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Form Tambah Margin</h4>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i><?= htmlspecialchars($error) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="card p-4">
            <form method="POST" class="row g-3">
                <div class="col-md-4">
                    <label for="persen" class="form-label fw-semibold">Persentase Margin (%)</label>
                    <input type="number" step="0.01" min="0" id="persen" name="persen" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label for="status" class="form-label fw-semibold">Status</label>
                    <select id="status" name="status" class="form-select" required>
                        <option value="1">Aktif</option>
                        <option value="0">Tidak Aktif</option>
                    </select>
                </div>
                <input type="hidden" name="iduser" value="<?= htmlspecialchars($_SESSION['iduser']) ?>">
                <div class="col-12 text-end">
                    <button type="submit" class="btn btn-gradient px-4">
                        <i class="fas fa-save me-1"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        setTimeout(() => {
            const alert = document.querySelector('.alert');
            if (alert) new bootstrap.Alert(alert).close();
        }, 3000);
    </script>
</body>
</html>
