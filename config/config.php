<?php
// config/config.php
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_samesite', 'Strict');

if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
    ini_set('session.cookie_secure', 1);
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

define('DB_HOST', 'localhost');
define('DB_USER', 'cpe_user');
define('DB_PASS', 'PortalPass123!');
define('DB_NAME', 'db_announcement_system');

define('APP_NAME', 'CPE3B Announcements');
define('BASE_URL', '');
define('SEMESTER_START', '2026-08-10');
date_default_timezone_set('Asia/Manila');

const COLOR_THEMES = [
    'bg-sciets' => ['name' => 'Pink', 'hex' => '#f48fb1'],
    'bg-contwo' => ['name' => 'Teal', 'hex' => '#4dd0e1'],
    'bg-eneco' => ['name' => 'Indigo', 'hex' => '#7986cb'],
    'bg-eceng' => ['name' => 'Yellow', 'hex' => '#ffd54f'],
    'bg-softdes' => ['name' => 'Green', 'hex' => '#81c784'],
    'bg-numerical' => ['name' => 'Purple', 'hex' => '#ba68c8'],
    'bg-rizal' => ['name' => 'Orange', 'hex' => '#ffb74d'],
    'bg-pehef2' => ['name' => 'Light Blue', 'hex' => '#4fc3f7'],
    'bg-other' => ['name' => 'Gray', 'hex' => '#9e9e9e'],
    'bg-cpe' => ['name' => 'Red', 'hex' => '#e57373']
];
