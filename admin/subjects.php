<?php
// admin/subjects.php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth_guard.php';
require_once __DIR__ . '/../includes/helpers.php';
$pageTitle = "Manage Subjects";

// --- HANDLE FORM SUBMISSIONS (ADD & EDIT) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf($_POST['csrf_token'] ?? '');

    $action = $_POST['action'] ?? '';
    $code = trim($_POST['code']);
    $name = trim($_POST['name']);
    $prof = trim($_POST['professor']);
    $schedule = trim($_POST['schedule']);
    $color = $_POST['color_theme'];

    if ($action === 'add') {
        $stmt = $pdo->prepare("INSERT INTO tbl_subjects (code, name, professor, schedule, color_theme) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$code, $name, $prof, $schedule, $color]);
        logAction($pdo, $_SESSION['admin_id'], $pdo->lastInsertId(), 'created', null, "Added subject: $code");
        setFlash('success', 'Subject added successfully!');
    } elseif ($action === 'edit') {
        $id = $_POST['id'];

        // Get old state for audit log
        $stmt = $pdo->prepare("SELECT * FROM tbl_subjects WHERE id = ?");
        $stmt->execute([$id]);
        $oldState = json_encode($stmt->fetch(PDO::FETCH_ASSOC));

        // Update record
        $update = $pdo->prepare("UPDATE tbl_subjects SET code = ?, name = ?, professor = ?, schedule = ?, color_theme = ? WHERE id = ?");
        $update->execute([$code, $name, $prof, $schedule, $color, $id]);

        // Get new state for audit log
        $stmt->execute([$id]);
        $newState = json_encode($stmt->fetch(PDO::FETCH_ASSOC));

        logAction($pdo, $_SESSION['admin_id'], $id, 'updated', $oldState, $newState);
        setFlash('success', 'Subject updated successfully!');
    }

    header("Location: subjects.php");
    exit;
}

// --- FILTER LOGIC ---
$searchQuery = $_GET['search'] ?? '';
$where = [];
$params = [];

if ($searchQuery) {
    $where[] = "(code LIKE ? OR name LIKE ? OR professor LIKE ? OR schedule LIKE ?)";
    $searchWildcard = "%$searchQuery%";
    $params[] = $searchWildcard;
    $params[] = $searchWildcard;
    $params[] = $searchWildcard;
    $params[] = $searchWildcard;
}

$whereSql = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";
$sql = "SELECT * FROM tbl_subjects $whereSql ORDER BY code ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$allSubjects = $stmt->fetchAll();

$active = array_filter($allSubjects, fn($s) => $s['status'] === 'active');
$archived = array_filter($allSubjects, fn($s) => $s['status'] === 'archived');

include __DIR__ . '/../includes/header.php';
?>
<div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
    <h2 class="mb-3 mb-md-0">Manage Subjects</h2>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#subjectModal" onclick="prepareModal('add')">
        <i class="bi bi-plus-lg"></i> Add Subject
    </button>
</div>

<!-- FILTER BAR -->
<div class="card shadow-sm mb-4 border-0 bg-light">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-center">
            <div class="col-md-6">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Search Code, Name, Professor, or Schedule..." value="<?= e($searchQuery) ?>">
                </div>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-dark btn-sm w-100">Filter</button>
            </div>
            <div class="col-md-1">
                <a href="subjects.php" class="btn btn-outline-secondary btn-sm w-100" title="Clear Filters"><i class="bi bi-x-lg"></i></a>
            </div>
        </form>
    </div>
</div>

<ul class="nav nav-tabs mb-3" role="tablist">
    <li class="nav-item"><button class="nav-link active fw-bold" data-bs-toggle="tab" data-bs-target="#active-tab">Active (<?= count($active) ?>)</button></li>
    <li class="nav-item"><button class="nav-link text-muted" data-bs-toggle="tab" data-bs-target="#archived-tab">Archived (<?= count($archived) ?>)</button></li>
</ul>

