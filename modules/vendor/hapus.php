<?php
include '../../config/database.php';

if (isset($_GET['idvendor'])) {
    $idvendor = $_GET['idvendor'];

    // Prepare the SQL statement to delete the vendor
    $query = "DELETE FROM vendor WHERE idvendor = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $idvendor);

    if ($stmt->execute()) {
        // Redirect to the vendor index page with a success message
        header("Location: index.php?message=Vendor deleted successfully");
    } else {
        // Redirect to the vendor index page with an error message
        header("Location: index.php?message=Error deleting vendor");
    }

    $stmt->close();
} else {
    // Redirect to the vendor index page if no ID is provided
    header("Location: index.php?message=No vendor ID provided");
}

$conn->close();
?>