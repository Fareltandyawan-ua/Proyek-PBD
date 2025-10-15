<?php
include '../../config/database.php';
include '../../includes/functions.php';

session_start();
check_login();

$query = "SELECT p.idpenjualan, p.created_at, p.subtotal_nilai, p.ppn, p.total_nilai, u.username 
          FROM penjualan p 
          JOIN user u ON p.iduser = u.iduser 
          ORDER BY p.created_at DESC";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penjualan - Daftar Penjualan</title>
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <?php include '../../includes/header.php'; ?>
    <?php include '../../includes/sidebar.php'; ?>

    <div class="container mt-4">
        <h1>Daftar Penjualan</h1>
        <a href="tambah.php" class="btn btn-primary mb-3">Tambah Penjualan</a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID Penjualan</th>
                    <th>Tanggal</th>
                    <th>Subtotal</th>
                    <th>PPN</th>
                    <th>Total</th>
                    <th>Pengguna</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo $row['idpenjualan']; ?></td>
                        <td><?php echo $row['created_at']; ?></td>
                        <td><?php echo number_format($row['subtotal_nilai'], 2); ?></td>
                        <td><?php echo number_format($row['ppn'], 2); ?></td>
                        <td><?php echo number_format($row['total_nilai'], 2); ?></td>
                        <td><?php echo $row['username']; ?></td>
                        <td>
                            <a href="detail.php?id=<?php echo $row['idpenjualan']; ?>" class="btn btn-info">Detail</a>
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