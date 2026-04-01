<?php
// admin/delete.php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth_guard.php';
require_once __DIR__ . '/../includes/helpers.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['action'], $_POST['csrf_token'])) {
    verifyCsrf($_POST['csrf_token']); // Security Check

    $id = (int)$_POST['id'];
    $action = $_POST['action'];

    if ($action === 'archive') {
        $pdo->prepare("UPDATE tbl_announcements SET status = 'archived' WHERE id = ?")->execute([$id]);
        logAction($pdo, $_SESSION['admin_id'], $id, 'archived');
        setFlash('warning', 'Announcement archived successfully.');
    } elseif ($action === 'restore') {
        $pdo->prepare("UPDATE tbl_announcements SET status = 'active' WHERE id = ?")->execute([$id]);
        logAction($pdo, $_SESSION['admin_id'], $id, 'restored');
        setFlash('success', 'Announcement restored!');
    } elseif ($action === 'hard_delete') {
        logAction($pdo, $_SESSION['admin_id'], null, 'permanently deleted (ID:' . $id . ')');
        $pdo->prepare("DELETE FROM tbl_announcements WHERE id = ?")->execute([$id]);
        setFlash('danger', 'Announcement permanently deleted.');
    }
}
header("Location: index.php");
exit();
