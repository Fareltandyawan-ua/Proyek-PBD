<?php
session_start();
include '../../config/database.php';
include '../../includes/functions.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Get the sale ID from the URL
$sale_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch sale details from the database
$sale = null;
if ($sale_id > 0) {
    $query = "SELECT p.idpenjualan, p.created_at, p.subtotal_nilai, p.ppn, p.total_nilai, u.username 
              FROM penjualan p 
              JOIN user u ON p.iduser = u.iduser 
              WHERE p.idpenjualan = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $sale_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $sale = $result->fetch_assoc();
}

// Fetch sale details items
$sale_items = [];
if ($sale_id > 0) {
    $query_items = "SELECT dp.jumlah, dp.harga_satuan, dp.subtotal, b.nama 
                    FROM detail_penjualan dp 
                    JOIN barang b ON dp.idbarang = b.idbarang 
                    WHERE dp.penjualan_idpenjualan = ?";
    $stmt_items = $conn->prepare($query_items);
    $stmt_items->bind_param("i", $sale_id);
    $stmt_items->execute();
    $result_items = $stmt_items->get_result();
    while ($row = $result_items->fetch_assoc()) {
        $sale_items[] = $row;
    }
}

include '../../includes/header.php';
include '../../includes/sidebar.php';
?>

<div class="container">
    <h2>Detail Penjualan</h2>
    <?php if ($sale): ?>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">ID Penjualan: <?php echo $sale['idpenjualan']; ?></h5>
                <p class="card-text">Tanggal: <?php echo $sale['created_at']; ?></p>
                <p class="card-text">Subtotal: <?php echo number_format($sale['subtotal_nilai'], 2); ?></p>
                <p class="card-text">PPN: <?php echo number_format($sale['ppn'], 2); ?></p>
                <p class="card-text">Total: <?php echo number_format($sale['total_nilai'], 2); ?></p>
                <p class="card-text">Dibuat oleh: <?php echo $sale['username']; ?></p>
            </div>
        </div>

        <h3>Detail Item Penjualan</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Nama Barang</th>
                    <th>Jumlah</th>
                    <th>Harga Satuan</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sale_items as $item): ?>
                    <tr>
                        <td><?php echo $item['nama']; ?></td>
                        <td><?php echo $item['jumlah']; ?></td>
                        <td><?php echo number_format($item['harga_satuan'], 2); ?></td>
                        <td><?php echo number_format($item['subtotal'], 2); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Detail penjualan tidak ditemukan.</p>
    <?php endif; ?>
</div>

<?php include '../../includes/footer.php'; ?>