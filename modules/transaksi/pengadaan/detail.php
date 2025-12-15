<?php
require_once '../../../classes/Auth.php';
require_once '../../../classes/Database.php';

$auth = new Auth();
$auth->checkRole([1]);

$db = new Database();

// Cek ID Pengadaan
if (!isset($_GET['idpengadaan'])) {
    die("ID Pengadaan tidak ditemukan!");
}

$idpengadaan = $_GET['idpengadaan'];

// Ambil data detail
$detailList = $db->fetchAll("
    SELECT dp.*, b.nama 
    FROM detail_pengadaan dp
    JOIN barang b ON dp.idbarang = b.idbarang
    WHERE dp.idpengadaan = ?
", [$idpengadaan]);

// Ambil barang aktif untuk input tambah
$barangList = $db->fetchAll("SELECT idbarang, nama, harga FROM barang WHERE status = 1 ORDER BY nama");

// Hitung subtotal
$subtotal = 0;
foreach ($detailList as $d) {
    $subtotal += ($d['jumlah'] * $d['harga_satuan']);
}

// Hitung PPN (10%)
$ppn = $subtotal * 0.10;

// Hitung Total
$total = $subtotal + $ppn;

// Proses insert item baru
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idbarang = $_POST['idbarang'];
    $jumlah = $_POST['jumlah'];
    $harga = $db->fetchAll("SELECT harga FROM barang WHERE idbarang = $idbarang");
    $harga = $harga[0]['harga'];
    $subtotal = $jumlah * $harga;
    
    $db->execute("
        INSERT INTO detail_pengadaan (idpengadaan, idbarang, jumlah, harga_satuan, sub_total)
        VALUES (?, ?, ?, ?, ?)
    ", [$idpengadaan, $idbarang, $jumlah, $harga, $subtotal]);

    header("Location: detail.php?idpengadaan=" . $idpengadaan);
    exit;
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Detail Pengadaan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background: #eef2f7;
        }

        .card-custom {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .summary-box {
            font-size: 1.1rem;
        }

        .summary-value {
            font-weight: bold;
            font-size: 1.3rem;
            color: #4a4ae4;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-dark px-3" style="background: linear-gradient(135deg,#667eea,#764ba2)">
        <span class="navbar-brand fw-bold"><i class="fas fa-file-invoice me-2"></i>Detail Pengadaan
            #<?= $idpengadaan ?></span>
        <a href="index.php" class="btn btn-light btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
    </nav>

    <div class="container mt-4">

        <!-- Form Tambah Item -->
        <div class="card-custom mb-4">
            <h5 class="fw-bold mb-3"><i class="fas fa-plus me-1"></i>Tambah Item Barang</h5>

            <form method="POST" class="row g-3">

                <div class="col-md-4">
                    <label class="form-label">Barang</label>
                    <select name="idbarang" class="form-select" required>
                        <option value="">-- pilih barang --</option>
                        <?php foreach ($barangList as $b): ?>
                            <option value="<?= $b['idbarang']?>"><?= $b['nama'] ?> -- <?='Rp.'. $b['harga'] ?> --</option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Jumlah</label>
                    <input type="number" name="jumlah" class="form-control" required min="1">
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <button class="btn btn-success w-100"><i class="fas fa-check"></i> Tambah</button>
                </div>

            </form>
        </div>

        <!-- Tabel Detail -->
        <div class="card-custom mb-4">
            <h5 class="fw-bold mb-3"><i class="fas fa-table me-2"></i>Daftar Item</h5>

            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Barang</th>
                        <th>Jumlah</th>
                        <th>Harga</th>
                        <th>Subtotal</th>
                        <th class=>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    <?php $no = 1;
                    foreach ($detailList as $d): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $d['nama'] ?></td>
                            <td><?= $d['jumlah'] ?></td>
                            <td>Rp <?= number_format($d['harga_satuan'], 0, ',', '.') ?></td>
                            <td>Rp <?= number_format($d['jumlah'] * $d['harga_satuan'], 0, ',', '.') ?></td>
                            <td>
                                <a href="edit_detail.php?id=<?= $d['iddetail_pengadaan'] ?>&idpengadaan=<?= $idpengadaan ?>"
                                    class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <a href="delete_detail.php?id=<?= $d['iddetail_pengadaan'] ?>&idpengadaan=<?= $idpengadaan ?>"
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Yakin ingin menghapus item ini?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Ringkasan -->
        <div class="card-custom">
            <div class="d-flex justify-content-between summary-box">
                <span>Subtotal:</span>
                <span class="summary-value">Rp <?= number_format($subtotal, 0, ',', '.') ?></span>
            </div>

            <div class="d-flex justify-content-between summary-box mt-2">
                <span>PPN (10%):</span>
                <span class="summary-value">Rp <?= number_format($ppn, 0, ',', '.') ?></span>
            </div>

            <hr>

            <div class="d-flex justify-content-between summary-box">
                <span>Total Akhir:</span>
                <span class="summary-value text-success">Rp <?= number_format($total, 0, ',', '.') ?></span>
            </div>
        </div>

    </div>

</body>

</html>