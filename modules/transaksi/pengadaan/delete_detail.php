<?php
require_once '../../../classes/Auth.php';
require_once '../../../classes/Database.php';

$auth = new Auth();
$auth->checkRole([1]);
$db = new Database();

if (!isset($_GET['id']) || !isset($_GET['idpengadaan'])) {
    die("Parameter tidak lengkap!");
}

$iddetail = $_GET['id'];
$idpengadaan = $_GET['idpengadaan'];

$db->execute("DELETE FROM detail_pengadaan WHERE iddetail_pengadaan = ?", [$iddetail]);

header("Location: detail.php?idpengadaan=$idpengadaan");
exit;
