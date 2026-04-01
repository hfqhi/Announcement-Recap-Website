<?php
// includes/helpers.php

// Strict XSS Prevention: Wrap ALL user output in this
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

// Flash Messages (Auto-dismissing alerts)
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

// Database Helpers (MVC Lite)
function logAction($pdo, $adminId, $announcementId, $action)
{
    $stmt = $pdo->prepare("INSERT INTO tbl_audit_log (admin_id, announcement_id, action) VALUES (?, ?, ?)");
    $stmt->execute([$adminId, $announcementId, $action]);
}

function getActiveAnnouncements($pdo)
{
    return $pdo->query("SELECT a.*, s.code, s.name, s.professor, s.schedule, s.color_theme
                        FROM tbl_announcements a
                        LEFT JOIN tbl_subjects s ON a.subject_id = s.id
                        WHERE a.status = 'active' ORDER BY a.due_date ASC")->fetchAll();
}
