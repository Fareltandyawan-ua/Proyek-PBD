<?php
require_once '../../classes/Auth.php';
require_once '../../classes/Database.php';

$auth = new Auth();
$auth->checkRole([2]);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tambah Role - Superadmin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <style>
    body { background-color: #f8f9fa; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
    .card { border: none; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.08); }
    .btn-gradient { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; }
  </style>
</head>
<body>
<div class="container mt-5">
  <div class="card p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 class="mb-0"><i class="fas fa-user-shield me-2 text-success"></i>Tambah Role Baru</h4>
      <a href="index.php" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left me-1"></i>Kembali</a>
    </div>
    <hr>

    <form action="process.php?action=add" method="POST">
      <div class="mb-3">
        <label class="form-label fw-semibold">Nama Role</label>
        <input type="text" name="nama_role" class="form-control" required>
      </div>

      <div class="text-end">
        <button type="submit" class="btn btn-gradient px-4"><i class="fas fa-save me-2"></i>Simpan</button>
      </div>
    </form>
  </div>
</div>
</body>
</html>
