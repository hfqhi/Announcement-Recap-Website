<?php
// admin/history.php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth_guard.php';
require_once __DIR__ . '/../includes/helpers.php';
$pageTitle = "Audit Log";

// --- Filters & Pagination ---
$period = $_GET['period'] ?? 'all';
$actionFilter = $_GET['action'] ?? '';
$subjectFilter = $_GET['subject_id'] ?? '';
$page = max(1, (int)($_GET['page'] ?? 1));
$limit = 50;
$offset = ($page - 1) * $limit;

$where = [];
$params = [];

if ($period === 'today') {
    $where[] = "DATE(l.changed_at) = CURDATE()";
} elseif ($period === 'week') {
    $where[] = "YEARWEEK(l.changed_at, 1) = YEARWEEK(NOW(), 1)";
} elseif ($period === 'month') {
    $where[] = "YEAR(l.changed_at) = YEAR(NOW()) AND MONTH(l.changed_at) = MONTH(NOW())";
} elseif ($period === 'custom' && !empty($_GET['date_from']) && !empty($_GET['date_to'])) {
    $where[] = "l.changed_at BETWEEN :date_from AND :date_to";
    $params['date_from'] = $_GET['date_from'] . ' 00:00:00';
    $params['date_to'] = $_GET['date_to'] . ' 23:59:59';
}

if ($actionFilter) {
    $where[] = "l.action = :action";
    $params['action'] = $actionFilter;
}

if ($subjectFilter) {
    $where[] = "s.id = :subject_id";
    $params['subject_id'] = $subjectFilter;
}

$whereClause = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";

// --- Query Execution ---
// Get Total Count for Pagination
$countQuery = "SELECT COUNT(*) FROM tbl_audit_log l
               LEFT JOIN tbl_announcements ann ON l.announcement_id = ann.id
               LEFT JOIN tbl_subjects s ON ann.subject_id = s.id $whereClause";
$stmtCount = $pdo->prepare($countQuery);
$stmtCount->execute($params);
$totalRecords = $stmtCount->fetchColumn();
$totalPages = ceil($totalRecords / $limit);

// Get Paginated Data
$sql = "SELECT l.*, a.username, ann.title as announcement_title, s.code as subject_code
        FROM tbl_audit_log l
        LEFT JOIN tbl_admins a ON l.admin_id = a.id
        LEFT JOIN tbl_announcements ann ON l.announcement_id = ann.id
        LEFT JOIN tbl_subjects s ON ann.subject_id = s.id
        $whereClause
        ORDER BY l.changed_at DESC LIMIT $limit OFFSET $offset";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$logs = $stmt->fetchAll();

// Fetch Subjects for the Dropdown Filter
$subjects = $pdo->query("SELECT id, code FROM tbl_subjects ORDER BY code ASC")->fetchAll();

// --- UI Helpers ---
$actionColors = [
    'created' => 'bg-success',
    'updated' => 'bg-warning text-dark',
    'archived' => 'bg-secondary',
    'restored' => 'bg-primary',
    'hard_deleted' => 'bg-danger',
    'subject_created' => 'bg-success border border-light',
    'subject_updated' => 'bg-warning text-dark border border-light',
    'subject_archived' => 'bg-secondary border border-light',
    'subject_restored' => 'bg-primary border border-light',
    'subject_deleted' => 'bg-danger border border-light'
];

function renderDiff($oldJson, $newJson)
{
    $old = json_decode($oldJson ?? '{}', true) ?: [];
    $new = json_decode($newJson ?? '{}', true) ?: [];
    $allKeys = array_unique(array_merge(array_keys($old), array_keys($new)));

    $diffHtml = '<table class="table table-sm table-bordered mt-2 mb-0" style="font-size: 0.85rem;">';
    $diffHtml .= '<thead class="table-light"><tr><th>Field</th><th>Old Value</th><th>New Value</th></tr></thead><tbody>';
    $hasDiff = false;

    foreach ($allKeys as $k) {
        if (in_array($k, ['created_at', 'updated_at', 'id'])) continue; // Skip auto timestamps
        $o = $old[$k] ?? '<em class="text-muted">null</em>';
        $n = $new[$k] ?? '<em class="text-muted">null</em>';
        if ($o != $n) {
            $hasDiff = true;
            $diffHtml .= "<tr><td class='fw-bold'>" . e($k) . "</td>
                          <td class='text-danger text-decoration-line-through'>" . e($o) . "</td>
                          <td class='text-success'>" . e($n) . "</td></tr>";
        }
    }
    $diffHtml .= '</tbody></table>';
    return $hasDiff ? $diffHtml : '<div class="text-muted small mt-2">No structural changes detected (or record is missing).</div>';
}

