<?php
// admin/index.php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth_guard.php';
require_once __DIR__ . '/../includes/helpers.php';
$pageTitle = "Dashboard";

$stmt = $pdo->query("SELECT * FROM tbl_announcements ORDER BY status ASC, due_date ASC, created_at DESC");
$announcements = $stmt->fetchAll();

include __DIR__ . '/../includes/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Admin Dashboard</h2>
    <a href="create.php" class="btn btn-primary"><i class="bi bi-plus-lg"></i> New Announcement</a>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0 table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Subject</th>
                    <th>Title</th>
                    <th>Due Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($announcements as $row): ?>
                <tr>
                    <td><span class="badge bg-secondary"><?= htmlspecialchars($row['subject']) ?></span></td>
                    <td><?= htmlspecialchars($row['title']) ?></td>
                    <td><?= $row['due_date'] ? date('M d, Y', strtotime($row['due_date'])) : '-' ?></td>
                    <td>
                        <span class="badge <?= $row['status'] == 'active' ? 'bg-success' : 'bg-danger' ?>">
                            <?= ucfirst($row['status']) ?>
                        </span>
                    </td>
                    <td>
                        <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                        <?php if($row['status'] == 'active'): ?>
                        <form action="delete.php" method="POST" class="d-inline delete-form">
                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                            <button type="submit" class="btn btn-sm btn-outline-danger">Archive</button>
                        </form>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>