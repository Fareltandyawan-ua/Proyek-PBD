<?php
include '../config/database.php';

function calculateMargin($costPrice, $sellingPrice) {
    if ($costPrice == 0) {
        return 0; // Avoid division by zero
    }
    return (($sellingPrice - $costPrice) / $costPrice) * 100; // Return margin percentage
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $barangId = $_POST['barang_id'];
    $costPrice = $_POST['cost_price'];
    $sellingPrice = $_POST['selling_price'];

    // Validate input
    if (empty($barangId) || empty($costPrice) || empty($sellingPrice)) {
        echo json_encode(['error' => 'All fields are required.']);
        exit;
    }

    // Calculate margin
    $margin = calculateMargin($costPrice, $sellingPrice);

    // Prepare SQL query to update margin in the database
    $query = "UPDATE barang SET margin = ? WHERE idbarang = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("di", $margin, $barangId);

    if ($stmt->execute()) {
        echo json_encode(['success' => 'Margin calculated and updated successfully.', 'margin' => $margin]);
    } else {
        echo json_encode(['error' => 'Failed to update margin.']);
    }

    $stmt->close();
}
$conn->close();
?>