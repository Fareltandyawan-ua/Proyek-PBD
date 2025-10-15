<?php

require_once '../../database/dbconnection.php';
require_once '../../class/class_pemilik.php';


$db = new DBConnection();
$db->init_connect();
$pemilik = new Pemilik($db->dbconn);

// Ambil data user untuk select option
$user = $db->send_query('SELECT * FROM user');

// CREATE
if (isset($_POST['tambah'])) {
	$pemilik->create($_POST);
}

// UPDATE
if (isset($_POST['update'])) {
	$pemilik->update($_POST);
}

// DELETE
if (isset($_GET['delete'])) {
	$pemilik->delete($_GET['delete']);
}

// Ambil data untuk edit
$edit_data = null;
if (isset($_GET['edit'])) {
	$edit_data = $pemilik->getById($_GET['edit']);
}

$result = $pemilik->getAllWithUser();

?>
<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="../../css/data_master.css">
	<link rel="stylesheet" type="text/css" href="../../css/data_pemilik.css">
	<title>Data Pemilik</title>

</head>
<body>
	<div class="header">
		<div class="logo"></div>
		<h2>Data Pemilik</h2>
		<div class="nav">
			<a href="dashboard_admin.php">Dashboard</a>
			<a href="../../auth/logout.php">Logout</a>
		</div>
	</div>
	<div class="pemilik-container">
		<?php
		// Form tambah/edit
		echo '<form method="post">';
		   if (is_array($edit_data)) {
			   echo '<input type="hidden" name="idpemilik" value="' . htmlspecialchars($edit_data['idpemilik']) . '">';
			   echo '<select name="iduser" required><option value="">Pilih User</option>';
			   foreach ($user['data'] as $u) {
				   $selected = ($edit_data['iduser'] == $u['iduser']) ? 'selected' : '';
				   echo '<option value="' . $u['iduser'] . '" ' . $selected . '>' . htmlspecialchars($u['nama']) . ' (' . htmlspecialchars($u['email']) . ')</option>';
			   }
			   echo '</select> ';
			   echo '<input type="text" name="no_wa" value="' . htmlspecialchars($edit_data['no_wa']) . '" placeholder="No. WA" required> ';
			   echo '<input type="text" name="alamat" value="' . htmlspecialchars($edit_data['alamat']) . '" placeholder="Alamat" required> ';
			   echo '<button type="submit" name="update">Update</button>';
			   echo '<a href="data_pemilik.php" class="button batal">Batal</a>';
		   } else {
			echo '<select name="iduser" required><option value="">Pilih User</option>';
			foreach ($user['data'] as $u) {
				echo '<option value="' . $u['iduser'] . '">' . htmlspecialchars($u['nama']) . ' (' . htmlspecialchars($u['email']) . ')</option>';
			}
			echo '</select> ';
			echo '<input type="text" name="no_wa" placeholder="No. WA" required> ';
			echo '<input type="text" name="alamat" placeholder="Alamat" required> ';
			echo '<button type="submit" name="tambah">Tambah</button>';
		}
		echo '</form>';

		// Tabel data
		if ($result['status'] === 'success') {
			echo '<table>';
			echo '<tr><th>ID</th><th>Nama</th><th>Email</th><th>No. WA</th><th>Alamat</th><th>Aksi</th></tr>';
			foreach ($result['data'] as $row) {
				echo '<tr>';
				echo '<td>' . $row['idpemilik'] . '</td>';
				echo '<td>' . $row['nama'] . '</td>';
				echo '<td>' . $row['email'] . '</td>';
				echo '<td>' . $row['no_wa'] . '</td>';
				echo '<td>' . $row['alamat'] . '</td>';
				echo '<td>';
				echo '<a class="aksi-link button" href="?edit=' . $row['idpemilik'] . '">Edit</a>';
				echo '<a class="aksi-link button" style="background:#d90429;" href="?delete=' . $row['idpemilik'] . '" onclick="return confirm(\'Hapus data ini?\')">Delete</a>';
				echo '</td>';
				echo '</tr>';
			}
			echo '</table>';
		} else {
			echo 'Error: ' . $result['message'];
		}
		$db->close_connection();
		?>
		<br>
		<a href="data_master.php" class="btn-kembali">
            <span class="btn-icon">←</span>
            <span class="btn-text">Kembali</span>
        </a>
	</div>
</body>
</html>
