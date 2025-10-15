<?php
include_once "dbconnection.php";
include_once "classes.php";
session_start();

// Inisialisasi koneksi database
$db = new DBConnection();
$db->init_connect();
$dbconn = $db->dbconn;

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
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
    <title>Manajemen Role User</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; }
        .header { background-color: #4FC3F7; color: white; padding: 10px 20px; display: flex; justify-content: space-between; align-items: center; }
        .logo { width: 40px; height: 40px; background-color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; }
        .nav a { color: white; text-decoration: none; margin-left: 20px; }
        .content { padding: 20px; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #888; padding: 8px; text-align: center; }
        th { background-color: #e0e0e0; }
        .aksi-link { color: #1a0dab; text-decoration: underline; cursor: pointer; margin: 0 5px; }
        .tambah-btn { margin-bottom: 10px; padding: 6px 16px; background: #4FC3F7; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .tambah-btn:hover { background: #039be5; }
        .popup-form { display: none; position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(0,0,0,0.3); justify-content: center; align-items: center; }
        .popup-form.active { display: flex; }
        .popup-content { background: #fff; padding: 24px; border-radius: 8px; min-width: 300px; }
        .popup-content select, .popup-content button { margin-top: 10px; }
    </style>
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
            <a href="data_master.php">Data Master</a>
            <a href="logout.php">Logout</a>
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
