<?php
include '../../config/database.php';
include '../../includes/functions.php';

// Check if the ID is set in the URL
if (isset($_GET['id'])) {
    $idretur = $_GET['id'];

    // Fetch the return details from the database
    $query = "SELECT r.idretur, r.created_at, r.idpenerimaan, r.iduser, d.jumlah, d.alasan 
              FROM retur r 
              JOIN detail_retur d ON r.idretur = d.idretur 
              WHERE r.idretur = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $idretur);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $retur = $result->fetch_assoc();
    } else {
        echo "No details found for this return.";
        exit;
    }
} else {
    echo "Invalid request.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <title>Detail Retur</title>
</head>
<body>
    <?php include '../../includes/header.php'; ?>
    <?php include '../../includes/sidebar.php'; ?>

    <div class="container mt-5">
        <h2>Detail Retur</h2>
        <table class="table table-bordered">
            <tr>
                <th>ID Retur</th>
                <td><?php echo $retur['idretur']; ?></td>
            </tr>
            <tr>
                <th>Tanggal</th>
                <td><?php echo $retur['created_at']; ?></td>
            </tr>
            <tr>
                <th>ID Penerimaan</th>
                <td><?php echo $retur['idpenerimaan']; ?></td>
            </tr>
            <tr>
                <th>ID User</th>
                <td><?php echo $retur['iduser']; ?></td>
            </tr>
            <tr>
                <th>Jumlah</th>
                <td><?php echo $retur['jumlah']; ?></td>
            </tr>
            <tr>
                <th>Alasan</th>
                <td><?php echo $retur['alasan']; ?></td>
            </tr>
        </table>
        <a href="index.php" class="btn btn-primary">Kembali</a>
    </div>

    <?php include '../../includes/footer.php'; ?>
    <script src="../../assets/js/jquery.min.js"></script>
    <script src="../../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>