include __DIR__ . '/../includes/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Audit Log</h2>
    <span class="badge bg-dark fs-6">Total Records: <?= number_format($totalRecords) ?></span>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body bg-light">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-2">
                <label class="form-label small fw-bold mb-1">Time Period</label>
                <select name="period" class="form-select form-select-sm" onchange="toggleCustomDates(this.value)">
                    <option value="all" <?= $period == 'all' ? 'selected' : '' ?>>All Time</option>
                    <option value="today" <?= $period == 'today' ? 'selected' : '' ?>>Today</option>
                    <option value="week" <?= $period == 'week' ? 'selected' : '' ?>>This Week</option>
                    <option value="month" <?= $period == 'month' ? 'selected' : '' ?>>This Month</option>
                    <option value="custom" <?= $period == 'custom' ? 'selected' : '' ?>>Custom Range...</option>
                </select>
            </div>
            <div class="col-md-3 custom-dates" style="display: <?= $period == 'custom' ? 'block' : 'none' ?>;">
                <div class="d-flex gap-2">
                    <div>
                        <label class="form-label small fw-bold mb-1">From</label>
                        <input type="date" name="date_from" class="form-control form-control-sm" value="<?= e($_GET['date_from'] ?? '') ?>">
                    </div>
                    <div>
                        <label class="form-label small fw-bold mb-1">To</label>
                        <input type="date" name="date_to" class="form-control form-control-sm" value="<?= e($_GET['date_to'] ?? '') ?>">
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold mb-1">Action Type</label>
                <select name="action" class="form-select form-select-sm">
                    <option value="">All Actions</option>
                    <option value="created" <?= $actionFilter == 'created' ? 'selected' : '' ?>>Created</option>
                    <option value="updated" <?= $actionFilter == 'updated' ? 'selected' : '' ?>>Updated</option>
                    <option value="hard_deleted" <?= $actionFilter == 'hard_deleted' ? 'selected' : '' ?>>Deleted</option>
                    <option value="subject_updated" <?= $actionFilter == 'subject_updated' ? 'selected' : '' ?>>Subject Updates</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold mb-1">Subject</label>
                <select name="subject_id" class="form-select form-select-sm">
                    <option value="">All Subjects</option>
                    <?php foreach ($subjects as $s): ?>
                        <option value="<?= $s['id'] ?>" <?= $subjectFilter == $s['id'] ? 'selected' : '' ?>><?= e($s['code']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary btn-sm w-100"><i class="bi bi-search"></i> Filter</button>
            </div>
            <div class="col-md-1">
                <a href="history.php" class="btn btn-outline-secondary btn-sm w-100" title="Clear"><i class="bi bi-x-lg"></i></a>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0 table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th>Date & Time</th>
                    <th>Admin</th>
                    <th>Action</th>
                    <th>Target</th>
                    <th>Details</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($logs)): ?>
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">No audit logs found for this criteria.</td>
                    </tr>
                <?php endif; ?>

                <?php foreach ($logs as $log): ?>
                    <?php
                    $badgeColor = $actionColors[$log['action']] ?? 'bg-secondary';
                    $isSubjectAction = strpos($log['action'], 'subject_') === 0;

                    // Resolve Target Name
                    $targetStr = "<em>Unknown/Deleted</em>";
                    if ($isSubjectAction && $log['new_value']) {
                        $targetData = json_decode($log['new_value'], true);
                        $targetStr = "Subject: " . e($targetData['code'] ?? 'N/A');
                    } elseif ($isSubjectAction && $log['old_value']) {
                        $targetData = json_decode($log['old_value'], true);
                        $targetStr = "Subject: " . e($targetData['code'] ?? 'N/A');
                    } elseif ($log['announcement_title']) {
                        $targetStr = "<strong>" . e($log['subject_code']) . "</strong>: " . e($log['announcement_title']);
                    } elseif ($log['deleted_record_id']) {
                        $targetStr = "Deleted Record ID: " . $log['deleted_record_id'];
                    }
                    ?>
                    <tr>
                        <td class="text-nowrap" style="font-size: 0.9rem;">
                            <?= date('Y-m-d', strtotime($log['changed_at'])) ?><br>
                            <span class="text-muted small"><?= date('h:i A', strtotime($log['changed_at'])) ?></span>
                        </td>
                        <td><?= e($log['username'] ?? 'System') ?></td>
                        <td><span class="badge <?= $badgeColor ?>"><?= e(str_replace('_', ' ', strtoupper($log['action']))) ?></span></td>
                        <td><?= $targetStr ?></td>
                        <td>
                            <?php if ($log['action'] === 'updated' || $log['action'] === 'subject_updated' || $log['action'] === 'hard_deleted'): ?>
                                <button class="btn btn-sm btn-outline-secondary py-0 px-2" data-bs-toggle="collapse" data-bs-target="#diff-<?= $log['id'] ?>">
                                    <i class="bi bi-eye"></i> View
                                </button>
                            <?php endif; ?>
                        </td>
                    </tr>

                    <?php if ($log['action'] === 'updated' || $log['action'] === 'subject_updated' || $log['action'] === 'hard_deleted'): ?>
                        <tr class="collapse bg-light" id="diff-<?= $log['id'] ?>">
                            <td colspan="5" class="p-3">
                                <?php if ($log['action'] === 'hard_deleted'): ?>
                                    <div class="fw-bold text-danger mb-1">Deleted Data Snapshot:</div>
                                    <pre class="bg-white p-2 border rounded" style="font-size: 0.8rem;"><?= e(json_encode(json_decode($log['old_value']), JSON_PRETTY_PRINT)) ?></pre>
                                <?php else: ?>
                                    <div class="fw-bold mb-1">Changes Detected:</div>
                                    <?= renderDiff($log['old_value'], $log['new_value']) ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php if ($totalPages > 1): ?>
    <nav class="mt-4">
        <ul class="pagination justify-content-center">
            <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=<?= $page - 1 ?>&period=<?= $period ?>&action=<?= $actionFilter ?>&subject_id=<?= $subjectFilter ?>">Previous</a>
            </li>
            <li class="page-item disabled"><span class="page-link">Page <?= $page ?> of <?= $totalPages ?></span></li>
            <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=<?= $page + 1 ?>&period=<?= $period ?>&action=<?= $actionFilter ?>&subject_id=<?= $subjectFilter ?>">Next</a>
            </li>
        </ul>
    </nav>
<?php endif; ?>

<script>
    function toggleCustomDates(val) {
        document.querySelector('.custom-dates').style.display = (val === 'custom') ? 'block' : 'none';
    }
</script>
<?php include __DIR__ . '/../includes/footer.php'; ?>