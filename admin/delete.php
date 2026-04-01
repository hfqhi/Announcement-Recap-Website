<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth_guard.php';
require_once __DIR__ . '/../includes/helpers.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['action'], $_POST['csrf_token'])) {
    verifyCsrf($_POST['csrf_token']);
    $id = (int)$_POST['id'];
    $action = $_POST['action'];

    $targetData = $pdo->query("SELECT * FROM tbl_announcements WHERE id = $id")->fetch();
    if ($targetData) {
        if ($action === 'archive') {
            $pdo->prepare("UPDATE tbl_announcements SET status = 'archived' WHERE id = ?")->execute([$id]);
            logAction($pdo, $_SESSION['admin_id'], $id, 'archived', null, $targetData);
            setFlash('warning', 'Archived successfully.');
        } elseif ($action === 'restore') {
            $pdo->prepare("UPDATE tbl_announcements SET status = 'active' WHERE id = ?")->execute([$id]);
            logAction($pdo, $_SESSION['admin_id'], $id, 'restored', null, $targetData);
            setFlash('success', 'Restored!');
        } elseif ($action === 'hard_delete') {
            logAction($pdo, $_SESSION['admin_id'], null, 'hard_deleted', $targetData, null, $id);
            $pdo->prepare("DELETE FROM tbl_announcements WHERE id = ?")->execute([$id]);
            setFlash('danger', 'Permanently deleted.');
        }
    }
}
header("Location: index.php");
exit();
