<?php
include '../../config/database.php';
include '../../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idvendor = $_POST['idvendor'];
    $timestamp = date('Y-m-d H:i:s');
    $user_iduser = $_POST['user_iduser'];
    $status = $_POST['status'];
    $subtotal_nilai = $_POST['subtotal_nilai'];
    $ppn = $_POST['ppn'];
    $total_nilai = $_POST['total_nilai'];

    $query = "INSERT INTO pengadaan (timestamp, user_iduser, status, vendor_idvendor, subtotal_nilai, ppn, total_nilai) 
              VALUES ('$timestamp', '$user_iduser', '$status', '$idvendor', '$subtotal_nilai', '$ppn', '$total_nilai')";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Pengadaan berhasil ditambahkan!'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pengadaan</title>
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <?php include '../../includes/header.php'; ?>
    <?php include '../../includes/sidebar.php'; ?>

    <div class="container mt-5">
        <h2>Tambah Pengadaan</h2>
        <form action="" method="POST">
            <div class="form-group">
                <label for="idvendor">Vendor</label>
                <select name="idvendor" id="idvendor" class="form-control" required>
                    <?php
                    $vendor_query = "SELECT * FROM vendor";
                    $vendor_result = mysqli_query($conn, $vendor_query);
                    while ($vendor = mysqli_fetch_assoc($vendor_result)) {
                        echo "<option value='{$vendor['idvendor']}'>{$vendor['nama_vendor']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="user_iduser">User</label>
                <input type="text" name="user_iduser" id="user_iduser" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <input type="text" name="status" id="status" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="subtotal_nilai">Subtotal Nilai</label>
                <input type="number" name="subtotal_nilai" id="subtotal_nilai" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="ppn">PPN</label>
                <input type="number" name="ppn" id="ppn" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="total_nilai">Total Nilai</label>
                <input type="number" name="total_nilai" id="total_nilai" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>

    <?php include '../../includes/footer.php'; ?>
    <script src="../../assets/js/jquery.min.js"></script>
    <script src="../../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>