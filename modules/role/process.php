<?php
require_once '../../classes/Database.php';
$db = new Database();

$action = $_GET['action'] ?? '';

if ($action === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $db->execute("INSERT INTO role (nama_role) VALUES (?)", [$_POST['nama_role']]);
    header("Location: index.php?success=added");
    exit;
}

if ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $db->execute("UPDATE role SET nama_role=? WHERE idrole=?", [$_POST['nama_role'], $_POST['idrole']]);
    header("Location: index.php?success=updated");
    exit;
}

if ($action === 'delete') {
    $id = $_GET['id'] ?? 0;
    $db->execute("DELETE FROM role WHERE idrole=?", [$id]);
    header("Location: index.php?success=deleted");
    exit;
}
?>
