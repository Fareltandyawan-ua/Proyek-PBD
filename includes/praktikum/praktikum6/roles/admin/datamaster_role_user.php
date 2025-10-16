<?php
session_start();
include_once "../../database/dbconnection.php";
include_once "../../class/class_user_role.php";

// Inisialisasi koneksi database
$db = new DBConnection();
$db->init_connect();
$dbconn = $db->dbconn;

if (!isset($_SESSION['user'])) {
    header('Location: ../../auth/login.php');
    exit();
}

// Proses tambah role jika ada POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['iduser'], $_POST['idrole'])) {
    UserRole::addRoleToUser($dbconn, (int)$_POST['iduser'], (int)$_POST['idrole']);
    header("Location: datamaster_role_user.php");
    exit();
}

// Ambil data user beserta role-nya dengan OOP
$data = UserRole::getAllWithRole($dbconn);

// Ambil semua role untuk pilihan tambah
$roles = [];
$result = $dbconn->query("SELECT idrole, nama_role FROM role");
while ($row = $result->fetch_assoc()) {
    $roles[] = $row;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../../css/datamaster_role_user.css">
    <title>Manajemen Role User</title>
    <script>
        function showForm(iduser) {
            document.getElementById('popup-form').classList.add('active');
            document.getElementById('iduser_input').value = iduser;
        }
        function hideForm() {
            document.getElementById('popup-form').classList.remove('active');
        }
    </script>
</head>
<body>
    <div class="header">
        <div class="logo"></div>
        <h2>Manajemen Role User</h2>
        <div class="nav">
            <a href="../../auth/logout.php">Logout</a>
        </div>
    </div>
    <div class="content">
        <table>
            <tr>
                <th>ID User</th>
                <th>Nama</th>
                <th>Role</th>
                <th>Aksi</th>
            </tr>
            <?php foreach ($data as $user): ?>
            <tr>
                <td><?= htmlspecialchars($user['iduser']) ?></td>
                <td><?= htmlspecialchars($user['nama']) ?></td>
                <td style="text-align:left">
                    <?php foreach ($user['roles'] as $role): ?>
                        <?= htmlspecialchars($role['nama_role']) ?>
                        <?php if ($role['status'] == 1): ?>
                            (Aktif)
                        <?php else: ?>
                            (Non-Aktif)
                        <?php endif; ?><br>
                    <?php endforeach; ?>
                </td>
                <td>
                    <a class="aksi-link" href="javascript:void(0)" onclick="showForm(<?= $user['iduser'] ?>)">Tambah Role</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <br>
        <a class="aksi-link" href="data_master.php"><- Kembali ke Data Master</a>
    </div>

    <!-- Popup Form Tambah Role -->
    <div class="popup-form" id="popup-form">
        <div class="popup-content">
            <form method="post" action="datamaster_role_user.php">
                <input type="hidden" name="iduser" id="iduser_input" value="">
                <label>Pilih Role:</label>
                <select name="idrole" required>
                    <option value="">-- Pilih Role --</option>
                    <?php foreach ($roles as $role): ?>
                        <option value="<?= $role['idrole'] ?>"><?= htmlspecialchars($role['nama_role']) ?></option>
                    <?php endforeach; ?>
                </select>
                <br>
                <button type="submit" class="tambah-btn">Simpan</button>
                <button type="button" class="tambah-btn" onclick="hideForm()">Batal</button>
            </form>
        </div>
    </div>
</body>
</html>
<?php $dbconn->close(); ?>
