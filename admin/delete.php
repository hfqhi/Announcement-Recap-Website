<?php
// admin/delete.php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth_guard.php';
require_once __DIR__ . '/../includes/helpers.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['action'], $_POST['csrf_token'])) {
    verifyCsrf($_POST['csrf_token']);

    $id = (int)$_POST['id'];
    $action = $_POST['action'];

    // Fetch context for logging
    $stmt = $pdo->prepare("SELECT * FROM tbl_announcements WHERE id = ?");
    $stmt->execute([$id]);
    $targetData = $stmt->fetch();

    if ($targetData) {
        if ($action === 'archive') {
            $pdo->prepare("UPDATE tbl_announcements SET status = 'archived' WHERE id = ?")->execute([$id]);
            logAction($pdo, $_SESSION['admin_id'], $id, 'archived', null, $targetData);
            setFlash('warning', 'Announcement archived successfully.');
        } elseif ($action === 'restore') {
            $pdo->prepare("UPDATE tbl_announcements SET status = 'active' WHERE id = ?")->execute([$id]);
            logAction($pdo, $_SESSION['admin_id'], $id, 'restored', null, $targetData);
            setFlash('success', 'Announcement restored!');
        } elseif ($action === 'hard_delete') {
            // Log the deletion with the old data and the specific deleted_record_id parameter
            logAction($pdo, $_SESSION['admin_id'], null, 'hard_deleted', $targetData, null, $id);
            $pdo->prepare("DELETE FROM tbl_announcements WHERE id = ?")->execute([$id]);
            setFlash('danger', 'Announcement permanently deleted.');
        }
    }
}
header("Location: index.php");
exit();
