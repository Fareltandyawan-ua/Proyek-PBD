<?php
include '../../config/database.php';

if (isset($_GET['iduser'])) {
    $iduser = $_GET['iduser'];

    // Prepare the SQL statement
    $query = "DELETE FROM user WHERE iduser = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $iduser);

    // Execute the statement
    if ($stmt->execute()) {
        // Redirect to the user index page with a success message
        header("Location: index.php?message=User deleted successfully");
    } else {
        // Redirect to the user index page with an error message
        header("Location: index.php?message=Error deleting user");
    }

    // Close the statement
    $stmt->close();
} else {
    // Redirect to the user index page if no ID is provided
    header("Location: index.php?message=No user ID provided");
}

// Close the database connection
$conn->close();
?>