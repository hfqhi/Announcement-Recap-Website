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
$subjects = $pdo->query("SELECT id, code, name FROM tbl_subjects ORDER BY code ASC")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject_id = (int)$_POST['subject_id'];
    $title = sanitize($_POST['title']);
    $content = sanitize($_POST['content']);
    $due_date = !empty($_POST['due_date']) ? $_POST['due_date'] : null;
    $end_date = !empty($_POST['end_date']) ? $_POST['end_date'] : null;

    $updateStmt = $pdo->prepare("UPDATE tbl_announcements SET subject_id=?, title=?, content=?, due_date=?, end_date=? WHERE id=?");
    $updateStmt->execute([$subject_id, $title, $content, $due_date, $end_date, $id]);

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
                        <select name="subject_id" class="form-select" required>
                            <?php foreach($subjects as $sub): ?>
                                <option value="<?= $sub['id'] ?>" <?= $item['subject_id'] == $sub['id'] ? 'selected' : '' ?>>
                                    <?= $sub['code'] ?> - <?= htmlspecialchars($sub['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3"><label>Title</label><input type="text" name="title" class="form-control" value="<?= htmlspecialchars($item['title']) ?>" required></div>
                    <div class="mb-3"><label>Content</label><textarea name="content" class="form-control" rows="5" required><?= htmlspecialchars($item['content']) ?></textarea></div>
                    <div class="row">
                        <div class="col-md-6 mb-3"><label>Start / Due Date</label><input type="date" name="due_date" class="form-control" value="<?= $item['due_date'] ?>"></div>
                        <div class="col-md-6 mb-3"><label>End Date (Optional Period)</label><input type="date" name="end_date" class="form-control" value="<?= $item['end_date'] ?>"></div>
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="index.php" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>