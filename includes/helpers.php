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

function parseScheduleTime($schedule)
{
    if (!$schedule) return 9999999999;
    if (preg_match('/(\d{1,2}(:\d{2})?\s*[AP]M)/i', $schedule, $matches)) {
        return strtotime($matches[1]);
    }
    return 9999999999;
}

function getActiveAnnouncements($pdo)
{
    $announcements = $pdo->query("SELECT a.*, s.code, s.name, s.professor, s.schedule, s.color_theme
                        FROM tbl_announcements a
                        LEFT JOIN tbl_subjects s ON a.subject_id = s.id
                        WHERE a.status = 'active' ORDER BY ISNULL(a.due_date), a.due_date ASC")->fetchAll();

    usort($announcements, function ($a, $b) {
        if ($a['due_date'] !== $b['due_date']) return 0;
        $timeA = parseScheduleTime($a['schedule']);
        $timeB = parseScheduleTime($b['schedule']);
        if ($timeA !== $timeB) return $timeA <=> $timeB;
        return strcmp($a['code'], $b['code']);
    });
    return $announcements;
}

function getDaysLeft(string $dueDate): array
{
    $today = new DateTime('today', new DateTimeZone('Asia/Manila'));
    $due   = new DateTime($dueDate, new DateTimeZone('Asia/Manila'));
    $diff  = (int) $today->diff($due)->format('%r%a');

    if ($diff > 3)       return ['label' => "{$diff} days left",  'class' => 'text-success'];
    if ($diff === 1)     return ['label' => 'Due tomorrow!',      'class' => 'text-danger fw-bold'];
    if ($diff > 0)       return ['label' => "{$diff} days left",  'class' => 'text-warning fw-bold'];
    if ($diff === 0)     return ['label' => 'Due today!',         'class' => 'text-danger fw-bold'];
    return               ['label' => abs($diff) . 'd overdue',    'class' => 'text-danger text-decoration-line-through'];
}

function autoArchiveOverdue(PDO $pdo): void
{
    $today = (new DateTime('today', new DateTimeZone('Asia/Manila')))->format('Y-m-d');
    $sql = "UPDATE tbl_announcements
            SET status = 'archived'
            WHERE status = 'active'
            AND due_date IS NOT NULL
            AND (
                (end_date IS NOT NULL AND end_date < ?) OR
                (end_date IS NULL AND due_date < ?)
            )";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$today, $today]);
}
