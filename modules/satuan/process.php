<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Akses tidak sah!");
}

require_once '../../classes/Satuan.php';
$satuan = new Satuan();

$action = $_GET['action'] ?? '';

try {
    if ($action === 'add') {
        $satuan->add(['nama_satuan' => $_POST['nama_satuan']]);
        header("Location: index.php?success=added");
        exit;
    }

    if ($action === 'update') {
        $id = $_POST['idsatuan'];
        $satuan->update($id, ['nama_satuan' => $_POST['nama_satuan']]);
        header("Location: index.php?success=updated");
        exit;
    }

    if ($action === 'delete') {
        $id = $_POST['idsatuan'];
        $satuan->delete($id);
        header("Location: index.php?success=deleted");
        exit;
    }

    throw new Exception("Aksi tidak dikenali!");
} catch (Exception $e) {
    header("Location: index.php?error=" . urlencode($e->getMessage()));
    exit;
}
