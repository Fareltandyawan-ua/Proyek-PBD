<?php
session_start();
require_once '../../../classes/Auth.php';
require_once '../../../classes/Penerimaan.php';

$auth = new Auth();
$auth->checkRole([1]);

$penerimaan = new Penerimaan();

// Ambil list pengadaan untuk dropdown
$pengadaanList = $penerimaan->getPengadaanPending();

// Ambil detail barang jika user sudah memilih pengadaan
$detailBarang = [];
$selectedPengadaan = null;

if (isset($_GET['idpengadaan'])) {
    $detailBarang = $penerimaan->getBarangByPengadaan($_GET['idpengadaan']);
    $selectedPengadaan = $_GET['idpengadaan'];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Penerimaan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .card-custom {
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .table-input input {
            border: 1px solid #dee2e6;
            border-radius: 6px;
            padding: 8px;
        }
        .btn-save {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 10px 30px;
            border-radius: 8px;
            font-weight: 600;
        }
        .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-dark">
        <div class="container-fluid">
            <span class="navbar-brand fw-bold">
                <i class="fas fa-inbox me-2"></i>Tambah Penerimaan Barang
            </span>
            <a href="index.php" class="btn btn-light btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </nav>

    <div class="container mt-4">

        <div class="card card-custom">
            <div class="card-body p-4">

                <!-- Form Pilih Pengadaan -->
                <form method="GET" class="mb-4">
                    <div class="row g-3">
                        <div class="col-md-9">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-clipboard-list me-2"></i>Pilih Pengadaan (PO)
                            </label>
                            <select name="idpengadaan" class="form-select" required onchange="this.form.submit()">
                                <option value="">-- Pilih Pengadaan --</option>
                                <?php foreach ($pengadaanList as $p): ?>
                                    <option value="<?= $p['idpengadaan'] ?>" 
                                        <?= ($selectedPengadaan == $p['idpengadaan']) ? 'selected' : '' ?>>
                                        PO #<?= $p['idpengadaan'] ?> - <?= htmlspecialchars($p['nama_vendor']) ?> 
                                        (<?= date('d M Y', strtotime($p['timestamp'])) ?>) - 
                                        Rp <?= number_format($p['total_nilai'], 0, ',', '.') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search me-1"></i> Muat Data
                            </button>
                        </div>
                    </div>
                </form>

                <hr>

                <!-- Form Input Barang (tampil setelah pilih pengadaan) -->
                <?php if (!empty($detailBarang)): ?>
                    <form method="POST" action="proses_simpan.php" id="formPenerimaan">
                        
                        <input type="hidden" name="idpengadaan" value="<?= $selectedPengadaan ?>">

                        <h5 class="mb-3 fw-bold text-primary">
                            <i class="fas fa-boxes me-2"></i>Detail Barang yang Diterima
                        </h5>

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle table-input">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="30%">Nama Barang</th>
                                        <th width="10%">Satuan</th>
                                        <th width="10%">Jumlah Pesan</th>
                                        <th width="10%">Sudah Terima</th>
                                        <th width="10%">Sisa</th>
                                        <th width="12%">Terima Sekarang</th>
                                        <th width="13%">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $no = 1;
                                    foreach ($detailBarang as $row): 
                                        $sisa = $row['sisa'];
                                        if ($sisa <= 0) continue; // Skip barang yang sudah lengkap
                                    ?>
                                        <tr>
                                            <td class="text-center"><?= $no++ ?></td>
                                            <td><?= htmlspecialchars($row['nama']) ?></td>
                                            <td class="text-center"><?= htmlspecialchars($row['nama_satuan'] ?? '-') ?></td>
                                            <td class="text-center">
                                                <span class="badge bg-info"><?= $row['jumlah'] ?></span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-success"><?= $row['total_diterima'] ?></span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-warning text-dark"><?= $sisa ?></span>
                                            </td>
                                            <td>
                                                <input type="number" 
                                                       name="barang[<?= $row['idbarang'] ?>][jumlah_terima]"
                                                       class="form-control jumlah-terima" 
                                                       data-id="<?= $row['idbarang'] ?>"
                                                       data-harga="<?= $row['harga_satuan'] ?>"
                                                       min="0" 
                                                       max="<?= $sisa ?>"
                                                       placeholder="0"
                                                       value="<?= $sisa ?>">
                                                
                                                <input type="hidden" 
                                                       name="barang[<?= $row['idbarang'] ?>][harga_satuan]" 
                                                       value="<?= $row['harga_satuan'] ?>">
                                            </td>
                                            <td>
                                                <input type="text" 
                                                       class="form-control subtotal bg-light" 
                                                       id="subtotal_<?= $row['idbarang'] ?>" 
                                                       value="Rp 0" 
                                                       readonly>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr class="table-secondary fw-bold">
                                        <td colspan="7" class="text-end">TOTAL:</td>
                                        <td>
                                            <input type="text" 
                                                   id="grandTotal" 
                                                   class="form-control bg-light fw-bold" 
                                                   value="Rp 0" 
                                                   readonly>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-save">
                                <i class="fas fa-save me-2"></i>Simpan Penerimaan
                            </button>
                            <a href="index.php" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Batal
                            </a>
                        </div>

                    </form>
                <?php else: ?>
                    <div class="alert alert-info text-center">
                        <i class="fas fa-info-circle me-2"></i>
                        Silakan pilih pengadaan terlebih dahulu untuk memuat data barang.
                    </div>
                <?php endif; ?>

            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Format rupiah
        function formatRupiah(angka) {
            return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        // Hitung subtotal dan grand total
        function hitungTotal() {
            let grandTotal = 0;

            document.querySelectorAll('.jumlah-terima').forEach(input => {
                const id = input.dataset.id;
                const qty = parseFloat(input.value) || 0;
                const harga = parseFloat(input.dataset.harga) || 0;
                const subtotal = qty * harga;

                // Update subtotal per item
                document.getElementById('subtotal_' + id).value = formatRupiah(subtotal);

                // Tambah ke grand total
                grandTotal += subtotal;
            });

            // Update grand total
            document.getElementById('grandTotal').value = formatRupiah(grandTotal);
        }

        // Event listener untuk setiap input jumlah
        document.querySelectorAll('.jumlah-terima').forEach(input => {
            input.addEventListener('input', hitungTotal);
        });

        // Hitung saat halaman dimuat (auto-fill dengan sisa)
        window.addEventListener('load', hitungTotal);

        // Validasi form sebelum submit
        document.getElementById('formPenerimaan')?.addEventListener('submit', function(e) {
            let adaInput = false;
            
            document.querySelectorAll('.jumlah-terima').forEach(input => {
                if (parseFloat(input.value) > 0) {
                    adaInput = true;
                }
            });

            if (!adaInput) {
                e.preventDefault();
                alert('⚠️ Harap isi minimal 1 barang yang diterima!');
                return false;
            }

            // Konfirmasi
            if (!confirm('Simpan data penerimaan ini?')) {
                e.preventDefault();
                return false;
            }
        });
    </script>

</body>
</html>