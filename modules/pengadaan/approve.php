<?php
session_start();
include '../../config/database.php';
include '../../includes/functions.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Get procurement ID from URL
$idpengadaan = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch procurement details
$query = "SELECT * FROM pengadaan WHERE idpengadaan = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $idpengadaan);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Pengadaan tidak ditemukan.";
    exit();
}

$pengadaan = $result->fetch_assoc();

// Approve procurement
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status = 'A'; // Set status to approved
    $updateQuery = "UPDATE pengadaan SET status = ? WHERE idpengadaan = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param("si", $status, $idpengadaan);
    
    if ($updateStmt->execute()) {
        header("Location: index.php?message=Pengadaan approved successfully");
        exit();
    } else {
        echo "Error approving pengadaan: " . $conn->error;
    }
}

include '../../includes/header.php';
include '../../includes/sidebar.php';
?>

<div class="container">
    <h2>Approve Pengadaan</h2>
    <form method="POST">
        <div class="form-group">
            <label for="nama_vendor">Nama Vendor:</label>
            <input type="text" class="form-control" id="nama_vendor" value="<?php echo htmlspecialchars($pengadaan['vendor_idvendor']); ?>" readonly>
        </div>
        <div class="form-group">
            <label for="subtotal_nilai">Subtotal Nilai:</label>
            <input type="text" class="form-control" id="subtotal_nilai" value="<?php echo htmlspecialchars($pengadaan['subtotal_nilai']); ?>" readonly>
        </div>
        <div class="form-group">
            <label for="status">Status:</label>
            <input type="text" class="form-control" id="status" value="<?php echo htmlspecialchars($pengadaan['status']); ?>" readonly>
        </div>
        <button type="submit" class="btn btn-primary">Approve</button>
        <a href="index.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?php
include '../../includes/footer.php';
?>