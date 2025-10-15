<?php
session_start();
include '../../config/database.php';
include '../../includes/functions.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_GET['id'];
$query = "SELECT * FROM user WHERE iduser = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("Location: index.php");
    exit();
}

$user = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    $update_query = "UPDATE user SET username = ?, password = ?, idrole = ? WHERE iduser = ?";
    $update_stmt = $conn->prepare($update_query);
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $update_stmt->bind_param("ssii", $username, $hashed_password, $role, $user_id);

    if ($update_stmt->execute()) {
        header("Location: index.php?message=User updated successfully");
        exit();
    } else {
        $error = "Failed to update user.";
    }
}

include '../../includes/header.php';
include '../../includes/sidebar.php';
?>

<div class="container">
    <h2>Edit User</h2>
    <?php if (isset($error)) { echo "<div class='alert alert-danger'>$error</div>"; } ?>
    <form method="POST">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" class="form-control" id="username" name="username" value="<?php echo $user['username']; ?>" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="form-group">
            <label for="role">Role</label>
            <select class="form-control" id="role" name="role" required>
                <option value="1" <?php echo ($user['idrole'] == 1) ? 'selected' : ''; ?>>Admin</option>
                <option value="2" <?php echo ($user['idrole'] == 2) ? 'selected' : ''; ?>>Kasir</option>
                <option value="3" <?php echo ($user['idrole'] == 3) ? 'selected' : ''; ?>>Gudang</option>
                <option value="4" <?php echo ($user['idrole'] == 4) ? 'selected' : ''; ?>>Pemilik</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update User</button>
    </form>
</div>

<?php include '../../includes/footer.php'; ?>