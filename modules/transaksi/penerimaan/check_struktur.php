<?php
session_start();
require_once '../../../classes/Auth.php';
require_once '../../../classes/Database.php';

$auth = new Auth();
$auth->checkRole([1]);

$db = Database::getInstance();
$conn = $db->getConnection();

// Daftar tabel yang perlu dicek
$tables = [
    'penerimaan',
    'detail_penerimaan',
    'pengadaan',
    'detail_pengadaan',
    'kartu_stok',
    'barang'
];

?>
<!DOCTYPE html>
<html>
<head>
    <title>Cek Struktur Database</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .table-name {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 10px 15px;
            border-radius: 8px;
            margin-top: 20px;
        }
        .column-info {
            font-family: 'Courier New', monospace;
            font-size: 13px;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container mt-4">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">🔍 Struktur Database - Modul Penerimaan</h5>
            </div>
            <div class="card-body">
                <p class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Halaman ini menampilkan struktur tabel yang digunakan oleh modul penerimaan.
                    Pastikan semua kolom sesuai dengan yang dibutuhkan.
                </p>
            </div>
        </div>

        <?php foreach ($tables as $tableName): ?>
            
            <div class="table-name">
                📋 Tabel: <strong><?= strtoupper($tableName) ?></strong>
            </div>

            <div class="card mb-4">
                <div class="card-body">
                    <?php
                    try {
                        $stmt = $conn->prepare("DESCRIBE $tableName");
                        $stmt->execute();
                        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        
                        if (!empty($columns)):
                    ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover column-info">
                                <thead class="table-light">
                                    <tr>
                                        <th>Field</th>
                                        <th>Type</th>
                                        <th>Null</th>
                                        <th>Key</th>
                                        <th>Default</th>
                                        <th>Extra</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($columns as $col): ?>
                                        <tr>
                                            <td><strong><?= $col['Field'] ?></strong></td>
                                            <td><?= $col['Type'] ?></td>
                                            <td><?= $col['Null'] ?></td>
                                            <td>
                                                <?php if ($col['Key']): ?>
                                                    <span class="badge bg-primary"><?= $col['Key'] ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= $col['Default'] ?? 'NULL' ?></td>
                                            <td><?= $col['Extra'] ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Analisis Khusus -->
                        <?php if ($tableName === 'detail_penerimaan'): ?>
                            <div class="alert alert-info mt-3">
                                <strong>✅ Checklist untuk detail_penerimaan:</strong>
                                <ul class="mb-0 mt-2">
                                    <li>
                                        <?= in_array('idpenerimaan', array_column($columns, 'Field')) 
                                            ? '✅ idpenerimaan ada' 
                                            : '❌ idpenerimaan TIDAK ADA' ?>
                                    </li>
                                    <li>
                                        <?= in_array('idbarang', array_column($columns, 'Field')) 
                                            ? '✅ idbarang ada' 
                                            : '❌ idbarang TIDAK ADA' ?>
                                    </li>
                                    <li>
                                        <?= in_array('jumlah_terima', array_column($columns, 'Field')) 
                                            ? '✅ jumlah_terima ada' 
                                            : '❌ jumlah_terima TIDAK ADA (PENTING!)' ?>
                                    </li>
                                    <li>
                                        <?= in_array('harga_satuan_terima', array_column($columns, 'Field')) 
                                            ? '✅ harga_satuan_terima ada' 
                                            : '❌ harga_satuan_terima TIDAK ADA (PENTING!)' ?>
                                    </li>
                                    <li>
                                        <?= in_array('sub_total_terima', array_column($columns, 'Field')) 
                                            ? '✅ sub_total_terima ada' 
                                            : '❌ sub_total_terima TIDAK ADA (PENTING!)' ?>
                                    </li>
                                    <li>
                                        <?= in_array('iddetail_pengadaan', array_column($columns, 'Field')) 
                                            ? '⚠️ iddetail_pengadaan ada (TIDAK DIPERLUKAN)' 
                                            : '✅ iddetail_pengadaan tidak ada (BENAR)' ?>
                                    </li>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <?php if ($tableName === 'kartu_stok'): ?>
                            <div class="alert alert-info mt-3">
                                <strong>✅ Checklist untuk kartu_stok:</strong>
                                <ul class="mb-0 mt-2">
                                    <li>
                                        <?= in_array('jenis_transaksi', array_column($columns, 'Field')) 
                                            ? '✅ jenis_transaksi ada' 
                                            : '❌ jenis_transaksi TIDAK ADA' ?>
                                    </li>
                                    <li>
                                        <?= in_array('masuk', array_column($columns, 'Field')) 
                                            ? '✅ masuk ada' 
                                            : '❌ masuk TIDAK ADA' ?>
                                    </li>
                                    <li>
                                        <?= in_array('keluar', array_column($columns, 'Field')) 
                                            ? '✅ keluar ada' 
                                            : '❌ keluar TIDAK ADA' ?>
                                    </li>
                                    <li>
                                        <?= in_array('stock', array_column($columns, 'Field')) 
                                            ? '✅ stock ada' 
                                            : '❌ stock TIDAK ADA (PENTING!)' ?>
                                    </li>
                                    <li>
                                        <?= in_array('idtransaksi', array_column($columns, 'Field')) 
                                            ? '✅ idtransaksi ada' 
                                            : '❌ idtransaksi TIDAK ADA' ?>
                                    </li>
                                    <li>
                                        <?= in_array('idbarang', array_column($columns, 'Field')) 
                                            ? '✅ idbarang ada' 
                                            : '❌ idbarang TIDAK ADA' ?>
                                    </li>
                                </ul>
                            </div>
                        <?php endif; ?>

                    <?php 
                        else:
                            echo '<div class="alert alert-warning">Tabel kosong atau tidak ada data.</div>';
                        endif;
                    } catch (Exception $e) {
                        echo '<div class="alert alert-danger">Error: ' . $e->getMessage() . '</div>';
                    }
                    ?>
                </div>
            </div>

        <?php endforeach; ?>

        <div class="card bg-light">
            <div class="card-body">
                <h6 class="fw-bold">📝 Catatan Penting:</h6>
                <ul class="mb-0">
                    <li>Jika ada kolom yang tidak sesuai, Anda perlu mengubah struktur tabel dengan ALTER TABLE</li>
                    <li>Kolom <code>iddetail_pengadaan</code> di <code>detail_penerimaan</code> TIDAK DIPERLUKAN</li>
                    <li>Pastikan semua kolom yang ditandai PENTING sudah ada</li>
                </ul>
            </div>
        </div>

        <div class="mt-4 mb-4">
            <a href="index.php" class="btn btn-secondary">Kembali ke Penerimaan</a>
        </div>

    </div>
</body>
</html>