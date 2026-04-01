<?php
// admin/edit.php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth_guard.php';
require_once __DIR__ . '/../includes/helpers.php';

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM tbl_announcements WHERE id = ?");
$stmt->execute([$id]);
$item = $stmt->fetch();

if (!$item) {
    header("Location: index.php");
    exit();
}

// Fetch active subjects OR the currently selected subject (in case it was archived)
$subStmt = $pdo->prepare("SELECT id, code, name FROM tbl_subjects WHERE status = 'active' OR id = ? ORDER BY code ASC");
$subStmt->execute([$item['subject_id']]);
$subjects = $subStmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf($_POST['csrf_token'] ?? '');

    $subject_id = (int)$_POST['subject_id'];
    $title = $_POST['title'];
    $content = $_POST['content'];
    $due_date = !empty($_POST['due_date']) ? $_POST['due_date'] : null;
    $due_time = !empty($_POST['due_time']) ? $_POST['due_time'] : null;
    $end_date = !empty($_POST['end_date']) ? $_POST['end_date'] : null;

    // 1. Capture Old State
    $oldRow = $pdo->query("SELECT * FROM tbl_announcements WHERE id = $id")->fetch();

    // 2. Execute Update
    $updateStmt = $pdo->prepare("UPDATE tbl_announcements SET subject_id=?, title=?, content=?, due_date=?, due_time=?, end_date=? WHERE id=?");
    $updateStmt->execute([$subject_id, $title, $content, $due_date, $due_time, $end_date, $id]);

    // 3. Capture New State & Log
    $newRow = $pdo->query("SELECT * FROM tbl_announcements WHERE id = $id")->fetch();
    logAction($pdo, $_SESSION['admin_id'], $id, 'updated', $oldRow, $newRow);

    setFlash('success', 'Announcement updated successfully!');
    header("Location: index.php");
    exit();
}
$pageTitle = "Edit Announcement";
include __DIR__ . '/../includes/header.php';
?>
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h4>Edit Announcement</h4>
            </div>
            <div class="card-body">
                <form method="POST">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                    <div class="mb-3">
                        <label class="form-label fw-bold">Subject</label>
                        <select name="subject_id" class="form-select" required>
                            <?php foreach ($subjects as $sub): ?>
                                <option value="<?= $sub['id'] ?>" <?= $item['subject_id'] == $sub['id'] ? 'selected' : '' ?>>
                                    <?= e($sub['code']) ?> - <?= e($sub['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3"><label class="form-label fw-bold">Title</label><input type="text" name="title" class="form-control" value="<?= e($item['title']) ?>" required></div>
                    <div class="mb-3"><label class="form-label fw-bold">Content</label><textarea name="content" class="form-control" rows="5" required><?= e($item['content']) ?></textarea></div>

                    <div class="row bg-light p-3 rounded mb-3 mx-0">
                        <div class="col-md-4 mb-3 mb-md-0">
                            <label class="form-label fw-bold text-danger">Due Date</label>
                            <input type="date" name="due_date" class="form-control" value="<?= $item['due_date'] ?>">
                        </div>
                        <div class="col-md-4 mb-3 mb-md-0">
                            <label class="form-label fw-bold text-danger">Due Time</label>
                            <input type="time" name="due_time" class="form-control" value="<?= $item['due_time'] ?? '' ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-secondary">Period Ends</label>
                            <input type="date" name="end_date" class="form-control" value="<?= $item['end_date'] ?>">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary px-4">Update</button>
                    <a href="index.php" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>