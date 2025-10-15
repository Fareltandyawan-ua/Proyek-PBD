<?php
include '../../config/database.php';
include '../../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idbarang = $_POST['idbarang'];
    $jumlah = $_POST['jumlah'];
    $alasan = $_POST['alasan'];

    $query = "INSERT INTO detail_retur (jumlah, alasan, idretur, iddetail_penerimaan) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("issi", $jumlah, $alasan, $idretur, $iddetail_penerimaan);

    if ($stmt->execute()) {
        header("Location: index.php?message=success");
    } else {
        $error = "Error: " . $stmt->error;
    }
}

$query_barang = "SELECT * FROM barang WHERE status = 1";
$result_barang = $conn->query($query_barang);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Retur</title>
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <?php include '../../includes/header.php'; ?>
    <?php include '../../includes/sidebar.php'; ?>

    <div class="container mt-5">
        <h2>Tambah Retur</h2>
        <?php if (isset($error)) { echo "<div class='alert alert-danger'>$error</div>"; } ?>
        <form action="" method="POST">
            <div class="form-group">
                <label for="idbarang">Barang</label>
                <select name="idbarang" id="idbarang" class="form-control" required>
                    <option value="">Pilih Barang</option>
                    <?php while ($row = $result_barang->fetch_assoc()) { ?>
                        <option value="<?php echo $row['idbarang']; ?>"><?php echo $row['nama']; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <label for="jumlah">Jumlah</label>
                <input type="number" name="jumlah" id="jumlah" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="alasan">Alasan</label>
                <textarea name="alasan" id="alasan" class="form-control" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="index.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>

    <?php include '../../includes/footer.php'; ?>
    <script src="../../assets/js/jquery.min.js"></script>
    <script src="../../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>