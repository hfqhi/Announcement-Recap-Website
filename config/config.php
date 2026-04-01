<?php
// config/config.php
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_samesite', 'Strict');

// Auto-enable Secure Cookies if on HTTPS
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

define('APP_NAME', 'CPE2B Announcements');
define('BASE_URL', '');
define('SEMESTER_START', '2026-02-09');
date_default_timezone_set('Asia/Manila');

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
