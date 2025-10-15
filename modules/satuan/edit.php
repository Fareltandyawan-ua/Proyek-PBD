<?php
include '../../config/database.php';
include '../../includes/functions.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: index.php');
    exit;
}

$query = "SELECT * FROM satuan WHERE idsatuan = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$satuan = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_satuan = $_POST['nama_satuan'];
    $status = $_POST['status'];

    $updateQuery = "UPDATE satuan SET nama_satuan = ?, status = ? WHERE idsatuan = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param("ssi", $nama_satuan, $status, $id);

    if ($updateStmt->execute()) {
        header('Location: index.php?message=Data berhasil diupdate');
        exit;
    } else {
        $error = "Gagal mengupdate data: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <title>Edit Satuan</title>
</head>
<body>
    <?php include '../../includes/header.php'; ?>
    <?php include '../../includes/sidebar.php'; ?>

    <div class="container mt-5">
        <h2>Edit Satuan</h2>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error; ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-3">
                <label for="nama_satuan" class="form-label">Nama Satuan</label>
                <input type="text" class="form-control" id="nama_satuan" name="nama_satuan" value="<?= htmlspecialchars($satuan['nama_satuan']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status" required>
                    <option value="1" <?= $satuan['status'] == 1 ? 'selected' : ''; ?>>Aktif</option>
                    <option value="0" <?= $satuan['status'] == 0 ? 'selected' : ''; ?>>Tidak Aktif</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="index.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>

    <?php include '../../includes/footer.php'; ?>
    <script src="../../assets/js/jquery.min.js"></script>
    <script src="../../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../../assets/js/main.js"></script>
</body>
</html>