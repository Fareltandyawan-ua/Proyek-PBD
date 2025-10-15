<?php
include '../../config/database.php';
include '../../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $jenis = $_POST['jenis'];
    $nama = $_POST['nama'];
    $idsatuan = $_POST['idsatuan'];
    $status = $_POST['status'];

    $query = "INSERT INTO margin_penjualan (jenis, nama, idsatuan, status) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssii", $jenis, $nama, $idsatuan, $status);

    if ($stmt->execute()) {
        header("Location: index.php?message=Margin added successfully");
        exit();
    } else {
        $error = "Error: " . $stmt->error;
    }
}

$querySatuan = "SELECT * FROM satuan";
$resultSatuan = $conn->query($querySatuan);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <title>Add Margin</title>
</head>
<body>
    <?php include '../../includes/header.php'; ?>
    <?php include '../../includes/sidebar.php'; ?>

    <div class="container mt-5">
        <h2>Add Margin</h2>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <form action="" method="POST">
            <div class="form-group">
                <label for="jenis">Jenis</label>
                <input type="text" class="form-control" name="jenis" required>
            </div>
            <div class="form-group">
                <label for="nama">Nama</label>
                <input type="text" class="form-control" name="nama" required>
            </div>
            <div class="form-group">
                <label for="idsatuan">Satuan</label>
                <select class="form-control" name="idsatuan" required>
                    <option value="">Select Satuan</option>
                    <?php while ($row = $resultSatuan->fetch_assoc()): ?>
                        <option value="<?php echo $row['idsatuan']; ?>"><?php echo $row['nama_satuan']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select class="form-control" name="status" required>
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Add Margin</button>
        </form>
    </div>

    <?php include '../../includes/footer.php'; ?>
    <script src="../../assets/js/jquery.min.js"></script>
    <script src="../../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>