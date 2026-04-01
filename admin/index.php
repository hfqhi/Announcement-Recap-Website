<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth_guard.php';
require_once __DIR__ . '/../includes/helpers.php';
$pageTitle = "Manage Announcements";

$allAnnouncements = $pdo->query("SELECT a.*, s.code, s.color_theme
                                 FROM tbl_announcements a
                                 LEFT JOIN tbl_subjects s ON a.subject_id = s.id
                                 ORDER BY ISNULL(a.due_date), a.due_date ASC, s.code ASC, a.created_at DESC")->fetchAll();

include __DIR__ . '/../includes/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Manage Announcements</h2>
    <a href="create.php" class="btn btn-primary"><i class="bi bi-plus-lg"></i> New Announcement</a>
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
                            <th>Subject</th>
                            <th>Title</th>
                            <th>Date / Period</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($active as $row): ?>
                            <tr>
                                <td><span class="badge <?= e($row['color_theme'] ?? 'bg-secondary') ?>"><?= e($row['code'] ?? 'DELETED') ?></span></td>
                                <td><?= e($row['title']) ?></td>
                                <td><?= $row['due_date'] ? ($row['end_date'] ? date('M d', strtotime($row['due_date'])) . ' - ' . date('M d, Y', strtotime($row['end_date'])) : date('M d, Y', strtotime($row['due_date']))) : '<span class="text-muted">No Date</span>'; ?></td>
                                <td>
                                    <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-primary" title="Edit"><i class="bi bi-pencil-square"></i></a>
                                    <form action="delete.php" method="POST" class="d-inline delete-form">
                                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                        <input type="hidden" name="id" value="<?= $row['id'] ?>"><input type="hidden" name="action" value="archive">
                                        <button type="submit" class="btn btn-sm btn-outline-warning" title="Archive"><i class="bi bi-archive-fill"></i></button>
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
                            <th>Subject</th>
                            <th>Title</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($archived as $row): ?>
                            <tr class="table-secondary">
                                <td><span class="badge <?= e($row['color_theme'] ?? 'bg-secondary') ?>"><?= e($row['code'] ?? 'DELETED') ?></span></td>
                                <td><del><?= e($row['title']) ?></del></td>
                                <td>
                                    <form action="delete.php" method="POST" class="d-inline">
                                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                        <input type="hidden" name="id" value="<?= $row['id'] ?>"><input type="hidden" name="action" value="restore">
                                        <button type="submit" class="btn btn-sm btn-success" title="Restore"><i class="bi bi-arrow-counterclockwise"></i></button>
                                    </form>
                                    <form action="delete.php" method="POST" class="d-inline delete-form">
                                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                        <input type="hidden" name="id" value="<?= $row['id'] ?>"><input type="hidden" name="action" value="hard_delete">
                                        <button type="submit" class="btn btn-sm btn-danger" title="Permanently Delete"><i class="bi bi-trash3-fill"></i></button>
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
<?php include __DIR__ . '/../includes/footer.php'; ?>