<?php
session_start();

require_once '../../../classes/Auth.php';
require_once '../../../classes/Database.php';

$auth = new Auth();
$auth->checkRole([1]); // hanya admin

$db = new Database();

// Ambil daftar vendor
$vendorList = $db->fetchAll("SELECT idvendor, nama_vendor FROM vendor WHERE status = 1 ORDER BY nama_vendor ASC");

// Tangani POST (insert pengadaan)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $idvendor = $_POST['idvendor'];
        $ppn = $_POST['ppn'] ?? 0;
        $iduser = $auth->getUserId();

        // Insert ke tabel pengadaan
        $db->execute("
    INSERT INTO pengadaan (idvendor, iduser, status)
    VALUES (?, ?, 'P')
", [$idvendor, $iduser]);

        $idpengadaan = $db->lastInsertId();

        header("Location: detail.php?idpengadaan=" . $idpengadaan);
        exit;


    } catch (Exception $e) {
        $error = "Gagal menambah pengadaan: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Tambah Pengadaan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', sans-serif;
        }

        .navbar {
            background: linear-gradient(135deg, #667eea, #764ba2);
        }

        .card {
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .header-card {
            background: linear-gradient(135deg, #667eea, #764ba2);
            padding: 18px 22px;
            border-radius: 12px;
            margin-bottom: 20px;
            color: white;
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-dark p-3">
        <div class="container-fluid">
            <span class="navbar-brand fw-bold">
                <i class="fas fa-plus-circle me-2"></i>Tambah Pengadaan
            </span>
            <a href="index.php" class="btn btn-light btn-sm">
                <i class="fas fa-arrow-left me-1"></i>Kembali
            </a>
        </div>
    </nav>

    <div class="container mt-4" style="max-width: 700px;">

        <div class="header-card">
            <h4 class="mb-0"><i class="fas fa-file-invoice me-2"></i>Form Tambah Pengadaan</h4>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <div class="card p-4">
            <form method="POST">

                <div class="mb-3">
                    <label class="form-label fw-semibold">Vendor</label>
                    <select name="idvendor" class="form-select" required>
                        <option value="">-- Pilih Vendor --</option>
                        <?php foreach ($vendorList as $v): ?>
                            <option value="<?= $v['idvendor'] ?>">
                                <?= htmlspecialchars($v['nama_vendor']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary w-100 fw-semibold">
                    <i class="fas fa-save me-1"></i> Simpan & Lanjut ke Detail
                </button>
            </form>
        </div>

    </div>

</body>

</html>