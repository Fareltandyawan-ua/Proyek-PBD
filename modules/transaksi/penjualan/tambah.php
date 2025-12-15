<?php
require_once '../../../classes/Auth.php';
require_once '../../../classes/Database.php';

$auth = new Auth();
$auth->checkRole([1]);
$db = new Database();

// Ambil margin aktif
$margin = $db->fetch("SELECT * FROM margin_penjualan WHERE status = 1 LIMIT 1");
$idmargin_penjualan = $margin ? $margin['idmargin_penjualan'] : null;
$persen_margin = $margin ? $margin['persen'] : 0;

// Ambil barang
$barangList = $db->fetchAll("SELECT idbarang, nama, harga FROM barang WHERE status = 1");

// User login
$iduser = 1; // Ganti dengan session user

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Simpan header penjualan
    $subtotal_nilai = $_POST['subtotal_nilai'];
    $ppn = $_POST['ppn'];
    $total_nilai = $_POST['total_nilai'];
    $idmargin_penjualan = $_POST['idmargin_penjualan'];
    $iduser = $_POST['iduser'];

    $db->execute(
        "INSERT INTO penjualan (created_at, subtotal_nilai, ppn, total_nilai, iduser, idmargin_penjualan) VALUES (NOW(),?,?,?,?,?)",
        [$subtotal_nilai, $ppn, $total_nilai, $iduser, $idmargin_penjualan]
    );
    $idpenjualan = $db->lastInsertId();

    // Simpan detail penjualan
    if (!empty($_POST['detail'])) {
        foreach ($_POST['detail'] as $d) {
            $db->execute(
                "INSERT INTO detail_penjualan (idpenjualan, idbarang, jumlah, harga_satuan, subtotal) VALUES (?,?,?,?,?)",
                [$idpenjualan, $d['idbarang'], $d['jumlah'], $d['harga_satuan'], $d['subtotal']]
            );
        }
    }
    header("Location: index.php?success=Penjualan berhasil disimpan");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Penjualan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        let detailList = [];

        function addDetail() {
            const idbarang = document.getElementById('idbarang').value;
            const nama_barang = document.getElementById('idbarang').selectedOptions[0].text;
            const jumlah = parseInt(document.getElementById('jumlah').value) || 0;
            const harga_satuan = parseInt(document.getElementById('harga_satuan').value) || 0;
            const subtotal = jumlah * harga_satuan;

            if (!idbarang || jumlah <= 0 || harga_satuan <= 0) {
                alert('Barang, jumlah, dan harga harus diisi!');
                return;
            }

            detailList.push({idbarang, nama_barang, jumlah, harga_satuan, subtotal});
            renderDetail();
            document.getElementById('jumlah').value = '';
            document.getElementById('harga_satuan').value = '';
        }

        function renderDetail() {
            let tbody = '';
            let subtotalAll = 0;
            detailList.forEach((d, i) => {
                subtotalAll += d.subtotal;
                tbody += `<tr>
                    <td>${i+1}</td>
                    <td>
                        <input type="hidden" name="detail[${i}][idbarang]" value="${d.idbarang}">
                        ${d.nama_barang}
                    </td>
                    <td>
                        <input type="hidden" name="detail[${i}][jumlah]" value="${d.jumlah}">
                        ${d.jumlah}
                    </td>
                    <td>
                        <input type="hidden" name="detail[${i}][harga_satuan]" value="${d.harga_satuan}">
                        Rp ${d.harga_satuan.toLocaleString()}
                    </td>
                    <td>
                        <input type="hidden" name="detail[${i}][subtotal]" value="${d.subtotal}">
                        <strong>Rp ${d.subtotal.toLocaleString()}</strong>
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm" onclick="removeDetail(${i})"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>`;
            });
            document.getElementById('detail-body').innerHTML = tbody;
            document.getElementById('subtotal_nilai').value = subtotalAll;
            document.getElementById('subtotal_nilai_view').innerText = 'Rp ' + subtotalAll.toLocaleString();

            // Hitung total
            const ppn = parseInt(document.getElementById('ppn').value) || 0;
            const total = subtotalAll + Math.round(subtotalAll * ppn / 100);
            document.getElementById('total_nilai').value = total;
            document.getElementById('total_nilai_view').innerText = 'Rp ' + total.toLocaleString();
        }

        function removeDetail(idx) {
            detailList.splice(idx, 1);
            renderDetail();
        }

        function updateTotal() {
            renderDetail();
        }
        // Validasi sebelum submit agar detail tidak kosong
        document.addEventListener('DOMContentLoaded', function() {
            var form = document.querySelector('form');
            form.addEventListener('submit', function(e) {
                if (detailList.length === 0) {
                    alert('Tambahkan minimal satu barang ke daftar!');
                    e.preventDefault();
                }
            });
        });
    </script>
