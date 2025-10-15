<?php
require_once '../../database/dbconnection.php';
require_once '../../class/class_data_kategori.php';

$db = new DBConnection();
$db->init_connect();

// CREATE
if (isset($_POST['tambah'])) {
    $nama = trim($_POST['nama_kategori']);
    if ($nama !== '') {
        $kategori = new Kategori(0, $nama);
        $kategori->create($db->dbconn);
        header('Location: data_kategori.php');
        exit;
    }
}

// UPDATE
if (isset($_POST['update'])) {
    $id = intval($_POST['idkategori']);
    $nama = trim($_POST['nama_kategori']);
    if ($id && $nama !== '') {
        $kategori = new Kategori($id, $nama);
        $kategori->update($db->dbconn);
        header('Location: data_kategori.php');
        exit;
    }
}

// DELETE
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    if ($id) {
        $kategori = new Kategori($id);
        $kategori->delete($db->dbconn);
        header('Location: data_kategori.php');
        exit;
    }
}

// Ambil data untuk edit
$edit_data = null;
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $kategori = Kategori::getById($db->dbconn, $id);
    if ($kategori) {
        $edit_data = [
            'idkategori' => $kategori->getIdkategori(),
            'nama_kategori' => $kategori->getNamaKategori()
        ];
    }
}

$data_kategori = Kategori::getAll($db->dbconn);

?>
<!DOCTYPE html>
<html lang="id">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="../../css/data_master.css">
	<link rel="stylesheet" type="text/css" href="../../css/data_kategori.css">
	<title>Data Kategori</title>
</head>

<body>
	<div class="header">
		<div class="logo"></div>
		<h2>Data Kategori</h2>
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
			echo '<input type="hidden" name="idkategori" value="' . $edit_data['idkategori'] . '">';
			echo '<input type="text" name="nama_kategori" value="' . htmlspecialchars($edit_data['nama_kategori']) . '" required> ';
			echo '<button type="submit" name="update">Update</button>';
			echo '<a href="data_kategori.php" class="button batal">Batal</a>';
		} else {
			echo '<input type="text" name="nama_kategori" placeholder="Nama Kategori" required> ';
			echo '<button type="submit" name="tambah">Tambah</button>';
		}
		echo '</form>';

		// Tabel data
				if ($data_kategori) {
					echo '<table>';
					echo '<tr><th>ID</th><th>Nama Kategori</th><th>Aksi</th></tr>';
					foreach ($data_kategori as $kategori) {
						echo '<tr>';
						echo '<td>' . $kategori->getIdkategori() . '</td>';
						echo '<td>' . $kategori->getNamaKategori() . '</td>';
						echo '<td>';
						echo '<a class="aksi-link button" href="?edit=' . $kategori->getIdkategori() . '">Edit</a>';
						echo '<a class="aksi-link button" style="background:#d90429;" href="?delete=' . $kategori->getIdkategori() . '" onclick="return confirm(\'Hapus data ini?\')">Delete</a>';
						echo '</td>';
						echo '</tr>';
					}
					echo '</table>';
				} else {
					echo 'Tidak ada data.';
				}
				$db->close_connection();
		?>
		<br>
		<br>
		<a href="data_master.php" class="btn-kembali">
            <span class="btn-icon">←</span>
            <span class="btn-text">Kembali</span>
        </a>
    </div>
</body>
</html>