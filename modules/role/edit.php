<?php
require_once '../../classes/Auth.php';
require_once '../../classes/Database.php';

$auth = new Auth();
$auth->checkRole([2]);

$db = new Database();
$id = $_GET['id'] ?? 0;
$role = $db->fetch("SELECT * FROM role WHERE idrole = ?", [$id]);
if (!$role) die("Data tidak ditemukan");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Role - Superadmin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <style>
    body { background-color: #f8f9fa; }
    .card { border: none; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.08); }
  </style>
</head>
<body>
<div class="container mt-5">
  <div class="card p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 class="mb-0"><i class="fas fa-pen me-2 text-warning"></i>Edit Role</h4>
      <a href="index.php" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left me-1"></i>Kembali</a>
    </div>
    <hr>

    <form action="process.php?action=update" method="POST">
      <input type="hidden" name="idrole" value="<?= $role['idrole'] ?>">

      <div class="mb-3">
        <label class="form-label fw-semibold">Nama Role</label>
        <input type="text" name="nama_role" class="form-control" value="<?= htmlspecialchars($role['nama_role']) ?>" required>
      </div>

      <div class="text-end">
        <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save me-2"></i>Update</button>
      </div>
    </form>
  </div>
</div>
</body>
</html>
