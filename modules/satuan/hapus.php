<?php
include '../../config/database.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Prepare the SQL statement to delete the unit
    $query = "DELETE FROM satuan WHERE idsatuan = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Redirect to the index page after successful deletion
        header("Location: index.php?message=Unit deleted successfully");
    } else {
        echo "Error deleting record: " . $conn->error;
    }

    $stmt->close();
} else {
    echo "Invalid request.";
}

$conn->close();
?>