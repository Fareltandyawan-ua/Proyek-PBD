<?php
include '../../config/database.php';
include '../../includes/functions.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM pengadaan";
$result = mysqli_query($conn, $query);

include '../../includes/header.php';
include '../../includes/sidebar.php';
?>

<div class="container">
    <h1>Pengadaan Records</h1>
    <a href="tambah.php" class="btn btn-primary">Tambah Pengadaan</a>
    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>ID Pengadaan</th>
                <th>Tanggal</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                <tr>
                    <td><?php echo $row['idpengadaan']; ?></td>
                    <td><?php echo $row['timestamp']; ?></td>
                    <td><?php echo $row['status']; ?></td>
                    <td>
                        <a href="detail.php?id=<?php echo $row['idpengadaan']; ?>" class="btn btn-info">Detail</a>
                        <a href="approve.php?id=<?php echo $row['idpengadaan']; ?>" class="btn btn-success">Approve</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include '../../includes/footer.php'; ?>