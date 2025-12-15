<?php
require_once '../../classes/Auth.php';
require_once '../../classes/Database.php';

$auth = new Auth();
$auth->checkRole([1]); // hanya admin

$db = new Database();

$statusFilter = $_GET['status'] ?? '1';

try {
  if ($statusFilter === '1') {
    // status aktif
    $satuanList = $db->fetchAll("SELECT * FROM v_satuan_aktif ORDER BY idsatuan ASC");
  } elseif ($statusFilter === '0') {
    // status tidak aktif
    $satuanList = $db->fetchAll("SELECT * FROM v_satuan WHERE status = 0 ORDER BY idsatuan ASC");
  } else {
    // Semua status
    $satuanList = $db->fetchAll("SELECT * FROM v_satuan ORDER BY idsatuan ASC");
  }
} catch (Exception $e) {
  die("Terjadi kesalahan: " . $e->getMessage());
}

$success = $_GET['success'] ?? '';
$error = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Data Satuan - Dashboard Admin</title>
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
  </style>
</head>

<body>

  <!-- Navbar -->
  <nav class="navbar navbar-dark fixed-top">
    <div class="container-fluid">
      <span class="navbar-brand fw-bold"><i class="fas fa-weight me-2"></i>Data Satuan</span>
      <a href="../dashboard/admin/index.php" class="btn btn-light btn-sm"><i class="fas fa-home me-1"></i> Dashboard</a>
    </div>
  </nav>

  <!-- Sidebar -->
  <div class="sidebar">
    <a href="../dashboard/admin/index.php"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a>
    <a href="../barang/index.php"><i class="fas fa-box me-2"></i>Data Barang</a>
    <a href="#" class="active"><i class="fas fa-weight me-2"></i>Data Satuan</a>
    <a href="../vendor/index.php"><i class="fas fa-truck me-2"></i>Data Vendor</a>
    <a href="../margin/index.php"><i class="fas fa-chart-line me-2"></i>Margin Penjualan</a>
    <a href="../kartu_stok/index.php"><i class="fas fa-chart-line me-2"></i>Kartu Stok</a>
    <!-- Menu Transaksi -->
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
      <h4 class="mb-0"><i class="fas fa-ruler me-2"></i>Daftar Satuan</h4>
      <a href="tambah.php" class="btn btn-success btn-sm"><i class="fas fa-plus me-1"></i>Tambah Satuan</a>
    </div>

    <!-- Filter Form -->
    <form method="GET" class="mb-3 d-flex align-items-center gap-2">
      <label for="status" class="fw-semibold text-dark mb-0">Tampilkan:</label>
      <select name="status" id="status" class="form-select form-select-sm"
        onchange="this.form.submit()" style="min-width: 140px;">
        <option value="" <?= (isset($_GET['status']) && $_GET['status'] === '') ? 'selected' : '' ?>>Semua Satuan
        </option>
        <option value="1" <?= (!isset($_GET['status']) || $_GET['status'] === '1') ? 'selected' : '' ?>>Aktif
        </option>
        <option value="0" <?= (isset($_GET['status']) && $_GET['status'] === '0') ? 'selected' : '' ?>>Tidak Aktif
        </option>
      </select>
    </form>

    <div class="card p-3">
      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead>
            <tr>
              <th>No</th>
              <th>Nama Satuan</th>
              <th>Status</th>
              <th class="text-center">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($satuanList)): ?>
              <tr>
                <td colspan="3" class="text-center text-muted py-4"><i class="fas fa-inbox fa-2x mb-2 d-block"></i>Tidak
                  ada data satuan.</td>
              </tr>
            <?php else:
              foreach ($satuanList as $i => $s): ?>
                <tr>
                  <td><?= $i + 1 ?></td>
                  <td><strong><?= htmlspecialchars($s['nama_satuan']) ?></strong></td>
                  <td>
                    <?php if ($s['status'] == 1): ?>
                      <span class="badge bg-success"><i class="fas fa-check"></i> Aktif</span>
                    <?php else: ?>
                      <span class="badge bg-secondary"><i class="fas fa-ban"></i> Tidak Aktif</span>
                    <?php endif; ?>
                  </td>
                  <td class="text-center">
                    <a href="edit.php?id=<?= $s['idsatuan'] ?>" class="btn btn-warning btn-sm"><i
                        class="fas fa-edit"></i></a>
                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalDelete"
                      data-id="<?= $s['idsatuan'] ?>" data-nama="<?= htmlspecialchars($s['nama_satuan']) ?>">
                      <i class="fas fa-trash"></i>
                    </button>
                  </td>
                </tr>
              <?php endforeach; endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Modal Konfirmasi Hapus -->
  <div class="modal fade" id="modalDelete" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-0 shadow">
        <div class="modal-header bg-danger text-white">
          <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Konfirmasi Hapus</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <form action="process.php?action=delete" method="POST">
          <div class="modal-body">
            <p>Apakah Anda yakin ingin menghapus satuan <strong id="delete-nama"></strong>?</p>
            <input type="hidden" name="idsatuan" id="delete-id">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-danger">Hapus</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Alert -->
  <?php if ($success): ?>
    <div class="alert alert-success alert-custom alert-dismissible fade show">
      <i class="fas fa-check-circle me-2"></i>
      <?= ($success == 'added') ? "Satuan berhasil ditambahkan!" :
        (($success == 'updated') ? "Satuan berhasil diperbarui!" : "Satuan berhasil dihapus!") ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php elseif ($error): ?>
    <div class="alert alert-danger alert-custom alert-dismissible fade show">
      <i class="fas fa-exclamation-triangle me-2"></i><?= htmlspecialchars($error) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php endif; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const modalDelete = document.getElementById('modalDelete');
    modalDelete.addEventListener('show.bs.modal', event => {
      const button = event.relatedTarget;
      document.getElementById('delete-id').value = button.getAttribute('data-id');
      document.getElementById('delete-nama').textContent = button.getAttribute('data-nama');
    });
    setTimeout(() => {
      const alert = document.querySelector('.alert-custom');
      if (alert) new bootstrap.Alert(alert).close();
    }, 3000);
  </script>
</body>

</html>