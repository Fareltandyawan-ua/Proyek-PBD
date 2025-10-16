<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Registrasi</title>
</head>
<body>
    <h1>Halaman Registrasi</h1>
    <form action="../auth/proses_registrasi.php" method="POST">
        <label for="nama">Nama:</label>
        <input type="text" id="nama" name="nama" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <label for="retype_password">Retype Password:</label>
        <input type="password" id="retype_password" name="retype_password" required><br><br>
           <?php
        if (isset($_SESSION['flash_msg'])) {
            echo "<p style='color:red;'>".$_SESSION['flash_msg']."</p>";
        }
        ?>
        <input type="submit" value="Daftar">

        
        </form>    
</body>
</html>