<div class="tab-content">
    <!-- Active Tab -->
    <div class="tab-pane fade show active" id="active-tab">
        <div class="card shadow-sm border-0">
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
                        <?php if (empty($active)): ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">No active subjects found.</td>
                            </tr>
                        <?php endif; ?>
                        <?php foreach ($active as $row): ?>
                            <tr>
                                <td><span class="badge <?= e($row['color_theme']) ?>"><?= e($row['code']) ?></span></td>
                                <td><?= e($row['name']) ?></td>
                                <td><?= e($row['professor']) ?></td>
                                <td><small class="text-muted"><i class="bi bi-clock"></i> <?= e($row['schedule'] ?? 'TBA') ?></small></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary" title="Edit" data-bs-toggle="modal" data-bs-target="#subjectModal" onclick="prepareModal('edit', <?= htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8') ?>)">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <form action="delete.php" method="POST" class="d-inline delete-form">
                                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                        <input type="hidden" name="id" value="<?= $row['id'] ?>"><input type="hidden" name="table" value="tbl_subjects"><input type="hidden" name="action" value="archive">
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

    <!-- Archived Tab -->
    <div class="tab-pane fade" id="archived-tab">
        <div class="card shadow-sm border-0">
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
                        <?php if (empty($archived)): ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">No archived subjects found.</td>
                            </tr>
                        <?php endif; ?>
                        <?php foreach ($archived as $row): ?>
                            <tr class="table-secondary">
                                <td><span class="badge <?= e($row['color_theme']) ?>"><?= e($row['code']) ?></span></td>
                                <td><del><?= e($row['name']) ?></del></td>
                                <td><small class="text-muted"><del><?= e($row['schedule'] ?? 'TBA') ?></del></small></td>
                                <td>
                                    <form action="delete.php" method="POST" class="d-inline">
                                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                        <input type="hidden" name="id" value="<?= $row['id'] ?>"><input type="hidden" name="table" value="tbl_subjects"><input type="hidden" name="action" value="restore">
                                        <button type="submit" class="btn btn-sm btn-success" title="Restore"><i class="bi bi-arrow-counterclockwise"></i></button>
                                    </form>
                                    <form action="delete.php" method="POST" class="d-inline delete-form">
                                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                        <input type="hidden" name="id" value="<?= $row['id'] ?>"><input type="hidden" name="table" value="tbl_subjects"><input type="hidden" name="action" value="hard_delete">
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

<!-- Subject Modal (Add & Edit) -->
<div class="modal fade" id="subjectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <form method="POST">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="subjectModalTitle"><i class="bi bi-journal-plus"></i> Add Subject</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4 bg-light">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    <input type="hidden" name="action" id="modalAction" value="add">
                    <input type="hidden" name="id" id="modalId" value="">

                    <div class="mb-3">
                        <label class="form-label fw-bold">Subject Code</label>
                        <input type="text" name="code" id="modalCode" class="form-control" placeholder="e.g. CPE3B" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Subject Name</label>
                        <input type="text" name="name" id="modalName" class="form-control" placeholder="e.g. Computer Engineering" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Professor</label>
                        <input type="text" name="professor" id="modalProf" class="form-control" placeholder="e.g. Dr. Smith" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Time Schedule</label>
                        <input type="text" name="schedule" id="modalSchedule" class="form-control" placeholder="e.g. M 10:00 AM - 1:00 PM">
                    </div>
                    <!-- FIX: Dynamically populating the Color Theme dropdown from config -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Color Theme</label>
                        <select name="color_theme" id="modalColor" class="form-select" required>
                            <?php foreach (COLOR_THEMES as $key => $theme): ?>
                                <option value="<?= e($key) ?>"><?= e($theme['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary fw-bold" id="modalSubmitBtn">Save Subject</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function prepareModal(mode, data = null) {
        const title = document.getElementById('subjectModalTitle');
        const submitBtn = document.getElementById('modalSubmitBtn');
        const action = document.getElementById('modalAction');

        if (mode === 'add') {
            title.innerHTML = '<i class="bi bi-journal-plus"></i> Add Subject';
            submitBtn.textContent = 'Save Subject';
            action.value = 'add';

            document.getElementById('modalId').value = '';
            document.getElementById('modalCode').value = '';
            document.getElementById('modalName').value = '';
            document.getElementById('modalProf').value = '';
            document.getElementById('modalSchedule').value = '';
            // FIX: Default to the first key in your new config array
            document.getElementById('modalColor').value = 'bg-sciets';
        } else if (mode === 'edit' && data) {
            title.innerHTML = '<i class="bi bi-pencil-square"></i> Edit Subject';
            submitBtn.textContent = 'Update Subject';
            action.value = 'edit';

            document.getElementById('modalId').value = data.id;
            document.getElementById('modalCode').value = data.code;
            document.getElementById('modalName').value = data.name;
            document.getElementById('modalProf').value = data.professor;
            document.getElementById('modalSchedule').value = data.schedule || '';
            document.getElementById('modalColor').value = data.color_theme;
        }
    }
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>