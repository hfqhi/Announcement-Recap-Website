<?php
// includes/helpers.php

function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

function logAction($pdo, $adminId, $announcementId, $action, $oldValue = null, $newValue = null) {
    $stmt = $pdo->prepare("INSERT INTO tbl_audit_log (admin_id, announcement_id, action, old_value, new_value) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([
        $adminId,
        $announcementId,
        $action,
        $oldValue ? json_encode($oldValue) : null,
        $newValue ? json_encode($newValue) : null
    ]);
}

function getSubjectBadge($subject) {
    $subjects = [
        'SCIETS' => 'bg-sciets',
        'CONTWO' => 'bg-contwo',
        'ENECO' => 'bg-eneco',
        'ECENG' => 'bg-eceng',
        'SOFTDES' => 'bg-softdes',
        'NUMERICAL' => 'bg-numerical',
        'RIZAL' => 'bg-rizal',
        'PEHEF2' => 'bg-pehef2',
        'OTHER' => 'bg-other'
    ];
    $class = $subjects[strtoupper($subject)] ?? 'bg-other';
    return "<span class='badge w-100 py-2 fs-5 $class'>" . strtoupper($subject) . "</span>";
}

function getSubjectOptions() {
    return ['SCIETS', 'CONTWO', 'ENECO', 'ECENG', 'SOFTDES', 'NUMERICAL', 'RIZAL', 'PEHEF2', 'OTHER'];
}