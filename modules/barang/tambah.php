<?php
require_once '../../classes/Auth.php';
require_once '../../classes/Database.php';

$auth = new Auth();
$auth->checkRole([1]); // hanya admin

$db = new Database();

// Ambil semua data satuan untuk dropdown
$satuanList = $db->fetchAll("SELECT * FROM satuan");

// Jenis barang (kode CHAR(1))
$jenisList = [
    '1' => 'Makanan',
    '2' => 'Minuman',
    '3' => 'Bahan Pokok',
    '4' => 'Lainnya'
];
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tambah Barang</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
      <h4 class="mb-0"><i class="fas fa-plus-circle me-2 text-success"></i>Tambah Barang</h4>
      <a href="index.php" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left me-1"></i>Kembali
      </a>
    </div>
    <hr>

    <form action="/UAS_pbdprak/modules/barang/process.php?action=add" method="POST">
      <div class="mb-3">
        <label class="form-label fw-semibold">Nama Barang</label>
        <input type="text" name="nama" class="form-control" placeholder="Masukkan nama barang..." required>
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold">Jenis Barang</label>
        <select name="jenis" class="form-select" required>
          <option value="">-- Pilih Jenis Barang --</option>
          <?php foreach ($jenisList as $kode => $namaJenis): ?>
            <option value="<?= $kode ?>"><?= $namaJenis ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold">Satuan</label>
        <select name="idsatuan" class="form-select" required>
          <option value="">-- Pilih Satuan --</option>
          <?php foreach ($satuanList as $s): ?>
            <option value="<?= $s['idsatuan'] ?>"><?= $s['nama_satuan'] ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold">Status</label>
        <select name="status" class="form-select" required>
          <option value="1">Aktif</option>
          <option value="0">Nonaktif</option>
        </select>
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
