<?php
require_once '../../classes/Auth.php';
require_once '../../classes/Database.php';

$auth = new Auth();
$auth->checkRole([1]); // hanya admin

$db = new Database();
$id = $_GET['id'] ?? 0;

// ambil data barang berdasarkan ID
$barang = $db->fetch("SELECT * FROM barang WHERE idbarang = ?", [$id]);
if (!$barang) {
  die("Data tidak ditemukan");
}

$satuanList = $db->fetchAll("SELECT * FROM satuan");

// jenis barang disimpan sebagai kode CHAR(1)
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
  <title>Edit Barang</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }

    .card {
      border: none;
      border-radius: 15px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
    }

    .btn-gradient {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      border: none;
    }

    .btn-gradient:hover {
      opacity: 0.9;
    }
  </style>
</head>

<body>
  <div class="container mt-5">
    <div class="card p-4">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0"><i class="fas fa-edit me-2 text-warning"></i>Edit Barang</h4>
      </div>
      <hr>

      <form action="process.php?action=update" method="POST">
        <input type="hidden" name="idbarang" value="<?= $barang['idbarang'] ?>">

        <div class="mb-3">
          <label class="form-label fw-semibold">Nama Barang</label>
          <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($barang['nama']) ?>" required>
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">Jenis Barang</label>
          <select name="jenis" class="form-select" required>
            <?php foreach ($jenisList as $kode => $namaJenis): ?>
              <option value="<?= $kode ?>" <?= ($barang['jenis'] == $kode) ? 'selected' : '' ?>>
                <?= $namaJenis ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">Satuan</label>
          <select name="idsatuan" class="form-select" required>
            <?php foreach ($satuanList as $s): ?>
              <option value="<?= $s['idsatuan'] ?>" <?= ($barang['idsatuan'] == $s['idsatuan']) ? 'selected' : '' ?>>
                <?= $s['nama_satuan'] ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">Harga</label>
          <input type="text" name="harga" class="form-control" value="<?= htmlspecialchars($barang['harga']) ?>" required>
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">Status</label>
          <select name="status" class="form-select" required>
            <option value="1" <?= ($barang['status'] == 1) ? 'selected' : '' ?>>Aktif</option>
            <option value="0" <?= ($barang['status'] == 0) ? 'selected' : '' ?>>Nonaktif</option>
          </select>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-4">
          <a href="index.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Kembali
          </a>
          <button type="submit" class="btn btn-primary"
            style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
            <i class="fas fa-save me-1"></i> Update
          </button>
        </div>

      </form>
    </div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
</body>

</html>