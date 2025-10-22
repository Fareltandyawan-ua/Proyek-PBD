<?php
require_once '../../classes/Auth.php';
require_once '../../classes/Database.php';

$auth = new Auth();
$auth->checkRole([1]); // hanya admin

$db = new Database();
$barangList = $db->fetchAll("SELECT * FROM V_BARANG_DETAIL ORDER BY idbarang ASC");

$success = $_GET['success'] ?? '';
$error = $_GET['error'] ?? '';
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Barang - Dashboard Admin</title>
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

        .table thead {
            background: #667eea;
            color: white;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }

        .btn-custom {
            border-radius: 8px;
            padding: 8px 12px;
        }

        .badge {
            font-size: 0.8rem;
        }

        .header-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 12px;
            padding: 15px 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 25px;
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
            <span class="navbar-brand fw-bold"><i class="fas fa-box me-2"></i>Data Barang</span>
            <a href="../dashboard/admin/index.php" class="btn btn-light btn-sm"><i class="fas fa-home me-1"></i> Dashboard</a>
        </div>
    </nav>

    <!-- Sidebar -->
    <div class="sidebar">
        <a href="../dashboard/admin/index.php"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a>
        <a href="#" class="active"><i class="fas fa-box me-2"></i>Data Barang</a>
        <a href="../satuan/index.php"><i class="fas fa-weight me-2"></i>Data Satuan</a>
        <a href="../vendor/index.php"><i class="fas fa-truck me-2"></i>Data Vendor</a>
        <a href="../auth/logout.php" class="text-danger"><i class="fas fa-sign-out-alt me-2"></i>Logout</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <div class="header-card d-flex justify-content-between align-items-center">
            <h4 class="mb-0"><i class="fas fa-cubes me-2"></i>Daftar Barang</h4>
            <a href="tambah.php" class="btn btn-success btn-sm"><i class="fas fa-plus me-1"></i>Tambah Barang</a>
        </div>

        <!-- Alert -->
        <?php if ($success): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                <?php 
                    if ($success == 'added') echo "Barang berhasil ditambahkan!";
                    elseif ($success == 'updated') echo "Barang berhasil diperbarui!";
                    elseif ($success == 'deleted') echo "Barang berhasil dihapus!";
                ?>
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
                            <th>ID</th>
                            <th>Nama Barang</th>
                            <th>Jenis Barang</th>
                            <th>Satuan</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($barangList)): ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                    Tidak ada data barang.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($barangList as $b): ?>
                                <tr>
                                    <td><?= $b['idbarang'] ?></td>
                                    <td><strong><?= htmlspecialchars($b['nama_barang']) ?></strong></td>
                                    <td><?= htmlspecialchars($b['jenis_barang']) ?></td>
                                    <td><?= htmlspecialchars($b['nama_satuan']) ?></td>
                                    <td>
                                        <?php if ($b['status_barang'] === 'Aktif'): ?>
                                            <span class="badge bg-success"><i class="fas fa-check"></i> Aktif</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary"><i class="fas fa-ban"></i> Tidak Aktif</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <a href="edit.php?id=<?= $b['idbarang'] ?>" class="btn btn-warning btn-sm btn-custom">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-danger btn-sm btn-custom"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalDelete"
                                            data-id="<?= $b['idbarang'] ?>"
                                            data-nama="<?= htmlspecialchars($b['nama_barang']) ?>">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Hapus -->
    <form id="deleteForm" action="process.php?action=delete" method="POST">
        <input type="hidden" name="idbarang" id="delete-id">
        <div class="modal fade" id="modalDelete" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Konfirmasi Hapus</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin menghapus barang <strong id="delete-nama"></strong>?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const deleteModal = document.getElementById('modalDelete');
        deleteModal.addEventListener('show.bs.modal', event => {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const nama = button.getAttribute('data-nama');
            document.getElementById('delete-id').value = id;
            document.getElementById('delete-nama').textContent = nama;
        });

        // auto close alert after 3s
        setTimeout(() => {
            const alert = document.querySelector('.alert');
            if (alert) new bootstrap.Alert(alert).close();
        }, 3000);
    </script>
</body>
</html>
