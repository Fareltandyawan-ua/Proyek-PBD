<?php
include '../../config/database.php';
include '../../includes/functions.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM margin_penjualan WHERE idmargin_penjualan = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $margin = $result->fetch_assoc();
    } else {
        header("Location: index.php?error=Margin not found");
        exit();
    }
} else {
    header("Location: index.php?error=Invalid request");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $persen = $_POST['persen'];
    $status = $_POST['status'];

    $updateQuery = "UPDATE margin_penjualan SET persen = ?, status = ? WHERE idmargin_penjualan = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param("ssi", $persen, $status, $id);

    if ($updateStmt->execute()) {
        header("Location: index.php?success=Margin updated successfully");
        exit();
    } else {
        $error = "Error updating margin: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <title>Edit Margin</title>
</head>
<body>
    <?php include '../../includes/header.php'; ?>
    <?php include '../../includes/sidebar.php'; ?>

    <div class="container mt-5">
        <h2>Edit Margin</h2>
        <?php if (isset($error)) { echo "<div class='alert alert-danger'>$error</div>"; } ?>
        <form action="" method="POST">
            <div class="form-group">
                <label for="persen">Persen</label>
                <input type="text" class="form-control" id="persen" name="persen" value="<?php echo $margin['persen']; ?>" required>
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="1" <?php echo ($margin['status'] == 1) ? 'selected' : ''; ?>>Active</option>
                    <option value="0" <?php echo ($margin['status'] == 0) ? 'selected' : ''; ?>>Inactive</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update Margin</button>
            <a href="index.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

    <?php include '../../includes/footer.php'; ?>
    <script src="../../assets/js/jquery.min.js"></script>
    <script src="../../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>