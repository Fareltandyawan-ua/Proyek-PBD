<?php
require_once '../../../classes/Database.php';
$database = new Database();
$db = $database->getConnection();

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $idpenjualan = $_POST['idpenjualan'];
        $idbarang = $_POST['idbarang'];
        $jumlah = $_POST['jumlah'];
        $harga = $_POST['harga'];

        // Insert data ke tabel detail_penjualan
        $query = "INSERT INTO detail_penjualan (idpenjualan, idbarang, jumlah, harga)
                  VALUES (?, ?, ?, ?)";
        $stmt = $db->prepare($query);
        $stmt->execute([$idpenjualan, $idbarang, $jumlah, $harga]);

        // ✅ Trigger trg_detail_penjualan otomatis memanggil sp_update_stok()

        header("Location: index.php?success=added");
        exit;
    } else {
        throw new Exception("Akses tidak valid!");
    }
} catch (Exception $e) {
    header("Location: index.php?error=" . urlencode($e->getMessage()));
    exit;
}
?>
