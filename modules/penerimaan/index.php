<?php
include '../../config/database.php';
include '../../includes/functions.php';

session_start();
check_login();

$query = "SELECT * FROM penerimaan";
$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penerimaan Records</title>
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <?php include '../../includes/header.php'; ?>
    <?php include '../../includes/sidebar.php'; ?>

    <div class="container mt-4">
        <h2>Penerimaan Records</h2>
        <a href="tambah.php" class="btn btn-primary mb-3">Tambah Penerimaan</a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID Penerimaan</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo $row['idpenerimaan']; ?></td>
                        <td><?php echo $row['created_at']; ?></td>
                        <td><?php echo $row['status']; ?></td>
                        <td>
                            <a href="detail.php?id=<?php echo $row['idpenerimaan']; ?>" class="btn btn-info">Detail</a>
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