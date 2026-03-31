<?php
// admin/create.php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth_guard.php';
require_once __DIR__ . '/../includes/helpers.php';
$pageTitle = "Create Announcement";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject = sanitize($_POST['subject']);
    $title = sanitize($_POST['title']);
    $content = sanitize($_POST['content']);
    $due_date = !empty($_POST['due_date']) ? $_POST['due_date'] : null;

    $stmt = $pdo->prepare("INSERT INTO tbl_announcements (admin_id, subject, title, content, due_date) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$_SESSION['admin_id'], $subject, $title, $content, $due_date]);

    $newId = $pdo->lastInsertId();
    logAction($pdo, $_SESSION['admin_id'], $newId, 'created', null, $_POST);

    header("Location: index.php");
    exit();
}
include __DIR__ . '/../includes/header.php';
?>
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white"><h4>Create Announcement</h4></div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label>Subject</label>
                        <select name="subject" class="form-select" required>
                            <?php foreach(getSubjectOptions() as $opt): ?>
                                <option value="<?= $opt ?>"><?= $opt ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Title</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Content (Supports basic HTML/Markdown via textarea)</label>
                        <textarea name="content" class="form-control" rows="5" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Due Date (Optional)</label>
                        <input type="date" name="due_date" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary">Publish</button>
                    <a href="index.php" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>