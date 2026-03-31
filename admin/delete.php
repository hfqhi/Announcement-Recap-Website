<?php
// admin/delete.php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth_guard.php';
require_once __DIR__ . '/../includes/helpers.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = (int)$_POST['id'];

    // Soft Delete: Update status to archived
    $stmt = $pdo->prepare("UPDATE tbl_announcements SET status = 'archived' WHERE id = ?");
    $stmt->execute([$id]);

    logAction($pdo, $_SESSION['admin_id'], $id, 'archived (soft delete)');
}
header("Location: index.php");
exit();