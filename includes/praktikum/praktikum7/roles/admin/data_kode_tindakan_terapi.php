<?php
require_once '../../database/dbconnection.php';
require_once '../../class/class_kode_tindakan_terapi.php';

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
        $ktt = new KodeTindakanTerapi(0, $kode, $deskripsi, $idkategori, $idkategori_klinis);
        $ktt->create($db->dbconn);
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
        $ktt = new KodeTindakanTerapi($id, $kode, $deskripsi, $idkategori, $idkategori_klinis);
        $ktt->update($db->dbconn);
        header('Location: data_kode_tindakan_terapi.php');
        exit;
    }
}

// DELETE
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    if ($id) {
        $ktt = new KodeTindakanTerapi($id);
        $ktt->delete($db->dbconn);
        header('Location: data_kode_tindakan_terapi.php');
        exit;
    }
}

// Ambil data untuk edit
$edit_data = null;
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $ktt = KodeTindakanTerapi::getById($db->dbconn, $id);
    if ($ktt) {
        $edit_data = [
            'idkode_tindakan_terapi' => $ktt->getId(),
            'kode' => $ktt->getKode(),
            'deskripsi_tindakan_terapi' => $ktt->getDeskripsi(),
            'idkategori' => $ktt->getIdKategori(),
            'idkategori_klinis' => $ktt->getIdKategoriKlinis()
        ];
    }
}

$data_ktt = KodeTindakanTerapi::getAllWithJoin($db->dbconn);

?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../../css/data_master.css">
    <link rel="stylesheet" type="text/css" href="../../css/data_kode_tindakan_terapi.css">
    <title>Data Kode Tindakan Terapi</title>
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
        if ($data_ktt) {
            echo '<table>';
            echo '<tr><th>ID</th><th>Kode</th><th>Deskripsi Tindakan</th><th>Kategori</th><th>Kategori Klinis</th><th>Aksi</th></tr>';
            foreach ($data_ktt as $row) {
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