<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth_guard.php';
require_once __DIR__ . '/../includes/helpers.php';
$pageTitle = "Create Announcement";

$subjects = $pdo->query("SELECT id, code, name FROM tbl_subjects WHERE status = 'active' ORDER BY code ASC")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf($_POST['csrf_token'] ?? '');
    $due_time = !empty($_POST['due_time']) ? $_POST['due_time'] : null;

    // --- FILE UPLOAD LOGIC ---
    $file_path = null;
    if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../assets/uploads/';
        // Create the directory dynamically if it doesn't exist
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Sanitize the filename and add a timestamp to prevent overwriting
        $safeFileName = preg_replace("/[^a-zA-Z0-9\.\-_]/", "", basename($_FILES['attachment']['name']));
        $fileName = time() . '_' . $safeFileName;
        $destination = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['attachment']['tmp_name'], $destination)) {
            $file_path = 'assets/uploads/' . $fileName;
        }
    }
    // -------------------------

    $stmt = $pdo->prepare("INSERT INTO tbl_announcements (admin_id, subject_id, title, content, due_date, due_time, end_date, file_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$_SESSION['admin_id'], $_POST['subject_id'], $_POST['title'], $_POST['content'], $_POST['due_date'] ?: null, $due_time, $_POST['end_date'] ?: null, $file_path]);

    $newId = $pdo->lastInsertId();
    $newRow = $pdo->query("SELECT * FROM tbl_announcements WHERE id = $newId")->fetch();
    logAction($pdo, $_SESSION['admin_id'], $newId, 'created', null, $newRow);
    setFlash('success', 'Published successfully!');
    header("Location: index.php");
    exit();
}
include __DIR__ . '/../includes/header.php';
?>
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <h4 class="mb-4">Create Announcement</h4>
                <!-- ADDED: enctype="multipart/form-data" -->
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    <div class="mb-3"><label class="form-label fw-bold">Subject</label><select name="subject_id" class="form-select" required><?php foreach ($subjects as $sub): ?><option value="<?= $sub['id'] ?>"><?= e($sub['code']) ?> - <?= e($sub['name']) ?></option><?php endforeach; ?></select></div>
                    <div class="mb-3"><label class="form-label fw-bold">Title</label><input type="text" name="title" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label fw-bold">Content</label><textarea name="content" class="form-control" rows="5" required></textarea></div>

                    <!-- ADDED: File Upload Input -->
                    <div class="mb-3">
                        <label class="form-label fw-bold"><i class="bi bi-paperclip"></i> Attachment (Optional)</label>
                        <input type="file" name="attachment" class="form-control" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                        <div class="form-text">Accepted files: PDF, Word, JPG, PNG.</div>
                    </div>

                    <div class="row bg-light p-3 rounded mb-3 mx-0">
                        <div class="col-md-4 mb-3 mb-md-0"><label class="form-label fw-bold text-danger">Due Date</label><input type="date" name="due_date" class="form-control"></div>
                        <div class="col-md-4 mb-3 mb-md-0"><label class="form-label fw-bold text-danger">Due Time</label><input type="time" name="due_time" class="form-control"></div>
                        <div class="col-md-4"><label class="form-label fw-bold text-secondary">Period Ends</label><input type="date" name="end_date" class="form-control"></div>
                    </div>
                    <button type="submit" class="btn btn-primary px-4">Publish</button> <a href="index.php" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>