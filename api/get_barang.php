<?php
include '../config/database.php';

header('Content-Type: application/json');

$query = "SELECT * FROM barang";
$result = $conn->query($query);

$barang = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $barang[] = $row;
    }
    echo json_encode(array("status" => "success", "data" => $barang));
} else {
    echo json_encode(array("status" => "error", "message" => "No items found."));
}

$conn->close();
?>