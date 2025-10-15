<?php
include '../config/database.php';

header('Content-Type: application/json');

try {
    $query = "SELECT * FROM vendor";
    $stmt = $conn->prepare($query);
    $stmt->execute();

    $vendors = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => 'success',
        'data' => $vendors
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
?>