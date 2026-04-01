<?php
// admin/history.php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth_guard.php';
$pageTitle = "Audit Log";

$stmt = $pdo->query("
    SELECT l.*, a.username, ann.title as announcement_title
    FROM tbl_audit_log l
    LEFT JOIN tbl_admins a ON l.admin_id = a.id
    LEFT JOIN tbl_announcements ann ON l.announcement_id = ann.id
    ORDER BY l.changed_at DESC LIMIT 100
");
$logs = $stmt->fetchAll();

$actionColors = [
    'created' => 'bg-success',
    'updated' => 'bg-warning text-dark',
    'archived' => 'bg-danger',
    'restored' => 'bg-primary'
];

include __DIR__ . '/../includes/header.php';
?>
<h2 class="mb-4">Admin Audit Log</h2>
<div class="card shadow-sm">
    <div class="card-body p-0 table-responsive">
        <table class="table table-striped table-sm mb-0">
            <thead class="table-dark">
                <tr>
                    <th>Time</th>
                    <th>Admin</th>
                    <th>Action</th>
                    <th>Target Announcement</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($logs as $log): ?>
                    <?php $badgeColor = $actionColors[$log['action']] ?? 'bg-secondary'; ?>
                    <tr>
                        <td><?= date('M d, H:i', strtotime($log['changed_at'])) ?></td>
                        <td><?= htmlspecialchars($log['username'] ?? 'Unknown') ?></td>
                        <td><span class="badge <?= $badgeColor ?>"><?= htmlspecialchars(ucfirst($log['action'])) ?></span></td>
                        <td><?= htmlspecialchars($log['announcement_title'] ?? 'Deleted/Unknown (ID:' . $log['announcement_id'] . ')') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>