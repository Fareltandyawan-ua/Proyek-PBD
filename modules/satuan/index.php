<?php
include '../../config/database.php';
include '../../includes/functions.php';

session_start();
check_login();

$query = "SELECT * FROM satuan";
$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <title>Data Satuan</title>
</head>
<body>
    <?php include '../../includes/header.php'; ?>
    <?php include '../../includes/sidebar.php'; ?>

    <div class="container mt-4">
        <h2>Data Satuan</h2>
        <a href="tambah.php" class="btn btn-primary mb-3">Tambah Satuan</a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID Satuan</th>
                    <th>Nama Satuan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo $row['idsatuan']; ?></td>
                        <td><?php echo $row['nama_satuan']; ?></td>
                        <td><?php echo $row['status'] ? 'Aktif' : 'Tidak Aktif'; ?></td>
                        <td>
                            <a href="edit.php?id=<?php echo $row['idsatuan']; ?>" class="btn btn-warning">Edit</a>
                            <a href="hapus.php?id=<?php echo $row['idsatuan']; ?>" class="btn btn-danger">Hapus</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <?php include '../../includes/footer.php'; ?>
    <script src="../../assets/js/jquery.min.js"></script>
    <script src="../../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>