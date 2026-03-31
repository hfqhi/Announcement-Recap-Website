<?php
// config/config.php
session_start();

// Database Constants
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', ''); // Change if you have a password in Laragon
define('DB_NAME', 'db_announcement_system');

// App Settings
define('APP_NAME', 'CPE2B Announcements');
define('BASE_URL', '/announcement-system'); // Adjust based on your Laragon folder structure

// Timezone
date_default_timezone_set('Asia/Manila');