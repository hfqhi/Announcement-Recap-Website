<?php
// admin/subjects.php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth_guard.php';
require_once __DIR__ . '/../includes/helpers.php';
$pageTitle = "Manage Subjects";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['csrf_token'])) {
    verifyCsrf($_POST['csrf_token']);

    $id = (int)($_POST['id'] ?? 0);

    if ($_POST['action'] === 'add') {
        $stmt = $pdo->prepare("INSERT INTO tbl_subjects (code, name, professor, schedule, color_theme) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$_POST['code'], $_POST['name'], $_POST['professor'], $_POST['schedule'], $_POST['color_theme']]);

        $newId = $pdo->lastInsertId();
        $newRow = $pdo->query("SELECT * FROM tbl_subjects WHERE id = $newId")->fetch();
        logAction($pdo, $_SESSION['admin_id'], null, 'subject_created', null, $newRow, $newId);
        setFlash('success', 'Subject added successfully!');
    } elseif ($_POST['action'] === 'edit') {
        $oldRow = $pdo->query("SELECT * FROM tbl_subjects WHERE id = $id")->fetch();
        $stmt = $pdo->prepare("UPDATE tbl_subjects SET code=?, name=?, professor=?, schedule=?, color_theme=? WHERE id=?");
        $stmt->execute([$_POST['code'], $_POST['name'], $_POST['professor'], $_POST['schedule'], $_POST['color_theme'], $id]);
        $newRow = $pdo->query("SELECT * FROM tbl_subjects WHERE id = $id")->fetch();

        logAction($pdo, $_SESSION['admin_id'], null, 'subject_updated', $oldRow, $newRow, $id);
        setFlash('success', 'Subject updated successfully!');
    } elseif ($_POST['action'] === 'archive') {
        $targetData = $pdo->query("SELECT * FROM tbl_subjects WHERE id = $id")->fetch();
        $pdo->prepare("UPDATE tbl_subjects SET status = 'archived' WHERE id = ?")->execute([$id]);
        logAction($pdo, $_SESSION['admin_id'], null, 'subject_archived', null, $targetData, $id);
        setFlash('warning', 'Subject archived.');
    } elseif ($_POST['action'] === 'restore') {
        $targetData = $pdo->query("SELECT * FROM tbl_subjects WHERE id = $id")->fetch();
        $pdo->prepare("UPDATE tbl_subjects SET status = 'active' WHERE id = ?")->execute([$id]);
        logAction($pdo, $_SESSION['admin_id'], null, 'subject_restored', null, $targetData, $id);
        setFlash('success', 'Subject restored.');
    } elseif ($_POST['action'] === 'hard_delete') {
        $targetSubject = $pdo->query("SELECT * FROM tbl_subjects WHERE id = $id")->fetch();

        // 1. Pre-log all cascade-deleted announcements to prevent silent data loss
        $orphanedAnns = $pdo->query("SELECT * FROM tbl_announcements WHERE subject_id = $id")->fetchAll();
        foreach ($orphanedAnns as $ann) {
            logAction($pdo, $_SESSION['admin_id'], null, 'hard_deleted', $ann, null, $ann['id']);
        }

        // 2. Log the subject deletion itself
        logAction($pdo, $_SESSION['admin_id'], null, 'subject_deleted', $targetSubject, null, $id);

        // 3. Execute the deletion
        $pdo->prepare("DELETE FROM tbl_subjects WHERE id = ?")->execute([$id]);
        setFlash('danger', 'Subject and all associated announcements permanently deleted.');
    }
    header("Location: subjects.php");
    exit();
}

$allSubjects = $pdo->query("SELECT * FROM tbl_subjects ORDER BY code ASC")->fetchAll();
$active = array_filter($allSubjects, fn($s) => $s['status'] === 'active');
$archived = array_filter($allSubjects, fn($s) => $s['status'] === 'archived');

include __DIR__ . '/../includes/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Manage Subjects</h2>
    <button class="btn btn-primary" onclick="openSubjectModal('add')"><i class="bi bi-plus-lg"></i> Add Subject</button>
</div>

<ul class="nav nav-tabs mb-3" role="tablist">
    <li class="nav-item"><button class="nav-link active fw-bold" data-bs-toggle="tab" data-bs-target="#active-tab">Active (<?= count($active) ?>)</button></li>
    <li class="nav-item"><button class="nav-link text-muted" data-bs-toggle="tab" data-bs-target="#archived-tab">Archived (<?= count($archived) ?>)</button></li>
</ul>

<div class="tab-content">
    <div class="tab-pane fade show active" id="active-tab">
        <div class="card shadow-sm">
            <div class="card-body p-0 table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Professor</th>
                            <th>Schedule</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($active as $sub): ?>
                            <tr>
                                <td><span class="badge <?= e($sub['color_theme']) ?>"><?= e($sub['code']) ?></span></td>
                                <td><?= e($sub['name']) ?></td>
                                <td><?= e($sub['professor']) ?></td>
                                <td><?= e($sub['schedule']) ?></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary" style="min-height:38px;" onclick='openSubjectModal("edit", <?= json_encode($sub) ?>)' title="Edit"><i class="bi bi-pencil-square"></i><span class="d-none d-sm-inline ms-1">Edit</span></button>
                                    <form method="POST" class="d-inline delete-form">
                                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                        <input type="hidden" name="action" value="archive">
                                        <input type="hidden" name="id" value="<?= $sub['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-warning" style="min-height:38px;" title="Archive"><i class="bi bi-archive-fill"></i></button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="archived-tab">
        <div class="card shadow-sm">
            <div class="card-body p-0 table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Schedule</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($archived as $sub): ?>
                            <tr class="table-secondary">
                                <td><span class="badge <?= e($sub['color_theme']) ?>"><?= e($sub['code']) ?></span></td>
                                <td><del><?= e($sub['name']) ?></del></td>
                                <td><del><?= e($sub['schedule']) ?></del></td>
                                <td>
                                    <form method="POST" class="d-inline">
                                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                        <input type="hidden" name="action" value="restore"><input type="hidden" name="id" value="<?= $sub['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-success" style="min-height:38px;" title="Restore"><i class="bi bi-arrow-counterclockwise"></i></button>
                                    </form>
                                    <form method="POST" class="d-inline delete-form">
                                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                        <input type="hidden" name="action" value="hard_delete"><input type="hidden" name="id" value="<?= $sub['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-danger" style="min-height:38px;" title="Permanently Delete"><i class="bi bi-trash3-fill"></i></button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="subjectModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Subject</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="overflow-y: auto; max-height: 70vh;">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <input type="hidden" name="action" id="modalAction" value="add">
                <input type="hidden" name="id" id="modalId">
                <label class="form-label fw-bold small">Code (e.g. SCIETS)</label>
                <input type="text" name="code" id="modalCode" class="form-control mb-2" required>
                <label class="form-label fw-bold small">Full Name</label>
                <input type="text" name="name" id="modalName" class="form-control mb-2" required>
                <label class="form-label fw-bold small">Professor</label>
                <input type="text" name="professor" id="modalProf" class="form-control mb-2">
                <label class="form-label fw-bold small">Schedule</label>
                <input type="text" name="schedule" id="modalSched" class="form-control mb-2">
                <label class="form-label fw-bold small">Color Theme</label>
                <select name="color_theme" id="modalTheme" class="form-select mb-2">
                    <?php foreach (COLOR_THEMES as $class => $data): ?>
                        <option value="<?= $class ?>"><?= $data['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="modal-footer"><button type="submit" class="btn btn-primary px-4">Save</button></div>
        </form>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>