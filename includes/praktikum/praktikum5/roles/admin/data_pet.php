<?php
require_once '../../database/dbconnection.php';

$db = new DBConnection();
$db->init_connect();

// Ambil data pemilik dan ras hewan untuk select option
$pemilik = $db->send_query('SELECT p.idpemilik, u.nama FROM pemilik p LEFT JOIN user u ON p.iduser = u.iduser');
$ras_hewan = $db->send_query('SELECT * FROM ras_hewan');

// CREATE
if (isset($_POST['tambah'])) {
    $nama = trim($_POST['nama_pet']);
    $tanggal_lahir = trim($_POST['tanggal_lahir']);
    $warna_tanda = trim($_POST['warna_tanda']);
	$jenis_kelamin = trim($_POST['jenis_kelamin']);
	// Konversi label ke value singkat
	if ($jenis_kelamin === 'Jantan') $jenis_kelamin = 'L';
	if ($jenis_kelamin === 'Betina') $jenis_kelamin = 'P';
    $idpemilik = intval($_POST['idpemilik']);
    $idras_hewan = intval($_POST['idras_hewan']);
    if ($nama !== '' && $tanggal_lahir !== '' && $warna_tanda !== '' && $jenis_kelamin !== '' && $idpemilik && $idras_hewan) {
        $stmt = $db->dbconn->prepare('INSERT INTO pet (nama, tanggal_lahir, warna_tanda, jenis_kelamin, idpemilik, idras_hewan) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->bind_param('ssssii', $nama, $tanggal_lahir, $warna_tanda, $jenis_kelamin, $idpemilik, $idras_hewan);
        $stmt->execute();
        $stmt->close();
        header('Location: data_pet.php');
        exit;
    }
}

// UPDATE
if (isset($_POST['update'])) {
    $id = intval($_POST['idpet']);
    $nama = trim($_POST['nama_pet']);
    $tanggal_lahir = trim($_POST['tanggal_lahir']);
    $warna_tanda = trim($_POST['warna_tanda']);
	$jenis_kelamin = trim($_POST['jenis_kelamin']);
	if ($jenis_kelamin === 'Jantan') $jenis_kelamin = 'L';
	if ($jenis_kelamin === 'Betina') $jenis_kelamin = 'P';
    $idpemilik = intval($_POST['idpemilik']);
    $idras_hewan = intval($_POST['idras_hewan']);
    if ($id && $nama !== '' && $tanggal_lahir !== '' && $warna_tanda !== '' && $jenis_kelamin !== '' && $idpemilik && $idras_hewan) {
        $stmt = $db->dbconn->prepare('UPDATE pet SET nama=?, tanggal_lahir=?, warna_tanda=?, jenis_kelamin=?, idpemilik=?, idras_hewan=? WHERE idpet=?');
        $stmt->bind_param('ssssiii', $nama, $tanggal_lahir, $warna_tanda, $jenis_kelamin, $idpemilik, $idras_hewan, $id);
        $stmt->execute();
        $stmt->close();
        header('Location: data_pet.php');
        exit;
    }
}

// DELETE
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    if ($id) {
        $stmt = $db->dbconn->prepare('DELETE FROM pet WHERE idpet=?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->close();
        header('Location: data_pet.php');
        exit;
    }
}

// Ambil data untuk edit
$edit_data = null;
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $stmt = $db->dbconn->prepare('SELECT * FROM pet WHERE idpet=?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result_edit = $stmt->get_result();
    $edit_data = $result_edit->fetch_assoc();
    $stmt->close();
}

$query = 'SELECT pt.idpet, pt.nama AS nama_pet, pt.tanggal_lahir, pt.warna_tanda, pt.jenis_kelamin, pm.nama AS nama_pemilik, rh.nama_ras, pt.idpemilik, pt.idras_hewan
          FROM pet pt
          LEFT JOIN pemilik p ON pt.idpemilik = p.idpemilik
          LEFT JOIN user pm ON p.iduser = pm.iduser
          LEFT JOIN ras_hewan rh ON pt.idras_hewan = rh.idras_hewan';
$result = $db->send_query($query);

?>
<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="../../css/data_master.css">
	<title>Data Pet</title>
	<style>
		.pet-container { max-width: 1000px; margin: 32px auto; background: #fff; border-radius: 10px; box-shadow: 0 2px 8px #0001; padding: 32px; }
		.pet-container h2 { text-align: center; color: #222; margin-bottom: 24px; }
		form { display: flex; gap: 8px; flex-wrap: wrap; justify-content: center; margin-bottom: 24px; }
		input[type="text"], input[type="date"], select { padding: 8px; border: 1px solid #b7e4c7; border-radius: 5px; min-width: 120px; }
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
		<h2>Data Pet</h2>
		<div class="nav">
			<a href="dashboard_admin.php">Dashboard</a>
			<a href="../../auth/logout.php">Logout</a>
		</div>
	</div>
	<div class="pet-container">
		<?php
		// Form tambah/edit
		echo '<form method="post">';
		if ($edit_data) {
			echo '<input type="hidden" name="idpet" value="' . $edit_data['idpet'] . '">';
			echo '<input type="text" name="nama_pet" value="' . htmlspecialchars($edit_data['nama']) . '" placeholder="Nama Pet" required> ';
			echo '<input type="date" name="tanggal_lahir" value="' . htmlspecialchars($edit_data['tanggal_lahir']) . '" required> ';
			echo '<input type="text" name="warna_tanda" value="' . htmlspecialchars($edit_data['warna_tanda']) . '" placeholder="Warna/Tanda" required> ';
		echo '<select name="jenis_kelamin" required>';
		$jk = $edit_data['jenis_kelamin'];
		echo '<option value="">Jenis Kelamin</option>';
		echo '<option value="L"' . ($jk == 'L' ? ' selected' : '') . '>Jantan</option>';
		echo '<option value="P"' . ($jk == 'P' ? ' selected' : '') . '>Betina</option>';
		echo '</select> ';
			echo '<select name="idpemilik" required><option value="">Pilih Pemilik</option>';
			foreach ($pemilik['data'] as $p) {
				$selected = $edit_data['idpemilik'] == $p['idpemilik'] ? 'selected' : '';
				echo '<option value="' . $p['idpemilik'] . '" ' . $selected . '>' . htmlspecialchars($p['nama']) . '</option>';
			}
			echo '</select> ';
			echo '<select name="idras_hewan" required><option value="">Pilih Ras Hewan</option>';
			foreach ($ras_hewan['data'] as $r) {
				$selected = $edit_data['idras_hewan'] == $r['idras_hewan'] ? 'selected' : '';
				echo '<option value="' . $r['idras_hewan'] . '" ' . $selected . '>' . htmlspecialchars($r['nama_ras']) . '</option>';
			}
			echo '</select> ';
			echo '<button type="submit" name="update">Update</button>';
			echo '<a href="data_pet.php" class="button batal">Batal</a>';
		} else {
			echo '<input type="text" name="nama_pet" placeholder="Nama Pet" required> ';
			echo '<input type="date" name="tanggal_lahir" required> ';
			echo '<input type="text" name="warna_tanda" placeholder="Warna/Tanda" required> ';
		echo '<select name="jenis_kelamin" required>';
		echo '<option value="">Jenis Kelamin</option>';
		echo '<option value="L">Jantan</option>';
		echo '<option value="P">Betina</option>';
		echo '</select> ';
			echo '<select name="idpemilik" required><option value="">Pilih Pemilik</option>';
			foreach ($pemilik['data'] as $p) {
				echo '<option value="' . $p['idpemilik'] . '">' . htmlspecialchars($p['nama']) . '</option>';
			}
			echo '</select> ';
			echo '<select name="idras_hewan" required><option value="">Pilih Ras Hewan</option>';
			foreach ($ras_hewan['data'] as $r) {
				echo '<option value="' . $r['idras_hewan'] . '">' . htmlspecialchars($r['nama_ras']) . '</option>';
			}
			echo '</select> ';
			echo '<button type="submit" name="tambah">Tambah</button>';
		}
		echo '</form>';

		// Tabel data
		if ($result['status'] === 'success') {
			echo '<table>';
			echo '<tr><th>ID</th><th>Nama Pet</th><th>Tanggal Lahir</th><th>Warna/Tanda</th><th>Jenis Kelamin</th><th>Pemilik</th><th>Ras Hewan</th><th>Aksi</th></tr>';
			foreach ($result['data'] as $row) {
				echo '<tr>';
				echo '<td>' . $row['idpet'] . '</td>';
				echo '<td>' . $row['nama_pet'] . '</td>';
				echo '<td>' . $row['tanggal_lahir'] . '</td>';
				echo '<td>' . $row['warna_tanda'] . '</td>';
			// Tampilkan label yang ramah di tabel
			$label_jk = $row['jenis_kelamin'] == 'L' ? 'Jantan' : ($row['jenis_kelamin'] == 'P' ? 'Betina' : $row['jenis_kelamin']);
			echo '<td>' . $label_jk . '</td>';
				echo '<td>' . $row['nama_pemilik'] . '</td>';
				echo '<td>' . $row['nama_ras'] . '</td>';
				echo '<td>';
				echo '<a class="aksi-link button" href="?edit=' . $row['idpet'] . '">Edit</a>';
				echo '<a class="aksi-link button" style="background:#d90429;" href="?delete=' . $row['idpet'] . '" onclick="return confirm(\'Hapus data ini?\')">Delete</a>';
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
