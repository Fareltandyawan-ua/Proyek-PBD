<?php
require_once '../../database/dbconnection.php';

$db = new DBConnection();
$db->init_connect();

// Ambil data kategori dan kategori klinis untuk select option
$kategori = $db->send_query('SELECT * FROM kategori');
$kategori_klinis = $db->send_query('SELECT * FROM kategori_klinis');

// CREATE
if (isset($_POST['tambah'])) {
    $kode = trim($_POST['kode']);
    $deskripsi = trim($_POST['deskripsi_tindakan_terapi']);
    $idkategori = intval($_POST['idkategori']);
    $idkategori_klinis = intval($_POST['idkategori_klinis']);
    if ($kode !== '' && $deskripsi !== '' && $idkategori && $idkategori_klinis) {
        $stmt = $db->dbconn->prepare('INSERT INTO kode_tindakan_terapi (kode, deskripsi_tindakan_terapi, idkategori, idkategori_klinis) VALUES (?, ?, ?, ?)');
        $stmt->bind_param('ssii', $kode, $deskripsi, $idkategori, $idkategori_klinis);
        $stmt->execute();
        $stmt->close();
        header('Location: data_kode_tindakan_terapi.php');
        exit;
    }
}

// UPDATE
if (isset($_POST['update'])) {
    $id = intval($_POST['idkode_tindakan_terapi']);
    $kode = trim($_POST['kode']);
    $deskripsi = trim($_POST['deskripsi_tindakan_terapi']);
    $idkategori = intval($_POST['idkategori']);
    $idkategori_klinis = intval($_POST['idkategori_klinis']);
    if ($id && $kode !== '' && $deskripsi !== '' && $idkategori && $idkategori_klinis) {
        $stmt = $db->dbconn->prepare('UPDATE kode_tindakan_terapi SET kode=?, deskripsi_tindakan_terapi=?, idkategori=?, idkategori_klinis=? WHERE idkode_tindakan_terapi=?');
        $stmt->bind_param('ssiii', $kode, $deskripsi, $idkategori, $idkategori_klinis, $id);
        $stmt->execute();
        $stmt->close();
        header('Location: data_kode_tindakan_terapi.php');
        exit;
    }
}

// DELETE
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    if ($id) {
        $stmt = $db->dbconn->prepare('DELETE FROM kode_tindakan_terapi WHERE idkode_tindakan_terapi=?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->close();
        header('Location: data_kode_tindakan_terapi.php');
        exit;
    }
}

// Ambil data untuk edit
$edit_data = null;
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $stmt = $db->dbconn->prepare('SELECT * FROM kode_tindakan_terapi WHERE idkode_tindakan_terapi=?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result_edit = $stmt->get_result();
    $edit_data = $result_edit->fetch_assoc();
    $stmt->close();
}

$query = 'SELECT ktt.idkode_tindakan_terapi, ktt.kode, ktt.deskripsi_tindakan_terapi, ktt.idkategori, ktt.idkategori_klinis, k.nama_kategori, kk.nama_kategori_klinis
          FROM kode_tindakan_terapi ktt
          LEFT JOIN kategori k ON ktt.idkategori = k.idkategori
          LEFT JOIN kategori_klinis kk ON ktt.idkategori_klinis = kk.idkategori_klinis';
$result = $db->send_query($query);

