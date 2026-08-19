<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth_guard.php';
require_once __DIR__ . '/../includes/helpers.php';

$id = $_GET['id'] ?? 0;
$item = $pdo->query("SELECT * FROM tbl_announcements WHERE id = $id")->fetch();
if (!$item) {
    header("Location: index.php");
    exit();
}

$subjects = $pdo->query("SELECT id, code, name FROM tbl_subjects WHERE status = 'active' OR id = {$item['subject_id']} ORDER BY code ASC")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf($_POST['csrf_token'] ?? '');
    $due_time = !empty($_POST['due_time']) ? $_POST['due_time'] : null;

    // --- FILE UPLOAD LOGIC ---
    $file_path = $item['file_path']; // Keep existing by default

    if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
        // Use dirname(__DIR__) for clean absolute paths across Windows/Linux
        $uploadDir = dirname(__DIR__) . '/assets/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $safeFileName = preg_replace("/[^a-zA-Z0-9\.\-_]/", "", basename($_FILES['attachment']['name']));
        $fileName = time() . '_' . $safeFileName;
        $destination = $uploadDir . $fileName;

        // Execute the move
        if (move_uploaded_file($_FILES['attachment']['tmp_name'], $destination)) {
            // Delete the old file if it exists
            if (!empty($file_path) && file_exists(dirname(__DIR__) . '/' . $file_path)) {
                unlink(dirname(__DIR__) . '/' . $file_path);
            }
            $file_path = 'assets/uploads/' . $fileName;
        }
    }
    // -------------------------

    $oldRow = $pdo->query("SELECT * FROM tbl_announcements WHERE id = $id")->fetch();
    $pdo->prepare("UPDATE tbl_announcements SET subject_id=?, title=?, content=?, due_date=?, due_time=?, end_date=?, file_path=? WHERE id=?")
        ->execute([$_POST['subject_id'], $_POST['title'], $_POST['content'], $_POST['due_date'] ?: null, $due_time, $_POST['end_date'] ?: null, $file_path, $id]);

    $newRow = $pdo->query("SELECT * FROM tbl_announcements WHERE id = $id")->fetch();
    logAction($pdo, $_SESSION['admin_id'], $id, 'updated', $oldRow, $newRow);
    setFlash('success', 'Updated successfully!');
    header("Location: index.php");
    exit();
}
$pageTitle = "Edit Announcement";
include __DIR__ . '/../includes/header.php';
?>
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <h4 class="mb-4">Edit Announcement</h4>
                <!-- ADDED: enctype="multipart/form-data" -->
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    <div class="mb-3"><label class="form-label fw-bold">Subject</label><select name="subject_id" class="form-select" required><?php foreach ($subjects as $sub): ?><option value="<?= $sub['id'] ?>" <?= $item['subject_id'] == $sub['id'] ? 'selected' : '' ?>><?= e($sub['code']) ?> - <?= e($sub['name']) ?></option><?php endforeach; ?></select></div>
                    <div class="mb-3"><label class="form-label fw-bold">Title</label><input type="text" name="title" class="form-control" value="<?= e($item['title']) ?>" required></div>
                    <div class="mb-3"><label class="form-label fw-bold">Content</label><textarea name="content" class="form-control" rows="5" required><?= e($item['content']) ?></textarea></div>

                    <!-- ADDED: File Upload Input -->
                    <div class="mb-3">
                        <label class="form-label fw-bold"><i class="bi bi-paperclip"></i> Attachment (Optional)</label>
                        <?php if (!empty($item['file_path'])): ?>
                            <div class="mb-2">
                                <a href="../<?= e($item['file_path']) ?>" target="_blank" class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i> View Current File</a>
                                <small class="text-muted ms-2">Uploading a new file will overwrite the existing one.</small>
                            </div>
                        <?php endif; ?>
                        <input type="file" name="attachment" class="form-control" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                    </div>

                    <div class="row bg-light p-3 rounded mb-3 mx-0">
                        <div class="col-md-4 mb-3 mb-md-0"><label class="form-label fw-bold text-danger">Due Date</label><input type="date" name="due_date" class="form-control" value="<?= $item['due_date'] ?>"></div>
                        <div class="col-md-4 mb-3 mb-md-0"><label class="form-label fw-bold text-danger">Due Time</label><input type="time" name="due_time" class="form-control" value="<?= $item['due_time'] ?? '' ?>"></div>
                        <div class="col-md-4"><label class="form-label fw-bold text-secondary">Period Ends</label><input type="date" name="end_date" class="form-control" value="<?= $item['end_date'] ?>"></div>
                    </div>
                    <button type="submit" class="btn btn-primary px-4">Update</button> <a href="index.php" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>