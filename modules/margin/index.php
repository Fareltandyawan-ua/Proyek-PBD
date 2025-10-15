<?php
include '../../config/database.php';
include '../../includes/functions.php';

session_start();
check_login();

$query = "SELECT * FROM margin_penjualan";
$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Margin Records</title>
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <?php include '../../includes/header.php'; ?>
    <?php include '../../includes/sidebar.php'; ?>

    <div class="container mt-4">
        <h2>Margin Records</h2>
        <a href="tambah.php" class="btn btn-primary mb-3">Add New Margin</a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Created At</th>
                    <th>Percentage</th>
                    <th>Status</th>
                    <th>User ID</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo $row['idmargin_penjualan']; ?></td>
                        <td><?php echo $row['created_at']; ?></td>
                        <td><?php echo $row['persen']; ?></td>
                        <td><?php echo $row['status']; ?></td>
                        <td><?php echo $row['iduser']; ?></td>
                        <td>
                            <a href="edit.php?id=<?php echo $row['idmargin_penjualan']; ?>" class="btn btn-warning">Edit</a>
                            <a href="hapus.php?id=<?php echo $row['idmargin_penjualan']; ?>" class="btn btn-danger">Delete</a>
                        </td>
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