<?php
include '../../config/database.php';
include '../../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_vendor = $_POST['nama_vendor'];
    $badan_hukum = $_POST['badan_hukum'];
    $status = $_POST['status'];

    $query = "INSERT INTO vendor (nama_vendor, badan_hukum, status) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $nama_vendor, $badan_hukum, $status);

    if ($stmt->execute()) {
        header("Location: index.php?message=Vendor added successfully");
    } else {
        echo "Error: " . $stmt->error;
    }
}

include '../../includes/header.php';
include '../../includes/sidebar.php';
?>

<div class="container">
    <h2>Add New Vendor</h2>
    <form method="POST" action="">
        <div class="form-group">
            <label for="nama_vendor">Vendor Name:</label>
            <input type="text" class="form-control" id="nama_vendor" name="nama_vendor" required>
        </div>
        <div class="form-group">
            <label for="badan_hukum">Legal Entity:</label>
            <select class="form-control" id="badan_hukum" name="badan_hukum" required>
                <option value="P">PT</option>
                <option value="C">CV</option>
                <option value="N">Not Registered</option>
            </select>
        </div>
        <div class="form-group">
            <label for="status">Status:</label>
            <select class="form-control" id="status" name="status" required>
                <option value="A">Active</option>
                <option value="I">Inactive</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Add Vendor</button>
    </form>
</div>

<?php
include '../../includes/footer.php';
?>