?>
<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="../../css/data_master.css">
	<title>Data Kode Tindakan Terapi</title>
	<style>
		.kode-container { max-width: 1100px; margin: 32px auto; background: #fff; border-radius: 10px; box-shadow: 0 2px 8px #0001; padding: 32px; }
		.kode-container h2 { text-align: center; color: #222; margin-bottom: 24px; }
		form { display: flex; gap: 8px; flex-wrap: wrap; justify-content: center; margin-bottom: 24px; }
		input[type="text"], textarea, select { padding: 8px; border: 1px solid #b7e4c7; border-radius: 5px; min-width: 120px; }
		textarea { min-width: 200px; min-height: 32px; }
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
		<h2>Data Kode Tindakan Terapi</h2>
		<div class="nav">
			<a href="dashboard_admin.php">Dashboard</a>
			<a href="../../auth/logout.php">Logout</a>
		</div>
	</div>
	<div class="kode-container">
		<?php
		// Form tambah/edit
		echo '<form method="post">';
		if ($edit_data) {
			echo '<input type="hidden" name="idkode_tindakan_terapi" value="' . $edit_data['idkode_tindakan_terapi'] . '">';
			echo '<input type="text" name="kode" value="' . htmlspecialchars($edit_data['kode']) . '" placeholder="Kode" required> ';
			echo '<textarea name="deskripsi_tindakan_terapi" placeholder="Deskripsi Tindakan" required>' . htmlspecialchars($edit_data['deskripsi_tindakan_terapi']) . '</textarea> ';
			echo '<select name="idkategori" required>';
			echo '<option value="">Pilih Kategori</option>';
			foreach ($kategori['data'] as $kat) {
				$selected = $edit_data['idkategori'] == $kat['idkategori'] ? 'selected' : '';
				echo '<option value="' . $kat['idkategori'] . '" ' . $selected . '>' . htmlspecialchars($kat['nama_kategori']) . '</option>';
			}
			echo '</select> ';
			echo '<select name="idkategori_klinis" required>';
			echo '<option value="">Pilih Kategori Klinis</option>';
			foreach ($kategori_klinis['data'] as $kk) {
				$selected = $edit_data['idkategori_klinis'] == $kk['idkategori_klinis'] ? 'selected' : '';
				echo '<option value="' . $kk['idkategori_klinis'] . '" ' . $selected . '>' . htmlspecialchars($kk['nama_kategori_klinis']) . '</option>';
			}
			echo '</select> ';
			echo '<button type="submit" name="update">Update</button>';
			echo '<a href="data_kode_tindakan_terapi.php" class="button batal">Batal</a>';
		} else {
			echo '<input type="text" name="kode" placeholder="Kode" required> ';
			echo '<textarea name="deskripsi_tindakan_terapi" placeholder="Deskripsi Tindakan" required></textarea> ';
			echo '<select name="idkategori" required>';
			echo '<option value="">Pilih Kategori</option>';
			foreach ($kategori['data'] as $kat) {
				echo '<option value="' . $kat['idkategori'] . '">' . htmlspecialchars($kat['nama_kategori']) . '</option>';
			}
			echo '</select> ';
			echo '<select name="idkategori_klinis" required>';
			echo '<option value="">Pilih Kategori Klinis</option>';
			foreach ($kategori_klinis['data'] as $kk) {
				echo '<option value="' . $kk['idkategori_klinis'] . '">' . htmlspecialchars($kk['nama_kategori_klinis']) . '</option>';
			}
			echo '</select> ';
			echo '<button type="submit" name="tambah">Tambah</button>';
		}
		echo '</form>';

		// Tabel data
		if ($result['status'] === 'success') {
			echo '<table>';
			echo '<tr><th>ID</th><th>Kode</th><th>Deskripsi Tindakan</th><th>Kategori</th><th>Kategori Klinis</th><th>Aksi</th></tr>';
			foreach ($result['data'] as $row) {
				echo '<tr>';
				echo '<td>' . $row['idkode_tindakan_terapi'] . '</td>';
				echo '<td>' . $row['kode'] . '</td>';
				echo '<td>' . $row['deskripsi_tindakan_terapi'] . '</td>';
				echo '<td>' . $row['nama_kategori'] . '</td>';
				echo '<td>' . $row['nama_kategori_klinis'] . '</td>';
				echo '<td>';
				echo '<a class="aksi-link button" href="?edit=' . $row['idkode_tindakan_terapi'] . '">Edit</a>';
				echo '<a class="aksi-link button" style="background:#d90429;" href="?delete=' . $row['idkode_tindakan_terapi'] . '" onclick="return confirm(\'Hapus data ini?\')">Delete</a>';
				echo '</td>';
				echo '</tr>';
			}
			echo '</table>';
		} else {
			echo 'Error: ' . $result['message'];
		}
		$db->close_connection();
		?>
	</div>
</body>
</html>