</head>
<body>
<div class="container mt-5">
    <div class="card p-4">
        <h4 class="mb-3">Tambah Penjualan</h4>
        <form method="post" autocomplete="off">
            <input type="hidden" name="iduser" value="<?= $iduser ?>">
            <input type="hidden" name="idmargin_penjualan" value="<?= $idmargin_penjualan ?>">

            <div class="row mb-3">
                <div class="col-md-3">
                    <label>Margin Penjualan Aktif</label>
                    <input type="text" class="form-control" value="<?= $persen_margin ?> %" readonly>
                </div>
                <div class="col-md-3">
                    <label>PPN (%)</label>
                    <input type="number" id="ppn" name="ppn" class="form-control" value="11" min="0" onchange="updateTotal()" required>
                </div>
                <div class="col-md-3">
                    <label>Subtotal</label>
                    <input type="hidden" id="subtotal_nilai" name="subtotal_nilai" value="0">
                    <div class="form-control-plaintext" id="subtotal_nilai_view">Rp 0</div>
                </div>
                <div class="col-md-3">
                    <label>Total</label>
                    <input type="hidden" id="total_nilai" name="total_nilai" value="0">
                    <div class="form-control-plaintext fw-bold" id="total_nilai_view">Rp 0</div>
                </div>
            </div>

            <hr>
            <h6>Tambah Barang</h6>
            <div class="row g-2 align-items-end mb-2">
                <div class="col-md-4">
                    <label>Barang</label>
                    <select id="idbarang" class="form-select">
                        <option value="">- Pilih Barang -</option>
                        <?php foreach ($barangList as $b): ?>
                            <option value="<?= $b['idbarang'] ?>" data-harga="<?= $b['harga'] ?>">
                                <?= htmlspecialchars($b['nama']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label>Jumlah</label>
                    <input type="number" id="jumlah" class="form-control" min="1">
                </div>
                <div class="col-md-3">
                    <label>Harga Satuan</label>
                    <input type="number" id="harga_satuan" class="form-control" min="0">
                </div>
                <div class="col-md-3">
                    <button type="button" class="btn btn-success w-100" onclick="addDetail()">
                        <i class="fas fa-plus"></i> Tambah ke Daftar
                    </button>
                </div>
            </div>

            <div class="table-responsive mt-3">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Barang</th>
                            <th>Jumlah</th>
                            <th>Harga Satuan</th>
                            <th>Subtotal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="detail-body">
                        <tr><td colspan="6" class="text-center text-muted">Belum ada barang.</td></tr>
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Simpan Penjualan</button>
                <a href="index.php" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
<script src="https://kit.fontawesome.com/4e8e2e6e5b.js" crossorigin="anonymous"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var selectBarang = document.getElementById('idbarang');
    var hargaInput = document.getElementById('harga_satuan');
    if (selectBarang && hargaInput) {
        selectBarang.addEventListener('change', function() {
            var selected = this.options[this.selectedIndex];
            var harga = selected.getAttribute('data-harga') || '';
            hargaInput.value = harga;
        });
    }
});
</script>
</body>
</html>