<?php
require_once '../../classes/Database.php';
$db = new Database();

$action = $_GET['action'] ?? '';

try {
    if ($action === 'add') {
        $persen = $_POST['persen'];
        $status = $_POST['status'];
        $iduser = $_POST['iduser']; 
        $stmt = $db->getConnection()->prepare(
            "INSERT INTO margin_penjualan (created_at, persen, status, iduser, updated_at) VALUES (NOW(), ?, ?, ?, NOW())"
        );
        $stmt->execute([$persen, $status, $iduser]);
        header("Location: index.php?success=added");
    } elseif ($action === 'update') {
        $id = $_POST['idmargin_penjualan'];
        $persen = $_POST['persen'];
        $status = $_POST['status'];
        $iduser = $_POST['iduser']; 
        $stmt = $db->getConnection()->prepare(
            "UPDATE margin_penjualan SET persen = ?, status = ?, iduser = ?, updated_at = NOW() WHERE idmargin_penjualan = ?"
        );
        $stmt->execute([$persen, $status, $iduser, $id]);
        header("Location: index.php?success=updated");
    } elseif ($action === 'delete') {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') die("Akses tidak sah!");
        $id = $_POST['idmargin_penjualan'];
        $stmt = $db->getConnection()->prepare(
            "DELETE FROM margin_penjualan WHERE idmargin_penjualan = ?"
        );
        $stmt->execute([$id]);
        header("Location: index.php?success=deleted");
    } else {
        throw new Exception("Aksi tidak valid!");
    }
    exit;
} catch (Exception $e) {
    header("Location: index.php?error=" . urlencode($e->getMessage()));
    exit;
}