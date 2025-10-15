<?php
include '../../config/database.php';
include '../../includes/functions.php';

if (isset($_GET['idvendor'])) {
    $idvendor = $_GET['idvendor'];
    $vendor = getVendorById($idvendor);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idvendor = $_POST['idvendor'];
    $nama_vendor = $_POST['nama_vendor'];
    $badan_hukum = $_POST['badan_hukum'];
    $status = $_POST['status'];

    $update = updateVendor($idvendor, $nama_vendor, $badan_hukum, $status);
    if ($update) {
        header("Location: index.php?message=Vendor updated successfully");
        exit();
    } else {
        $error = "Failed to update vendor. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Vendor</title>
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <?php include '../../includes/header.php'; ?>
    <?php include '../../includes/sidebar.php'; ?>

    <div class="container mt-5">
        <h2>Edit Vendor</h2>
        <?php if (isset($error)) { echo "<div class='alert alert-danger'>$error</div>"; } ?>
        <form action="" method="POST">
            <input type="hidden" name="idvendor" value="<?php echo $vendor['idvendor']; ?>">
            <div class="form-group">
                <label for="nama_vendor">Nama Vendor</label>
                <input type="text" class="form-control" id="nama_vendor" name="nama_vendor" value="<?php echo $vendor['nama_vendor']; ?>" required>
            </div>
            <div class="form-group">
                <label for="badan_hukum">Badan Hukum</label>
                <select class="form-control" id="badan_hukum" name="badan_hukum" required>
                    <option value="P" <?php echo ($vendor['badan_hukum'] == 'P') ? 'selected' : ''; ?>>Perseroan</option>
                    <option value="C" <?php echo ($vendor['badan_hukum'] == 'C') ? 'selected' : ''; ?>>CV</option>
                    <option value="N" <?php echo ($vendor['badan_hukum'] == 'N') ? 'selected' : ''; ?>>N/A</option>
                </select>
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="A" <?php echo ($vendor['status'] == 'A') ? 'selected' : ''; ?>>Aktif</option>
                    <option value="N" <?php echo ($vendor['status'] == 'N') ? 'selected' : ''; ?>>Non-Aktif</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update Vendor</button>
        </form>
    </div>

    <?php include '../../includes/footer.php'; ?>
    <script src="../../assets/js/jquery.min.js"></script>
    <script src="../../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>