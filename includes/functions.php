<?php
// Function to connect to the database
function dbConnect() {
    $host = 'localhost'; // Database host
    $username = 'root'; // Database username
    $password = ''; // Database password
    $database = 'uts_pbdprak'; // Database name

    // Create connection
    $conn = new mysqli($host, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Function to execute a query and return results
function executeQuery($query) {
    $conn = dbConnect();
    $result = $conn->query($query);
    $conn->close();
    return $result;
}

// Function to fetch all records from a table
function fetchAll($table) {
    $query = "SELECT * FROM $table";
    return executeQuery($query);
}

// Function to fetch a single record by ID
function fetchById($table, $id) {
    $query = "SELECT * FROM $table WHERE id = $id";
    return executeQuery($query);
}

// Function to insert a new record into a table
function insertRecord($table, $data) {
    $columns = implode(", ", array_keys($data));
    $values = implode("', '", array_values($data));
    $query = "INSERT INTO $table ($columns) VALUES ('$values')";
    return executeQuery($query);
}

// Function to update a record in a table
function updateRecord($table, $data, $id) {
    $set = "";
    foreach ($data as $key => $value) {
        $set .= "$key = '$value', ";
    }
    $set = rtrim($set, ", ");
    $query = "UPDATE $table SET $set WHERE id = $id";
    return executeQuery($query);
}

// Function to delete a record from a table
function deleteRecord($table, $id) {
    $query = "DELETE FROM $table WHERE id = $id";
    return executeQuery($query);
}

// Function to start a session
function startSession() {
    session_start();
}

// Function to set a session variable
function setSession($key, $value) {
    $_SESSION[$key] = $value;
}

// Function to get a session variable
function getSession($key) {
    return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
}

// Function to destroy a session
function destroySession() {
    session_destroy();
}
?>