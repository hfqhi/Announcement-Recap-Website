<?php
// config/config.php

// 1. Strict Session Security
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_samesite', 'Strict');
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// 2. CSRF Token Generation
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// 3. Environment & Database
define('IS_PRODUCTION', true); // SET TO TRUE NOW to hide system paths from users
define('DB_HOST', 'localhost');
define('DB_USER', 'cpe_user'); // DO NOT LEAVE EMPTY
define('DB_PASS', 'PortalPass123!'); // DO NOT LEAVE EMPTY
define('DB_NAME', 'db_announcement_system');

// --- DATABASE CONNECTION ---
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
} catch (PDOException $e) {
    die("Error connecting to the system."); // Simple message for users
}

// 4. App Settings
define('APP_NAME', 'CPE2B Announcements');
define('BASE_URL', '');
define('SEMESTER_START', '2026-02-09');
date_default_timezone_set('Asia/Manila');

// Centralized Color Map
const COLOR_THEMES = [
    'bg-sciets' => ['name' => 'Pink (SCIETS)', 'hex' => '#f48fb1'],
    'bg-contwo' => ['name' => 'Teal (CONTWO)', 'hex' => '#4dd0e1'],
    'bg-eneco' => ['name' => 'Indigo (ENECO)', 'hex' => '#7986cb'],
    'bg-eceng' => ['name' => 'Yellow (ECENG)', 'hex' => '#ffd54f'],
    'bg-softdes' => ['name' => 'Green (SOFTDES)', 'hex' => '#81c784'],
    'bg-numerical' => ['name' => 'Purple (NUMERICAL)', 'hex' => '#ba68c8'],
    'bg-rizal' => ['name' => 'Orange (RIZAL)', 'hex' => '#ffb74d'],
    'bg-pehef2' => ['name' => 'Light Blue (PEHEF2)', 'hex' => '#4fc3f7'],
    'bg-other' => ['name' => 'Gray (Other)', 'hex' => '#9e9e9e']
];

// 5. Error Reporting
if (IS_PRODUCTION) {
    error_reporting(0);
    ini_set('display_errors', 0);
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}