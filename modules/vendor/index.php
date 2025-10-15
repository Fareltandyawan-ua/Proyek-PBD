<?php
include_once '../../config/database.php';
include_once '../../includes/functions.php';

// Fetch vendors from the database
$query = "SELECT * FROM vendor";
$stmt = $conn->prepare($query);
$stmt->execute();
$vendors = $stmt->fetchAll(PDO::FETCH_ASSOC);

include_once '../../includes/header.php';
include_once '../../includes/sidebar.php';
?>

<div class="container">
    <h2>Vendor List</h2>
    <a href="tambah.php" class="btn btn-primary">Add New Vendor</a>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Legal Entity</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($vendors as $vendor): ?>
                <tr>
                    <td><?php echo htmlspecialchars($vendor['idvendor']); ?></td>
                    <td><?php echo htmlspecialchars($vendor['nama_vendor']); ?></td>
                    <td><?php echo htmlspecialchars($vendor['badan_hukum']); ?></td>
                    <td><?php echo htmlspecialchars($vendor['status']); ?></td>
                    <td>
                        <a href="edit.php?id=<?php echo $vendor['idvendor']; ?>" class="btn btn-warning">Edit</a>
                        <a href="hapus.php?id=<?php echo $vendor['idvendor']; ?>" class="btn btn-danger">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include_once '../../includes/footer.php'; ?>