<?php
require_once '../../../classes/Auth.php';
require_once '../../../classes/Database.php';

$auth = new Auth();
$auth->checkRole([1]);

$db = new Database();

if (!isset($_GET['idpengadaan'])) {
    die("ID Pengadaan tidak ditemukan!");
}

$idpengadaan = $_GET['idpengadaan'];

// Ambil data pengadaan
$pengadaan = $db->fetch("
    SELECT p.*, v.nama_vendor 
    FROM pengadaan p
    JOIN vendor v ON p.idvendor = v.idvendor
    WHERE p.idpengadaan = ?
", [$idpengadaan]);

if (!$pengadaan) {
    die("Data pengadaan tidak ditemukan!");
}

// Ambil vendor aktif saja
$vendorList = $db->fetchAll("SELECT idvendor, nama_vendor FROM vendor WHERE status='1' ORDER BY nama_vendor ASC");

// Proses update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idvendor = $_POST['idvendor'];
    $status = $_POST['status'];

    $db->execute("
        UPDATE pengadaan SET 
            idvendor = ?, 
            status = ?
        WHERE idpengadaan = ?
    ", [$idvendor, $status, $idpengadaan]);

    header("Location: index.php?success=updated");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Pengadaan</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f3f4f6;
            font-family: 'Segoe UI';
        }

        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .form-card {
            background: #fff;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }
    </style>
</head>
<body>

<nav class="navbar navbar-dark px-3">
    <span class="navbar-brand fw-bold"><i class="fas fa-edit me-2"></i>Edit Pengadaan</span>
    <a href="index.php" class="btn btn-light btn-sm"><i class="fas fa-arrow-left me-1"></i> Kembali</a>
</nav>

<div class="container mt-4">
    <div class="form-card">

        <h4 class="mb-4"><i class="fas fa-file-alt me-2"></i>Form Edit Pengadaan</h4>

        <form method="POST">

            <div class="mb-3">
                <label class="form-label">Vendor</label>
                <select name="idvendor" required class="form-select">
                    <?php foreach ($vendorList as $v): ?>
                        <option value="<?= $v['idvendor'] ?>" 
                            <?= $v['idvendor'] == $pengadaan['idvendor'] ? 'selected' : '' ?>>
                            <?= $v['nama_vendor'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Status Pengadaan</label>
                <select name="status" class="form-select">
                    <option value="P" <?= $pengadaan['status']=='P'?'selected':'' ?>>Proses</option>
                    <option value="S" <?= $pengadaan['status']=='S'?'selected':'' ?>>Selesai</option>
                </select>
            </div>

            <button class="btn btn-primary">
                <i class="fas fa-save me-1"></i> Simpan Perubahan
            </button>
        </form>

    </div>
</div>

</body>
</html>
