<?php
require_once '../../database/dbconnection.php';
require_once '../../class/class_data_kategori_klinis.php';


$kategoriKlinis = new KategoriKlinis();

// CREATE
if (isset($_POST['tambah'])) {
	$nama = trim($_POST['nama_kategori_klinis']);
	if ($nama !== '') {
		$kategoriKlinis->create($nama);
		header('Location: data_kategori_klinis.php');
		exit;
	}
}

// UPDATE
if (isset($_POST['update'])) {
	$id = intval($_POST['idkategori_klinis']);
	$nama = trim($_POST['nama_kategori_klinis']);
	if ($id && $nama !== '') {
		$kategoriKlinis->update($id, $nama);
		header('Location: data_kategori_klinis.php');
		exit;
	}
}

// DELETE
if (isset($_GET['delete'])) {
	$id = intval($_GET['delete']);
	if ($id) {
		$kategoriKlinis->delete($id);
		header('Location: data_kategori_klinis.php');
		exit;
	}
}

// Ambil data untuk edit
$edit_data = null;
if (isset($_GET['edit'])) {
	$id = intval($_GET['edit']);
	$edit_data = $kategoriKlinis->getById($id);
}

$data_kategori = $kategoriKlinis->getAll();

?>
<!DOCTYPE html>
<html lang="id">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="../../css/data_kategori_klinis.css">
	<link rel="stylesheet" type="text/css" href="../../css/data_master.css">
	<title>Data Kategori Klinis</title>
</head>

<body>
	<div class="header">
		<div class="logo"></div>
		<h2>Data Kategori Klinis</h2>
		<div class="nav">
			<a href="dashboard_admin.php">Dashboard</a>
			<a href="../../auth/logout.php">Logout</a>
		</div>
	</div>
	<div class="kategori-container">
		<?php
		// Form tambah/edit
		echo '<form method="post">';
		if ($edit_data) {
			echo '<input type="hidden" name="idkategori_klinis" value="' . $edit_data['idkategori_klinis'] . '">';
			echo '<input type="text" name="nama_kategori_klinis" value="' . htmlspecialchars($edit_data['nama_kategori_klinis']) . '" required> ';
			echo '<button type="submit" name="update">Update</button>';
			echo '<a href="data_kategori_klinis.php" class="button batal">Batal</a>';
		} else {
			echo '<input type="text" name="nama_kategori_klinis" placeholder="Nama Kategori Klinis" required> ';
			echo '<button type="submit" name="tambah">Tambah</button>';
		}
		echo '</form>';

		// Tabel data
		if ($data_kategori) {
			echo '<table>';
			echo '<tr><th>ID</th><th>Nama Kategori Klinis</th><th>Aksi</th></tr>';
			foreach ($data_kategori as $row) {
				echo '<tr>';
				echo '<td>' . $row['idkategori_klinis'] . '</td>';
				echo '<td>' . $row['nama_kategori_klinis'] . '</td>';
				echo '<td>';
				echo '<a class="aksi-link button" href="?edit=' . $row['idkategori_klinis'] . '">Edit</a>';
				echo '<a class="aksi-link button" style="background:#d90429;" href="?delete=' . $row['idkategori_klinis'] . '" onclick="return confirm(\'Hapus data ini?\')">Delete</a>';
				echo '</td>';
				echo '</tr>';
			}
			echo '</table>';
		} else {
			echo 'Tidak ada data.';
		}
		?>
		<br>
		<a href="data_master.php" class="btn-kembali">
            <span class="btn-icon">←</span>
            <span class="btn-text">Kembali</span>
        </a>
	</div>
</body>

</html>