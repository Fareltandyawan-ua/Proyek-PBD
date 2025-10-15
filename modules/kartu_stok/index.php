<?php
include '../../config/database.php';
include '../../includes/functions.php';

// Fetch kartu_stok data from the database
$query = "SELECT * FROM kartu_stok";
$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <title>Kartu Stok</title>
</head>
<body>
    <?php include '../../includes/header.php'; ?>
    <?php include '../../includes/sidebar.php'; ?>

    <div class="container mt-4">
        <h2>Kartu Stok</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID Kartu Stok</th>
                    <th>Jenis Transaksi</th>
                    <th>Masuk</th>
                    <th>Keluar</th>
                    <th>Stock</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo $row['idkartu_stok']; ?></td>
                        <td><?php echo $row['jenis_transaksi']; ?></td>
                        <td><?php echo $row['masuk']; ?></td>
                        <td><?php echo $row['keluar']; ?></td>
                        <td><?php echo $row['stock']; ?></td>
                        <td><?php echo $row['created_at']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <?php include '../../includes/footer.php'; ?>
    <script src="../../assets/js/jquery.min.js"></script>
    <script src="../../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../../assets/js/main.js"></script>
</body>
</html>