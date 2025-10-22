<?php
require_once '../../classes/Database.php';
$db = new Database();

$action = $_GET['action'] ?? '';

if ($action === 'add') {
    $sql = "INSERT INTO vendor (nama_vendor, badan_hukum, status) VALUES (?, ?, ?)";
    $db->execute($sql, [
        $_POST['nama_vendor'],
        $_POST['badan_hukum'],
        $_POST['status']
    ]);
    header("Location: index.php?success=added");
    exit;
}

if ($action === 'update') {
    $sql = "UPDATE vendor SET nama_vendor=?, badan_hukum=?, status=? WHERE idvendor=?";
    $db->execute($sql, [
        $_POST['nama_vendor'],
        $_POST['badan_hukum'],
        $_POST['status'],
        $_POST['idvendor']
    ]);
    header("Location: index.php?success=updated");
    exit;
}

if ($action === 'delete') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: index.php?error=Akses tidak sah!");
        exit;
    }

    $id = $_POST['idvendor'] ?? 0;
    if (empty($id)) {
        header("Location: index.php?error=ID vendor tidak ditemukan");
        exit;
    }

    try {
        $deleted = $db->execute("DELETE FROM vendor WHERE idvendor = ?", [$id]);
        if ($deleted > 0) {
            header("Location: index.php?success=deleted");
        } else {
            header("Location: index.php?error=Data vendor tidak ditemukan atau gagal dihapus");
        }
    } catch (Exception $e) {
        header("Location: index.php?error=" . urlencode($e->getMessage()));
    }
    exit;
}

die("Akses tidak sah!");
