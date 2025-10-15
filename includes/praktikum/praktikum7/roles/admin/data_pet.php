<?php
require_once '../../database/dbconnection.php';
require_once '../../class/class_pet.php';

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
    if ($jenis_kelamin === 'Jantan') $jenis_kelamin = 'L';
    if ($jenis_kelamin === 'Betina') $jenis_kelamin = 'P';
    $idpemilik = intval($_POST['idpemilik']);
    $idras_hewan = intval($_POST['idras_hewan']);
    if ($nama !== '' && $tanggal_lahir !== '' && $warna_tanda !== '' && $jenis_kelamin !== '' && $idpemilik && $idras_hewan) {
        $pet = new Pet(0, $nama, $tanggal_lahir, $warna_tanda, $jenis_kelamin, $idpemilik, $idras_hewan);
        $pet->create($db->dbconn);
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
        $pet = new Pet($id, $nama, $tanggal_lahir, $warna_tanda, $jenis_kelamin, $idpemilik, $idras_hewan);
        $pet->update($db->dbconn);
        header('Location: data_pet.php');
        exit;
    }
}

// DELETE
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    if ($id) {
        $pet = new Pet($id);
        $pet->delete($db->dbconn);
        header('Location: data_pet.php');
        exit;
    }
}

// Ambil data untuk edit
$edit_data = null;
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $pet = Pet::getById($db->dbconn, $id);
    if ($pet) {
        $edit_data = [
            'idpet' => $pet->getIdpet(),
            'nama' => $pet->getNama(),
            'tanggal_lahir' => $pet->getTanggalLahir(),
            'warna_tanda' => $pet->getWarnaTanda(),
            'jenis_kelamin' => $pet->getJenisKelamin(),
            'idpemilik' => $pet->getIdpemilik(),
            'idras_hewan' => $pet->getIdrasHewan()
        ];
    }
}

$data_pet = Pet::getAllWithJoin($db->dbconn);

?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../../css/data_master.css">
    <link rel="stylesheet" type="text/css" href="../../css/data_pet.css">
    <title>Data Pet</title>
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
        if ($data_pet) {
            echo '<table>';
            echo '<tr><th>ID</th><th>Nama Pet</th><th>Tanggal Lahir</th><th>Warna/Tanda</th><th>Jenis Kelamin</th><th>Pemilik</th><th>Ras Hewan</th><th>Aksi</th></tr>';
            foreach ($data_pet as $row) {
                echo '<tr>';
                echo '<td>' . $row['idpet'] . '</td>';
                echo '<td>' . $row['nama_pet'] . '</td>';
                echo '<td>' . $row['tanggal_lahir'] . '</td>';
                echo '<td>' . $row['warna_tanda'] . '</td>';
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
            echo 'Tidak ada data.';
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