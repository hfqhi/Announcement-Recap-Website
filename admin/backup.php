<?php
// admin/backup.php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth_guard.php';

// Force browser to download the output as a .sql file
header('Content-Type: application/sql');
header('Content-Disposition: attachment; filename="cpe_backup_' . date('Y-m-d_H-i-s') . '.sql"');

echo "-- CPE-3B Automated Database Backup\n";
echo "-- Generated: " . date('Y-m-d H:i:s') . "\n\n";

// Fetch all tables in the current database
$tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);

foreach ($tables as $table) {
    // Extract the exact CREATE TABLE structure
    $createTable = $pdo->query("SHOW CREATE TABLE `$table`")->fetch(PDO::FETCH_ASSOC);
    echo "DROP TABLE IF EXISTS `$table`;\n";
    echo $createTable['Create Table'] . ";\n\n";

    // Extract all row data
    $rows = $pdo->query("SELECT * FROM `$table`")->fetchAll(PDO::FETCH_ASSOC);
    foreach ($rows as $row) {
        // Escape values securely
        $values = array_map(fn($val) => $val === null ? 'NULL' : $pdo->quote($val), array_values($row));
        echo "INSERT INTO `$table` VALUES (" . implode(", ", $values) . ");\n";
    }
    echo "\n\n";
}

// Log the manual backup action
logAction($pdo, $_SESSION['admin_id'], null, 'created', null, '"Manual Database Backup Downloaded"');
exit();
