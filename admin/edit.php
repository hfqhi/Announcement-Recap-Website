<?php
// admin/edit.php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth_guard.php';
require_once __DIR__ . '/../includes/helpers.php';

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM tbl_announcements WHERE id = ?");
$stmt->execute([$id]);
$item = $stmt->fetch();

if (!$item) { header("Location: index.php"); exit(); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject = sanitize($_POST['subject']);
    $title = sanitize($_POST['title']);
    $content = sanitize($_POST['content']);
    $due_date = !empty($_POST['due_date']) ? $_POST['due_date'] : null;
    $status = sanitize($_POST['status']);

    $updateStmt = $pdo->prepare("UPDATE tbl_announcements SET subject=?, title=?, content=?, due_date=?, status=? WHERE id=?");
    $updateStmt->execute([$subject, $title, $content, $due_date, $status, $id]);

    logAction($pdo, $_SESSION['admin_id'], $id, 'updated', $item, $_POST);

    header("Location: index.php");
    exit();
}
$pageTitle = "Edit Announcement";
include __DIR__ . '/../includes/header.php';
?>
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white"><h4>Edit Announcement</h4></div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label>Subject</label>
                        <select name="subject" class="form-select" required>
                            <?php foreach(getSubjectOptions() as $opt): ?>
                                <option value="<?= $opt ?>" <?= $item['subject'] == $opt ? 'selected' : '' ?>><?= $opt ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Title</label>
                        <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($item['title']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label>Content</label>
                        <textarea name="content" class="form-control" rows="5" required><?= htmlspecialchars($item['content']) ?></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Due Date</label>
                            <input type="date" name="due_date" class="form-control" value="<?= $item['due_date'] ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Status</label>
                            <select name="status" class="form-select">
                                <option value="active" <?= $item['status'] == 'active' ? 'selected' : '' ?>>Active</option>
                                <option value="archived" <?= $item['status'] == 'archived' ? 'selected' : '' ?>>Archived</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="index.php" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>