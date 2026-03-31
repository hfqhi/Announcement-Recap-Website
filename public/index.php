<?php
// public/index.php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php';
$pageTitle = "CPE2B Recap";

// Fetch active announcements
$stmt = $pdo->query("SELECT * FROM tbl_announcements WHERE status = 'active' ORDER BY subject ASC, due_date ASC");
$announcements = $stmt->fetchAll();

include __DIR__ . '/../includes/header.php';
?>
<div class="text-center mb-5 mt-3">
    <h1 class="display-5 fw-bold text-uppercase" style="letter-spacing: 2px;">Announcement Recap</h1>
    <h4 class="text-danger font-monospace"><?= date('M d, Y (l)') ?></h4>
</div>

<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
    <?php if(empty($announcements)): ?>
        <div class="col-12 text-center text-muted"><p>No active announcements at the moment.</p></div>
    <?php endif; ?>

    <?php foreach($announcements as $row): ?>
        <div class="col">
            <div class="card h-100 shadow-sm border-0 announcement-card">
                <div class="card-header border-0 text-center p-0">
                    <?= getSubjectBadge($row['subject']) ?>
                </div>
                <div class="card-body pt-4 bg-light rounded-bottom">
                    <h5 class="card-title fw-bold"><?= htmlspecialchars($row['title']) ?></h5>
                    <div class="card-text mb-3">
                        <?= nl2br(htmlspecialchars($row['content'])) ?>
                    </div>
                    <?php if($row['due_date']): ?>
                        <div class="text-danger fw-bold small">
                            <i class="bi bi-calendar-event"></i> Due: <?= date('D, M d', strtotime($row['due_date'])) ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>