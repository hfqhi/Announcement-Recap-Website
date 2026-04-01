<?php
// includes/helpers.php

// Strict XSS Prevention
function e($string)
{
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

// CSRF Verification
function verifyCsrf($token)
{
    if (!hash_equals($_SESSION['csrf_token'], $token)) {
        die("Security Check Failed: Invalid CSRF Token.");
    }
}

// Flash Messages
function setFlash($type, $message)
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function displayFlash()
{
    if (isset($_SESSION['flash'])) {
        $f = $_SESSION['flash'];
        echo "<div class='alert alert-{$f['type']} alert-dismissible fade show shadow-sm' role='alert'>
                {$f['message']} <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
              </div>";
        unset($_SESSION['flash']);
    }
}

// Upgraded Audit Log Engine
function logAction($pdo, $adminId, $announcementId, $action, $oldData = null, $newData = null, $deletedRecordId = null)
{
    $stmt = $pdo->prepare(
        "INSERT INTO tbl_audit_log
          (admin_id, announcement_id, deleted_record_id, action, old_value, new_value)
         VALUES (?, ?, ?, ?, ?, ?)"
    );
    $stmt->execute([
        $adminId,
        $announcementId,
        $deletedRecordId,
        $action,
        $oldData ? json_encode($oldData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : null,
        $newData ? json_encode($newData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : null,
    ]);
}

function getActiveAnnouncements($pdo)
{
    return $pdo->query("SELECT a.*, s.code, s.name, s.professor, s.schedule, s.color_theme
                        FROM tbl_announcements a
                        LEFT JOIN tbl_subjects s ON a.subject_id = s.id
                        WHERE a.status = 'active' ORDER BY ISNULL(a.due_date), a.due_date ASC")->fetchAll();
}

// New Deadline Computation Engine
function getDaysLeft(string $dueDate): array
{
    $today = new DateTime('today', new DateTimeZone('Asia/Manila'));
    $due   = new DateTime($dueDate, new DateTimeZone('Asia/Manila'));
    $diff  = (int) $today->diff($due)->format('%r%a');

    if ($diff > 3)       return ['label' => "{$diff} days left",  'class' => 'text-success'];
    if ($diff > 0)       return ['label' => "{$diff} days left",  'class' => 'text-warning fw-bold'];
    if ($diff === 0)     return ['label' => 'Due today!',         'class' => 'text-danger fw-bold'];
    return               ['label' => abs($diff) . 'd overdue',    'class' => 'text-danger text-decoration-line-through'];
}
