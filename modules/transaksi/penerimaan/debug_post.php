<?php
session_start();
require_once '../../../classes/Auth.php';

$auth = new Auth();
$auth->checkRole([1]);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Debug POST Data</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        pre {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #dee2e6;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container mt-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">🔍 Debug POST Data</h5>
            </div>
            <div class="card-body">
                
                <h6 class="fw-bold">Data POST yang diterima:</h6>
                <pre><?php print_r($_POST); ?></pre>

                <hr>

                <h6 class="fw-bold">Data SESSION:</h6>
                <pre><?php print_r($_SESSION); ?></pre>

                <hr>

                <h6 class="fw-bold">Analisis Data Barang:</h6>
                <?php if (isset($_POST['barang'])): ?>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID Barang</th>
                                <th>Jumlah Terima</th>
                                <th>Harga Satuan</th>
                                <th>Tipe Data</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($_POST['barang'] as $idbarang => $data): ?>
                                <tr>
                                    <td><?= $idbarang ?></td>
                                    <td><?= $data['jumlah_terima'] ?? 'TIDAK ADA' ?></td>
                                    <td><?= $data['harga_satuan'] ?? 'TIDAK ADA' ?></td>
                                    <td>
                                        jumlah_terima: <?= gettype($data['jumlah_terima'] ?? null) ?><br>
                                        harga_satuan: <?= gettype($data['harga_satuan'] ?? null) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="alert alert-warning">Tidak ada data barang</div>
                <?php endif; ?>

                <hr>

                <a href="tambah.php" class="btn btn-secondary">Kembali ke Form</a>
                
            </div>
        </div>
    </div>
</body>
</html>