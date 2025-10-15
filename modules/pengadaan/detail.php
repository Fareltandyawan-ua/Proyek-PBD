<?php
include '../../config/database.php';
include '../../includes/functions.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$idpengadaan = isset($_GET['id']) ? intval($_GET['id']) : 0;

$query = "SELECT p.*, v.nama_vendor FROM pengadaan p JOIN vendor v ON p.vendor_idvendor = v.idvendor WHERE p.idpengadaan = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $idpengadaan);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Data tidak ditemukan.";
    exit();
}

$pengadaan = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pengadaan</title>
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <?php include '../../includes/header.php'; ?>
    <?php include '../../includes/sidebar.php'; ?>

    <div class="container mt-5">
        <h2>Detail Pengadaan</h2>
        <table class="table table-bordered">
            <tr>
                <th>ID Pengadaan</th>
                <td><?php echo $pengadaan['idpengadaan']; ?></td>
            </tr>
            <tr>
                <th>Vendor</th>
                <td><?php echo $pengadaan['nama_vendor']; ?></td>
            </tr>
            <tr>
                <th>Tanggal</th>
                <td><?php echo $pengadaan['timestamp']; ?></td>
            </tr>
            <tr>
                <th>Status</th>
                <td><?php echo $pengadaan['status']; ?></td>
            </tr>
            <tr>
                <th>Subtotal Nilai</th>
                <td><?php echo number_format($pengadaan['subtotal_nilai'], 2); ?></td>
            </tr>
            <tr>
                <th>PPN</th>
                <td><?php echo number_format($pengadaan['ppn'], 2); ?></td>
            </tr>
            <tr>
                <th>Total Nilai</th>
                <td><?php echo number_format($pengadaan['total_nilai'], 2); ?></td>
            </tr>
        </table>
        <a href="index.php" class="btn btn-primary">Kembali</a>
    </div>

    <?php include '../../includes/footer.php'; ?>
    <script src="../../assets/js/jquery.min.js"></script>
    <script src="../../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>