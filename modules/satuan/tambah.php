<?php
require_once '../../classes/Auth.php';
require_once '../../classes/Satuan.php';

$auth = new Auth();
$auth->checkRole([1]); // hanya admin

$satuan = new Satuan();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tambah Satuan - Dashboard Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <style>
    body { background-color: #f8f9fa; }
    .card { border: none; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.08); }
    .btn-gradient { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; }
    .btn-gradient:hover { opacity: 0.9; }
  </style>
</head>
<body>
<div class="container mt-5">
  <div class="card p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 class="mb-0"><i class="fas fa-plus-circle me-2 text-success"></i>Tambah Satuan</h4>
      <a href="index.php" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left me-1"></i> Kembali
      </a>
    </div>
    <hr>

    <form action="process.php?action=add" method="POST">
      <div class="mb-3">
        <label class="form-label fw-semibold">Nama Satuan</label>
        <input type="text" name="nama_satuan" class="form-control" placeholder="Masukkan nama satuan" required>
      </div>

      <div class="text-end">
        <button type="submit" class="btn btn-gradient px-4">
          <i class="fas fa-save me-2"></i>Simpan
        </button>
      </div>
    </form>
  </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
</body>
</html>
