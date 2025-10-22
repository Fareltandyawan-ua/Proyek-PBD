<?php
require_once '../../classes/Auth.php';
require_once '../../classes/Database.php';

$auth = new Auth();
$auth->checkRole([2]); // hanya superadmin

$db = new Database();
$roles = $db->fetchAll("SELECT * FROM role ORDER BY idrole ASC");

$success = $_GET['success'] ?? '';
$error = $_GET['error'] ?? '';
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Kelola Role - Superadmin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <style>
    body { background-color: #f8f9fa; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
    .navbar { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); box-shadow: 0 3px 10px rgba(0,0,0,0.15); }
    .sidebar { background: #fff; width: 250px; position: fixed; top: 56px; bottom: 0; left: 0; box-shadow: 2px 0 8px rgba(0,0,0,0.1); padding-top: 15px; }
    .sidebar a { color: #495057; display: block; padding: 10px 20px; border-radius: 8px; margin: 5px 15px; text-decoration: none; transition: all 0.3s ease; }
    .sidebar a:hover, .sidebar a.active { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
    .main-content { margin-left: 250px; padding: 30px; margin-top: 70px; }
    .header-card { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 12px; padding: 15px 20px; margin-bottom: 25px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
    .card { border: none; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.08); }
    .alert-custom { position: fixed; top: 80px; right: 20px; min-width: 300px; z-index: 9999; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.15); }
  </style>
</head>
<body>

<nav class="navbar navbar-dark fixed-top">
  <div class="container-fluid">
    <span class="navbar-brand fw-bold"><i class="fas fa-shield-alt me-2"></i>Kelola Role</span>
    <a href="../dashboard/superadmin/index.php" class="btn btn-light btn-sm"><i class="fas fa-home me-1"></i> Dashboard</a>
  </div>
</nav>

<div class="sidebar">
  <a href="../dashboard/superadmin/index.php"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a>
  <a href="../user/index.php"><i class="fas fa-users me-2"></i>Kelola User</a>
  <a href="#" class="active"><i class="fas fa-user-shield me-2"></i>Kelola Role</a>
  <a href="../auth/logout.php" class="text-danger"><i class="fas fa-sign-out-alt me-2"></i>Logout</a>
</div>

<div class="main-content">
  <div class="header-card d-flex justify-content-between align-items-center">
    <h4 class="mb-0"><i class="fas fa-id-badge me-2"></i>Daftar Role</h4>
    <a href="tambah.php" class="btn btn-success btn-sm"><i class="fas fa-plus me-1"></i>Tambah Role</a>
  </div>

  <div class="card p-3">
    <div class="table-responsive">
      <table class="table table-hover align-middle">
        <thead>
          <tr>
            <th>ID</th>
            <th>Nama Role</th>
            <th class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($roles)): ?>
            <tr><td colspan="3" class="text-center text-muted py-4"><i class="fas fa-inbox fa-2x mb-2 d-block"></i>Tidak ada data role.</td></tr>
          <?php else: foreach ($roles as $r): ?>
            <tr>
              <td><?= $r['idrole'] ?></td>
              <td><strong><?= htmlspecialchars($r['nama_role']) ?></strong></td>
              <td class="text-center">
                <a href="edit.php?id=<?= $r['idrole'] ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalDelete" data-id="<?= $r['idrole'] ?>" data-nama="<?= htmlspecialchars($r['nama_role']) ?>"><i class="fas fa-trash"></i></button>
              </td>
            </tr>
          <?php endforeach; endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal Hapus -->
<div class="modal fade" id="modalDelete" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Konfirmasi Hapus</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p>Apakah Anda yakin ingin menghapus role <strong id="delete-nama"></strong>?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <a href="#" id="confirmDelete" class="btn btn-danger">Hapus</a>
      </div>
    </div>
  </div>
</div>

<!-- Alert -->
<?php if ($success): ?>
  <div class="alert alert-success alert-custom alert-dismissible fade show">
    <i class="fas fa-check-circle me-2"></i>
    <?= ($success == 'added') ? "Role berhasil ditambahkan!" :
        (($success == 'updated') ? "Role berhasil diperbarui!" : "Role berhasil dihapus!") ?>
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
  const deleteModal = document.getElementById('modalDelete');
  deleteModal.addEventListener('show.bs.modal', event => {
    const button = event.relatedTarget;
    const id = button.getAttribute('data-id');
    const nama = button.getAttribute('data-nama');
    document.getElementById('delete-nama').textContent = nama;
    document.getElementById('confirmDelete').setAttribute('href', 'process.php?action=delete&id=' + id);
  });
  setTimeout(() => {
    const alert = document.querySelector('.alert-custom');
    if (alert) new bootstrap.Alert(alert).close();
  }, 3000);
</script>
</body>
</html>
