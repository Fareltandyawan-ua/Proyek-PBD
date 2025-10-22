<?php
require_once '../../classes/Auth.php';
require_once '../../classes/Database.php';

$auth = new Auth();
$auth->checkRole([2]);

$db = new Database();
$id = $_GET['id'] ?? 0;
$user = $db->fetch("SELECT * FROM user WHERE iduser = ?", [$id]);
$roles = $db->fetchAll("SELECT * FROM role ORDER BY idrole ASC");

if (!$user) die("Data user tidak ditemukan.");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit User - Superadmin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <style>
    body { background-color: #f8f9fa; }
    .card { border: none; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.08); }
    .btn-gradient { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; }
  </style>
</head>
<body>
<div class="container mt-5">
  <div class="card p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 class="mb-0"><i class="fas fa-user-edit me-2 text-warning"></i>Edit User</h4>
      <a href="index.php" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left me-1"></i>Kembali</a>
    </div>
    <hr>

    <form action="process.php?action=update" method="POST">
      <input type="hidden" name="iduser" value="<?= $user['iduser'] ?>">

      <div class="mb-3">
        <label class="form-label fw-semibold">Username</label>
        <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($user['username']) ?>" required>
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold">Password (opsional)</label>
        <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah">
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold">Role</label>
        <select name="idrole" class="form-select" required>
          <?php foreach ($roles as $r): ?>
            <option value="<?= $r['idrole'] ?>" <?= ($r['idrole'] == $user['idrole']) ? 'selected' : '' ?>>
              <?= htmlspecialchars($r['nama_role']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="text-end">
        <button type="submit" class="btn btn-gradient px-4"><i class="fas fa-save me-2"></i>Update</button>
      </div>
    </form>
  </div>
</div>
</body>
</html>
