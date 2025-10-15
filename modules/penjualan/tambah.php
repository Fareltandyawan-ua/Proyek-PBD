<?php
session_start();
include '../../config/database.php';
include '../../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idbarang = $_POST['idbarang'];
    $jumlah = $_POST['jumlah'];
    $harga_satuan = $_POST['harga_satuan'];
    $subtotal = $jumlah * $harga_satuan;

    $query = "INSERT INTO detail_penjualan (jumlah, harga_satuan, subtotal, penjualan_idpenjualan, idbarang) VALUES (?, ?, ?, LAST_INSERT_ID(), ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iiis", $jumlah, $harga_satuan, $subtotal, $idbarang);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Data penjualan berhasil ditambahkan.";
        header("Location: index.php");
        exit();
    } else {
        $_SESSION['error'] = "Gagal menambahkan data penjualan.";
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
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <title>Tambah Penjualan</title>
</head>
<body>
    <?php include '../../includes/header.php'; ?>
    <?php include '../../includes/sidebar.php'; ?>

    <div class="container mt-5">
        <h2>Tambah Penjualan</h2>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>
        <form action="" method="POST">
            <div class="form-group">
                <label for="idbarang">Barang</label>
                <select name="idbarang" id="idbarang" class="form-control" required>
                    <option value="">Pilih Barang</option>
                    <?php while ($row = $result_barang->fetch_assoc()): ?>
                        <option value="<?php echo $row['idbarang']; ?>"><?php echo $row['nama']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="jumlah">Jumlah</label>
                <input type="number" name="jumlah" id="jumlah" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="harga_satuan">Harga Satuan</label>
                <input type="number" name="harga_satuan" id="harga_satuan" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>

    <?php include '../../includes/footer.php'; ?>
    <script src="../../assets/js/jquery.min.js"></script>
    <script src="../../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>