<?php
// admin/index.php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth_guard.php';
require_once __DIR__ . '/../includes/helpers.php';
$pageTitle = "Manage Announcements";

// 1. Handle Filters
$subjectFilter = $_GET['subject_id'] ?? '';
$searchQuery = $_GET['search'] ?? '';

$where = [];
$params = []; // Simple indexed array for positional parameters

if ($subjectFilter) {
    $where[] = "a.subject_id = ?";
    $params[] = $subjectFilter;
}

if ($searchQuery) {
    $where[] = "(a.title LIKE ? OR a.content LIKE ?)";
    $searchWildcard = '%' . $searchQuery . '%';
    $params[] = $searchWildcard;
    $params[] = $searchWildcard;
}

$whereSql = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";

// 2. Fetch filtered announcements
$sql = "SELECT a.*, s.code, s.color_theme
        FROM tbl_announcements a
        LEFT JOIN tbl_subjects s ON a.subject_id = s.id
        $whereSql
        ORDER BY ISNULL(a.due_date), a.due_date ASC, s.code ASC, a.created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$allAnnouncements = $stmt->fetchAll();

// 3. Split into Active and Archived
$active = array_filter($allAnnouncements, fn($a) => $a['status'] === 'active');
$archived = array_filter($allAnnouncements, fn($a) => $a['status'] === 'archived');

// 4. Fetch Subjects for the dropdown filter
$subjects = $pdo->query("SELECT id, code FROM tbl_subjects ORDER BY code ASC")->fetchAll();

include __DIR__ . '/../includes/header.php';
?>
<div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
    <h2 class="mb-3 mb-md-0">Manage Announcements</h2>
    <a href="create.php" class="btn btn-primary"><i class="bi bi-plus-lg"></i> New Announcement</a>
</div>

<div class="card shadow-sm mb-4 border-0 bg-light">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-center">
            <div class="col-md-4">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Search title or content..." value="<?= e($searchQuery) ?>">
                </div>
            </div>
            <div class="col-md-3">
                <select name="subject_id" class="form-select form-select-sm">
                    <option value="">All Subjects</option>
                    <?php foreach ($subjects as $s): ?>
                        <option value="<?= $s['id'] ?>" <?= $subjectFilter == $s['id'] ? 'selected' : '' ?>><?= e($s['code']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-dark btn-sm w-100">Filter</button>
            </div>
            <div class="col-md-1">
                <a href="index.php" class="btn btn-outline-secondary btn-sm w-100" title="Clear Filters"><i class="bi bi-x-lg"></i></a>
            </div>
        </form>
    </div>
</div>

<ul class="nav nav-tabs mb-3" role="tablist">
    <li class="nav-item"><button class="nav-link active fw-bold" data-bs-toggle="tab" data-bs-target="#active-tab">Active (<?= count($active) ?>)</button></li>
    <li class="nav-item"><button class="nav-link text-muted" data-bs-toggle="tab" data-bs-target="#archived-tab">Archived (<?= count($archived) ?>)</button></li>
</ul>

<div class="tab-content">
    <div class="tab-pane fade show active" id="active-tab">
        <div class="card shadow-sm border-0">
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
                        <?php if (empty($active)): ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">No active announcements found.</td>
                            </tr>
                        <?php endif; ?>
                        <?php foreach ($active as $row): ?>
                            <tr>
                                <td><span class="badge <?= e($row['color_theme'] ?? 'bg-secondary') ?>"><?= e($row['code'] ?? 'DELETED') ?></span></td>
                                <td><?= e($row['title']) ?></td>
                                <td>
                                    <?php if ($row['end_date']): ?>
                                        <?= date('M d', strtotime($row['due_date'])) ?> - <?= date('M d, Y', strtotime($row['end_date'])) ?>
                                    <?php elseif ($row['due_date']): ?>
                                        <?= date('M d, Y', strtotime($row['due_date'])) ?>
                                    <?php else: ?>
                                        <span class="text-muted small">No Date</span>
                                    <?php endif; ?>
                                </td>
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
        <div class="card shadow-sm border-0">
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
                        <?php if (empty($archived)): ?>
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">No archived announcements found.</td>
                            </tr>
                        <?php endif; ?>
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