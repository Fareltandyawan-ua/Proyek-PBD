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
  <title>Data Barang - Sistem Pengadaan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <style>
    body { background-color: #f8f9fa; }
    .card { border: none; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.08); }
    .btn-gradient { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; }
    .btn-gradient:hover { opacity: 0.9; }
    .alert { border-radius: 10px; box-shadow: 0 3px 10px rgba(0,0,0,0.05); }
    .table th { background-color: #f1f3f5; text-transform: uppercase; font-size: 0.8rem; }
  </style>
</head>
<body>

<div class="container mt-5 mb-5">

  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold text-secondary"><i class="fas fa-boxes me-2 text-primary"></i>Data Barang</h3>
    <a href="tambah.php" class="btn btn-gradient px-3"><i class="fas fa-plus me-2"></i>Tambah Barang</a>
  </div>

  <!-- Alert -->
  <?php if ($success): ?>
      <div class="alert alert-success alert-dismissible fade show">
          <i class="fas fa-check-circle me-2"></i>
          <?= ($success == 'deleted') ? "Barang berhasil dihapus!" : (($success == 'added') ? "Barang berhasil ditambahkan!" : "Barang berhasil diperbarui!") ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
  <?php elseif ($error): ?>
      <div class="alert alert-danger alert-dismissible fade show">
          <i class="fas fa-exclamation-triangle me-2"></i><?= htmlspecialchars($error) ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
  <?php endif; ?>

  <!-- Card -->
  <div class="card p-4">
    <div class="table-responsive">
      <table class="table table-hover align-middle">
        <thead>
          <tr>
            <th>No</th>
            <th>Nama Barang</th>
            <th>Jenis</th>
            <th>Satuan</th>
            <th>Status</th>
            <th class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
        <?php if (empty($barangList)): ?>
          <tr><td colspan="6" class="text-center text-muted py-4"><i class="fas fa-inbox fa-2x d-block mb-2"></i>Belum ada data barang.</td></tr>
        <?php else: $no = 1; foreach ($barangList as $b): ?>
          <tr>
            <td><?= $no++ ?></td>
            <td><?= htmlspecialchars($b['nama_barang']) ?></td>
            <td>
              <?php
                $jenisLabel = ['1'=>'<span class="badge bg-primary">Makanan</span>',
                               '2'=>'<span class="badge bg-info">Minuman</span>',
                               '3'=>'<span class="badge bg-success">Bahan Pokok</span>',
                               '4'=>'<span class="badge bg-secondary">Lainnya</span>'];
                echo $jenisLabel[$b['jenis_barang']] ?? '<span class="badge bg-secondary">-</span>';
              ?>
            </td>
            <td><?= htmlspecialchars($b['nama_satuan'] ?? '-') ?></td>
            <td><?= ($b['status_barang'] == 'Aktif') ? '<span class="badge bg-success"><i class="fas fa-check me-1"></i>Aktif</span>' : '<span class="badge bg-danger"><i class="fas fa-times me-1"></i>Nonaktif</span>' ?></td>
            <td class="text-center">
              <a href="edit.php?id=<?= $b['idbarang'] ?>" class="btn btn-warning btn-sm me-1"><i class="fas fa-edit"></i></a>
              <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalDelete" data-id="<?= $b['idbarang'] ?>" data-nama="<?= htmlspecialchars($b['nama_barang']) ?>">
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
      <div class="modal-body">
        <p>Apakah Anda yakin ingin menghapus barang <strong id="delete-nama"></strong>?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <a href="#" id="confirmDelete" class="btn btn-danger">Hapus</a>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  const deleteModal = document.getElementById('modalDelete');
  deleteModal.addEventListener('show.bs.modal', event => {
      const button = event.relatedTarget;
      const id = button.getAttribute('data-id');
      const nama = button.getAttribute('data-nama');
      document.getElementById('delete-nama').textContent = nama;
      document.getElementById('confirmDelete').setAttribute('href', 'process.php?action=delete&id=' + id);
  });

  // auto close alert after 3s
  setTimeout(() => {
      const alert = document.querySelector('.alert');
      if (alert) new bootstrap.Alert(alert).close();
  }, 3000);
</script>
</body>
</html>
