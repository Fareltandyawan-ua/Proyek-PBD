<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Authentication</title>
    <link rel="stylesheet" type="text/css" href="../css/login.css">
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500&display=swap" rel="stylesheet">
</head>
<body>
    <div class="main">
        <input type="checkbox" id="chk" aria-hidden="true">

        <div class="login">
            <form action="login_post.php" method="post">
                <label for="chk" aria-hidden="true">Login</label>
                <input type="email" name="email" placeholder="Email" required="">
                <input type="password" name="password" placeholder="Password" required="">
                <button>Login</button>
                <?php 
                if (isset($_SESSION['flash_msg'])): ?>
                    <div class="flash-msg <?= isset($_SESSION['flash_success']) && $_SESSION['flash_success'] ? 'success' : 'error' ?>">
                        <?= $_SESSION['flash_msg']; unset($_SESSION['flash_msg'], $_SESSION['flash_success']); ?>
                    </div>
                <?php endif; ?>
            </form>
        </div>


        <!-- Registrasi -->
        <div class="signup">
            <form action="proses_registrasi.php" method="post">
                <label for="chk" aria-hidden="true">Sign Up</label>
                <input type="text" name="nama" placeholder="Nama" required="">
                <input type="email" name="email" placeholder="Email" required="">
                <input type="password" name="password" placeholder="Password" required="">
                <input type="password" name="retype_password" placeholder="Retype Password" required="">
                <button>Sign Up</button>
                <?php 
                if (isset($_SESSION['flash_msg'])): ?>
                    <div class="flash-msg <?= isset($_SESSION['flash_success']) && $_SESSION['flash_success'] ? 'success' : 'error' ?>">
                        <?= $_SESSION['flash_msg']; unset($_SESSION['flash_msg'], $_SESSION['flash_success']); ?>
                    </div>
                <?php endif; ?>
            </form>
        </div>
    </div>
</body>
</html>

