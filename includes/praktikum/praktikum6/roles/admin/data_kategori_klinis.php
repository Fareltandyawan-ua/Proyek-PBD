<?php
require_once '../../database/dbconnection.php';

$db = new DBConnection();
$db->init_connect();

// CREATE
if (isset($_POST['tambah'])) {
	$nama = trim($_POST['nama_kategori_klinis']);
	if ($nama !== '') {
		$stmt = $db->dbconn->prepare('INSERT INTO kategori_klinis (nama_kategori_klinis) VALUES (?)');
		$stmt->bind_param('s', $nama);
		$stmt->execute();
		$stmt->close();
		header('Location: data_kategori_klinis.php');
		exit;
	}
}

// UPDATE
if (isset($_POST['update'])) {
	$id = intval($_POST['idkategori_klinis']);
	$nama = trim($_POST['nama_kategori_klinis']);
	if ($id && $nama !== '') {
		$stmt = $db->dbconn->prepare('UPDATE kategori_klinis SET nama_kategori_klinis=? WHERE idkategori_klinis=?');
		$stmt->bind_param('si', $nama, $id);
		$stmt->execute();
		$stmt->close();
		header('Location: data_kategori_klinis.php');
		exit;
	}
}

// DELETE
if (isset($_GET['delete'])) {
	$id = intval($_GET['delete']);
	if ($id) {
		$stmt = $db->dbconn->prepare('DELETE FROM kategori_klinis WHERE idkategori_klinis=?');
		$stmt->bind_param('i', $id);
		$stmt->execute();
		$stmt->close();
		header('Location: data_kategori_klinis.php');
		exit;
	}
}

// Ambil data untuk edit
$edit_data = null;
if (isset($_GET['edit'])) {
	$id = intval($_GET['edit']);
	$stmt = $db->dbconn->prepare('SELECT * FROM kategori_klinis WHERE idkategori_klinis=?');
	$stmt->bind_param('i', $id);
	$stmt->execute();
	$result_edit = $stmt->get_result();
	$edit_data = $result_edit->fetch_assoc();
	$stmt->close();
}

$result = $db->send_query('SELECT * FROM kategori_klinis');

?>
<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="../../css/data_master.css">
	<title>Data Kategori Klinis</title>
	<style>
		.kategori-container { max-width: 600px; margin: 32px auto; background: #fff; border-radius: 10px; box-shadow: 0 2px 8px #0001; padding: 32px; }
		.kategori-container h2 { text-align: center; color: #222; margin-bottom: 24px; }
		form { display: flex; gap: 8px; justify-content: center; margin-bottom: 24px; }
		input[type="text"] { padding: 8px; border: 1px solid #b7e4c7; border-radius: 5px; min-width: 180px; }
		button, a.button { background: #4FC3F7; color: #fff; border: none; padding: 8px 16px; border-radius: 5px; cursor: pointer; text-decoration: none; transition: background 0.2s; }
		button:hover, a.button:hover { background: #0288d1; }
		a.batal { background: #adb5bd; color: #222; margin-left: 8px; }
		table { width: 100%; border-collapse: collapse; margin-top: 8px; }
		th, td { padding: 10px 8px; border-bottom: 1px solid #b7e4c7; text-align: left; }
		th { background: #b7e4c7; color: #222; }
		tr:nth-child(even) { background: #f1faee; }
		.aksi-link { margin-right: 8px; }
	</style>
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
		if ($result['status'] === 'success') {
			echo '<table>';
			echo '<tr><th>ID</th><th>Nama Kategori Klinis</th><th>Aksi</th></tr>';
			foreach ($result['data'] as $row) {
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
			echo 'Error: ' . $result['message'];
		}
		?>
	</div>
</body>
</html>
