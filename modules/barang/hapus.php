<?php
include '../../config/database.php';

if (isset($_GET['idbarang'])) {
    $idbarang = $_GET['idbarang'];

    // Prepare the SQL statement to delete the item
    $query = "DELETE FROM barang WHERE idbarang = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $idbarang);

    // Execute the statement
    if ($stmt->execute()) {
        // Redirect to the barang index page with a success message
        header("Location: index.php?message=Item deleted successfully");
    } else {
        // Redirect to the barang index page with an error message
        header("Location: index.php?message=Error deleting item");
    }

    // Close the statement
    $stmt->close();
} else {
    // Redirect to the barang index page if no ID is provided
    header("Location: index.php");
}

// Close the database connection
$conn->close();
?>