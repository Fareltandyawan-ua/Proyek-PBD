<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function checkLogin() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: ../auth/login.php');
        exit;
    }
}

function checkRole($allowed_roles = []) {
    checkLogin();
    
    if (!empty($allowed_roles) && !in_array($_SESSION['role_id'], $allowed_roles)) {
        header('Location: ../dashboard/index.php?error=access_denied');
        exit;
    }
}

function getUserRole() {
    return $_SESSION['role_name'] ?? '';
}

function getUserId() {
    return $_SESSION['user_id'] ?? 0;
}

function getUsername() {
    return $_SESSION['username'] ?? '';
}
?>