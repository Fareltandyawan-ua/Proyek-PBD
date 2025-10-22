<?php
session_start();
require_once '../../../classes/Auth.php';
$auth = new Auth();
$auth->checkRole([2]); // role 2 = superadmin

$userData = $auth->getUserData();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Superadmin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    /* Navbar */
    .navbar {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      box-shadow: 0 3px 10px rgba(0, 0, 0, 0.15);
      z-index: 1050;
    }

    /* Sidebar */
    .sidebar {
      background: #fff;
      width: 250px;
      position: fixed;
      top: 56px; /* di bawah navbar */
      bottom: 0;
      left: 0;
      box-shadow: 2px 0 8px rgba(0, 0, 0, 0.1);
      padding-top: 20px;
    }

    .sidebar a {
      color: #495057;
      display: block;
      padding: 10px 20px;
      border-radius: 8px;
      margin: 5px 15px;
      text-decoration: none;
      font-weight: 500;
      transition: all 0.3s ease;
    }

    .sidebar a:hover,
    .sidebar a.active {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
    }

    /* Main Content */
    .main-content {
      margin-left: 250px;
      padding: 30px;
      margin-top: 70px;
    }

    .header-card {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      border-radius: 12px;
      padding: 20px 25px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      margin-bottom: 25px;
    }

    .card {
      border: none;
      border-radius: 12px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
    }

    .info-card {
      border: none;
      border-radius: 12px;
      box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
      background: white;
      padding: 20px;
      text-align: center;
      transition: all 0.3s ease;
    }

    .info-card:hover {
      transform: translateY(-5px);
    }

    .info-icon {
      font-size: 2rem;
      color: #667eea;
      margin-bottom: 10px;
    }

    @media (max-width: 768px) {
      .sidebar {
        display: none;
      }

      .main-content {
        margin-left: 0;
      }
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-dark fixed-top">
  <div class="container-fluid">
    <span class="navbar-brand fw-bold"><i class="fas fa-crown me-2"></i>Dashboard Superadmin</span>
    <div>
      <span class="text-white me-3"><i class="fas fa-user-shield me-1"></i><?= htmlspecialchars($userData['username']) ?></span>
      <a href="../../auth/logout.php" class="btn btn-light btn-sm">
        <i class="fas fa-sign-out-alt me-1"></i>Logout
      </a>
    </div>
  </div>
</nav>

<!-- Sidebar -->
<div class="sidebar">
  <a href="index.php" class="active"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a>
  <a href="../../user/index.php"><i class="fas fa-users me-2"></i>Kelola User</a>
  <a href="../../role/index.php"><i class="fas fa-user-tag me-2"></i>Kelola Role</a>
</div>

<!-- Main Content -->
<div class="main-content">
  <div class="header-card">
    <h4 class="mb-1">Selamat datang, <strong><?= htmlspecialchars($userData['username']) ?></strong> 👋</h4>
    <p class="mb-0 text-white-75">Anda login sebagai <strong><?= htmlspecialchars($userData['role_name']) ?></strong></p>
    <small class="opacity-75">Dashboard Superadmin - <?= date('d F Y') ?></small>
  </div>

  <!-- Statistik / Info Cards -->
  <div class="row g-4">
    <div class="col-md-4">
      <div class="info-card">
        <i class="fas fa-users info-icon"></i>
        <h5>Manajemen User</h5>
        <p class="text-muted mb-0">Tambah, ubah, dan kelola akun pengguna</p>
      </div>
    </div>
    <div class="col-md-4">
      <div class="info-card">
        <i class="fas fa-user-tag info-icon"></i>
        <h5>Manajemen Role</h5>
        <p class="text-muted mb-0">Atur hak akses dan peran sistem</p>
      </div>
    </div>
    <div class="col-md-4">
      <div class="info-card">
        <i class="fas fa-chart-line info-icon"></i>
        <h5>Aktivitas Sistem</h5>
        <p class="text-muted mb-0">Pantau log dan kinerja aplikasi</p>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
