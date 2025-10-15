<?php
include '../../config/database.php';
include '../../includes/functions.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$idpenerimaan = isset($_GET['id']) ? intval($_GET['id']) : 0;

$query = "SELECT * FROM penerimaan WHERE idpenerimaan = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $idpenerimaan);
$stmt->execute();
$result = $stmt->get_result();

$penerimaan = $result->fetch_assoc();

if (!$penerimaan) {
    echo "Data penerimaan tidak ditemukan.";
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Penerimaan</title>
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <?php include '../../includes/header.php'; ?>
    <?php include '../../includes/sidebar.php'; ?>

    <div class="container mt-5">
        <h2>Detail Penerimaan</h2>
        <table class="table table-bordered">
            <tr>
                <th>ID Penerimaan</th>
                <td><?php echo $penerimaan['idpenerimaan']; ?></td>
            </tr>
            <tr>
                <th>Tanggal Penerimaan</th>
                <td><?php echo $penerimaan['created_at']; ?></td>
            </tr>
            <tr>
                <th>Status</th>
                <td><?php echo $penerimaan['status']; ?></td>
            </tr>
            <tr>
                <th>ID Pengadaan</th>
                <td><?php echo $penerimaan['idpengadaan']; ?></td>
            </tr>
            <tr>
                <th>ID User</th>
                <td><?php echo $penerimaan['iduser']; ?></td>
            </tr>
        </table>
        <a href="index.php" class="btn btn-primary">Kembali</a>
    </div>

    <?php include '../../includes/footer.php'; ?>
    <script src="../../assets/js/jquery.min.js"></script>
    <script src="../../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>