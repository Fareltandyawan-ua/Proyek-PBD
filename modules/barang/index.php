<?php
session_start();
include '../../config/database.php';
include '../../includes/functions.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Fetch barang data from the database
$query = "SELECT b.idbarang, b.nama, b.jenis, s.nama_satuan, b.status 
          FROM barang b 
          JOIN satuan s ON b.idsatuan = s.idsatuan 
          ORDER BY b.idbarang ASC";
$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Barang</title>
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <?php include '../../includes/header.php'; ?>
    <?php include '../../includes/sidebar.php'; ?>

    <div class="container mt-4">
        <h2>Data Barang</h2>
        <a href="tambah.php" class="btn btn-primary mb-3">Tambah Barang</a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID Barang</th>
                    <th>Nama Barang</th>
                    <th>Jenis</th>
                    <th>Satuan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo $row['idbarang']; ?></td>
                        <td><?php echo $row['nama']; ?></td>
                        <td><?php echo $row['jenis']; ?></td>
                        <td><?php echo $row['nama_satuan']; ?></td>
                        <td><?php echo $row['status'] ? 'Aktif' : 'Tidak Aktif'; ?></td>
                        <td>
                            <a href="edit.php?id=<?php echo $row['idbarang']; ?>" class="btn btn-warning">Edit</a>
                            <a href="hapus.php?id=<?php echo $row['idbarang']; ?>" class="btn btn-danger">Hapus</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <?php include '../../includes/footer.php'; ?>
    <script src="../../assets/js/jquery.min.js"></script>
    <script src="../../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../../assets/js/main.js"></script>
</body>
</html>