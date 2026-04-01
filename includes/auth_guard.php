<?php
// includes/auth_guard.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['admin_id'])) {
    header("Location: " . BASE_URL . "/auth/login.php");
    exit();
}
