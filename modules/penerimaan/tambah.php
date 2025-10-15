<?php
include '../../config/database.php';
include '../../includes/functions.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idpenerimaan = $_POST['idpenerimaan'];
    $idpengadaan = $_POST['idpengadaan'];
    $created_at = date('Y-m-d H:i:s');
    $status = $_POST['status'];

    $query = "INSERT INTO penerimaan (idpenerimaan, created_at, status, idpengadaan, iduser) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("issii", $idpenerimaan, $created_at, $status, $idpengadaan, $_SESSION['user_id']);

    if ($stmt->execute()) {
        header("Location: index.php?message=Data berhasil ditambahkan");
        exit();
    } else {
        $error = "Error: " . $stmt->error;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Penerimaan</title>
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <?php include '../../includes/header.php'; ?>
    <?php include '../../includes/sidebar.php'; ?>

    <div class="container mt-5">
        <h2>Tambah Penerimaan</h2>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error; ?></div>
        <?php endif; ?>
        <form action="" method="POST">
            <div class="mb-3">
                <label for="idpenerimaan" class="form-label">ID Penerimaan</label>
                <input type="number" class="form-control" id="idpenerimaan" name="idpenerimaan" required>
            </div>
            <div class="mb-3">
                <label for="idpengadaan" class="form-label">ID Pengadaan</label>
                <input type="number" class="form-control" id="idpengadaan" name="idpengadaan" required>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status" required>
                    <option value="A">Aktif</option>
                    <option value="T">Tidak Aktif</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="index.php" class="btn btn-secondary">Kembali</a>
        </form>
    </div>

    <?php include '../../includes/footer.php'; ?>
    <script src="../../assets/js/jquery.min.js"></script>
    <script src="../../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>