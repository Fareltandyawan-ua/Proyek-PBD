<?php

try {
    if ($action === 'add') {
        $barang->add($_POST);
        header("Location: index.php?success=added");
    } elseif ($action === 'update') {
        $barang->update($_POST['idbarang'], $_POST);
        header("Location: index.php?success=updated");
    } elseif ($action === 'delete') {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') die("Akses tidak sah!");
        $barang->delete($_POST['idbarang']);
        header("Location: index.php?success=deleted");
    } else {
        throw new Exception("Aksi tidak valid!");
    }
    exit;
} catch (Exception $e) {
    header("Location: index.php?error=" . urlencode($e->getMessage()));
    exit;
}

?>