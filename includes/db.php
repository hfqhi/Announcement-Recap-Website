<?php
// includes/db.php
require_once __DIR__ . '/../config/config.php';

try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    // Force MySQL to use Manila time for all NOW() and CURRENT_TIMESTAMP operations
    $pdo->exec("SET time_zone = '+08:00'");
} catch (PDOException $e) {
    die("Database Connection Failed.");
}
