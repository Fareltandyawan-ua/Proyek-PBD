<?php
require_once '../../classes/Database.php';
$db = new Database();

$action = $_GET['action'] ?? '';

if ($action === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $username = trim($_POST['username']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $idrole   = $_POST['idrole'];

        $db->execute("INSERT INTO user (username, password, idrole) VALUES (?, ?, ?)", [
            $username, $password, $idrole
        ]);

        header("Location: index.php?success=added");
        exit;
    } catch (Exception $e) {
        header("Location: index.php?error=" . urlencode($e->getMessage()));
        exit;
    }
}

if ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $iduser = $_POST['iduser'];
        $username = trim($_POST['username']);
        $idrole = $_POST['idrole'];

        if (!empty($_POST['password'])) {
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $db->execute("UPDATE user SET username=?, password=?, idrole=? WHERE iduser=?", [
                $username, $password, $idrole, $iduser
            ]);
        } else {
            $db->execute("UPDATE user SET username=?, idrole=? WHERE iduser=?", [
                $username, $idrole, $iduser
            ]);
        }

        header("Location: index.php?success=updated");
        exit;
    } catch (Exception $e) {
        header("Location: index.php?error=" . urlencode($e->getMessage()));
        exit;
    }
}

if ($action === 'delete' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $iduser = $_POST['iduser'];
        $db->execute("DELETE FROM user WHERE iduser=?", [$iduser]);
        header("Location: index.php?success=deleted");
        exit;
    } catch (Exception $e) {
        header("Location: index.php?error=" . urlencode($e->getMessage()));
        exit;
    }
}
