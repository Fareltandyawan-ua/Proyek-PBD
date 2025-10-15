<?php
include '../../config/database.php';
include '../../includes/functions.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM barang WHERE idbarang = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $barang = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idbarang = $_POST['idbarang'];
    $jenis = $_POST['jenis'];
    $nama = $_POST['nama'];
    $idsatuan = $_POST['idsatuan'];
    $status = $_POST['status'];

    $query = "UPDATE barang SET jenis = ?, nama = ?, idsatuan = ?, status = ? WHERE idbarang = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssiii", $jenis, $nama, $idsatuan, $status, $idbarang);

    if ($stmt->execute()) {
        header("Location: index.php?message=Barang updated successfully");
    } else {
        echo "Error updating record: " . $conn->error;
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
    <title>Edit Barang</title>
</head>
<body>
    <div class="container">
        <h2>Edit Barang</h2>
        <form action="" method="POST">
            <input type="hidden" name="idbarang" value="<?php echo $barang['idbarang']; ?>">
            <div class="form-group">
                <label for="jenis">Jenis</label>
                <input type="text" class="form-control" id="jenis" name="jenis" value="<?php echo $barang['jenis']; ?>" required>
            </div>
            <div class="form-group">
                <label for="nama">Nama</label>
                <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $barang['nama']; ?>" required>
            </div>
            <div class="form-group">
                <label for="idsatuan">Satuan</label>
                <select class="form-control" id="idsatuan" name="idsatuan" required>
                    <?php
                    $satuanQuery = "SELECT * FROM satuan";
                    $satuanResult = $conn->query($satuanQuery);
                    while ($satuan = $satuanResult->fetch_assoc()) {
                        $selected = ($satuan['idsatuan'] == $barang['idsatuan']) ? 'selected' : '';
                        echo "<option value='{$satuan['idsatuan']}' $selected>{$satuan['nama_satuan']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="1" <?php echo ($barang['status'] == 1) ? 'selected' : ''; ?>>Active</option>
                    <option value="0" <?php echo ($barang['status'] == 0) ? 'selected' : ''; ?>>Inactive</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="index.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
    <script src="../../assets/js/jquery.min.js"></script>
    <script src="../../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>