<?php
require_once '../../classes/Auth.php';
require_once '../../classes/Database.php';

$auth = new Auth();
$auth->checkRole([1]); // hanya admin

$db = new Database();

// daftar badan hukum (kode → label)
$badanHukumList = [
    'P' => 'PT (Perseroan Terbatas)',
    'C' => 'CV (Commanditaire Vennootschap)',
    'N' => 'Non Badan Hukum / Perorangan'
];
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tambah Vendor</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <style>
    body { background-color: #f8f9fa; }
    .card {
      border: none;
      border-radius: 15px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    }
    .btn-gradient {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      border: none;
    }
    .btn-gradient:hover { opacity: 0.9; }
  </style>
</head>
<body>

<div class="container mt-5">
  <div class="card p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 class="mb-0"><i class="fas fa-truck me-2 text-success"></i>Tambah Vendor</h4>
      <a href="index.php" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left me-1"></i>Kembali</a>
    </div>
    <hr>

    <form action="process.php?action=add" method="POST">
      <div class="mb-3">
        <label class="form-label fw-semibold">Nama Vendor</label>
        <input type="text" name="nama_vendor" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold">Badan Hukum</label>
        <select name="badan_hukum" class="form-select" required>
          <option value="">-- Pilih Badan Hukum --</option>
          <?php foreach ($badanHukumList as $kode => $label): ?>
            <option value="<?= $kode ?>"><?= $label ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold">Status</label>
        <select name="status" class="form-select" required>
          <option value="A">Aktif</option>
          <option value="N">Nonaktif</option>
        </select>
      </div>

      <div class="text-end">
        <button type="submit" class="btn btn-gradient px-4"><i class="fas fa-save me-2"></i>Simpan</button>
      </div>
    </form>
  </div>
</div>

</body>
</html>
