<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth_guard.php';
require_once __DIR__ . '/../includes/helpers.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['action'], $_POST['csrf_token'])) {
    verifyCsrf($_POST['csrf_token']);
    $id = (int)$_POST['id'];
    $action = $_POST['action'];

    $allowedTables = ['tbl_announcements', 'tbl_subjects'];
    $table = $_POST['table'] ?? 'tbl_announcements';

    if (!in_array($table, $allowedTables)) {
        die("Invalid table reference.");
    }

    $stmt = $pdo->prepare("SELECT * FROM $table WHERE id = ?");
    $stmt->execute([$id]);
    $targetData = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($targetData) {
        if ($action === 'archive') {
            $pdo->prepare("UPDATE $table SET status = 'archived' WHERE id = ?")->execute([$id]);
            logAction($pdo, $_SESSION['admin_id'], $id, 'archived', null, json_encode($targetData));
            setFlash('warning', 'Archived successfully.');
        } elseif ($action === 'restore') {
            $pdo->prepare("UPDATE $table SET status = 'active' WHERE id = ?")->execute([$id]);
            logAction($pdo, $_SESSION['admin_id'], $id, 'restored', null, json_encode($targetData));
            setFlash('success', 'Restored!');
        } elseif ($action === 'hard_delete') {
            logAction($pdo, $_SESSION['admin_id'], null, 'hard_deleted', json_encode($targetData), null, $id);
            $pdo->prepare("DELETE FROM $table WHERE id = ?")->execute([$id]);
            setFlash('danger', 'Permanently deleted.');
        }
    }

    $redirectUrl = ($table === 'tbl_subjects') ? 'subjects.php' : 'index.php';
    header("Location: " . $redirectUrl);
    exit();
} // <-- Added missing closing brace

header("Location: index.php");
exit();
