<?php
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Resepsionis</title>
    <link rel="stylesheet" type="text/css" href="../../css/dashboard_resepsionis.css">
</head>
<body>
    <div class="header">
        <div class="logo"></div>
        <h2>Halo, <?= htmlspecialchars($_SESSION['nama'] ?? 'Resepsionis'); ?></h2>
        <div class="nav">
            <a href="../../auth/logout.php">Logout</a>
        </div>
    </div>
    <div class="content">
        <h3>Selamat datang di halaman Resepsionis.</h3>
        <p>Pilih menu di bawah untuk melakukan registrasi atau proses temu dokter.</p>
        <div class="menu-container">
            <div class="menu-card">
                <div class="icon">&#128100;</div>
                <h4>Registrasi Pemilik</h4>
                <p>Daftarkan pemilik baru agar dapat menggunakan layanan klinik.</p>
                <a href="registrasi_pemilik.php">Registrasi Pemilik</a>
            </div>  
            <div class="menu-card">
                <div class="icon">&#128054;</div>
                <h4>Registrasi Pet</h4>
                <p>Daftarkan hewan peliharaan milik pemilik yang sudah terdaftar.</p>
                <a href="registrasi_pet.php">Registrasi Pet</a>
            </div>
            <div class="menu-card menu-dokter">
                <div class="icon">&#128106;</div>
                <h4>Temu Dokter</h4>
                <p>Proses pendaftaran untuk bertemu dokter sesuai urutan waktu daftar.</p>
                <a href="temu_dokter.php">Temu Dokter</a>
            </div>
        </div>
    </div>
</body>
</html>