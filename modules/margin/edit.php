<?php
include '../../config/database.php';
include '../../includes/functions.php';

if (!isset($_GET['id'])) {
    exit('ID tidak ditemukan');
}
$id = $_GET['id'];

// Ambil data margin_penjualan
$stmt = $pdo->prepare("SELECT * FROM margin_penjualan WHERE idmargin_penjualan = ?");
$stmt->execute([$id]);
$margin = $stmt->fetch();

if (!$margin) {
    exit('Data margin tidak ditemukan!');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $persen = $_POST['persen'];
    $iduser = $_POST['iduser']; // Pastikan ambil dari session atau input

    $stmt = $pdo->prepare("UPDATE margin_penjualan SET persen = ?, status = ?, iduser = ?, updated_at = NOW() WHERE idmargin_penjualan = ?");
    $stmt->execute([$persen, $status, $iduser, $id]);
    header("Location: index.php?success=updated");
    exit;
}

// Ambil user untuk dropdown (opsional)
$stmtUser = $pdo->query("SELECT iduser, username FROM user");
$userList = $stmtUser->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Margin Penjualan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <div class="card p-4">
        <h4 class="mb-3">Edit Margin Penjualan</h4>
        <form method="post">
            <div class="mb-3">
                <label>Persentase Margin (%)</label>
                <input type="number" name="persen" class="form-control" value="<?= htmlspecialchars($margin['persen']) ?>" required>
            </div>
            <div class="mb-3">
                <label>Status</label>
                <select name="status" class="form-select" required>
                    <option value="1" <?= $margin['status'] == 1 ? 'selected' : '' ?>>Aktif</option>
                    <option value="0" <?= $margin['status'] == 0 ? 'selected' : '' ?>>Tidak Aktif</option>
                </select>
            </div>
            <div class="mb-3">
                <label>User</label>
                <select name="iduser" class="form-select" required>
                    <?php foreach ($userList as $u): ?>
                        <option value="<?= $u['iduser'] ?>" <?= $margin['iduser'] == $u['iduser'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($u['username']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="index.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
</body>
</html>