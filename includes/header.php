<?php
require_once __DIR__ . '/../config/config.php';
$isAdmin = isset($_SESSION['admin_id']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? $pageTitle . ' - CPE2B' : 'CPE2B Announcements' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>

<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4 shadow-sm">
        <div class="container">

            <?php if ($isAdmin): ?>
                <a class="navbar-brand fw-bold d-flex align-items-center" href="/admin/index.php">
                    <i class="bi bi-shield-lock fs-4 me-2"></i>
                    Admin: <?= e($_SESSION['username'] ?? 'User') ?>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item"><a class="nav-link" href="/public/index.php">Portal</a></li>
                        <li class="nav-item"><a class="nav-link" href="/admin/index.php">Announcements</a></li>
                        <li class="nav-item"><a class="nav-link" href="/admin/subjects.php">Subjects</a></li>
                        <li class="nav-item"><a class="nav-link" href="/admin/history.php">Audit Log</a></li>
                        <li class="nav-item ms-lg-3"><a class="nav-link text-danger fw-bold" href="/auth/logout.php">Logout</a></li>
                    </ul>
                </div>

            <?php else: ?>
                <a class="navbar-brand fw-bold mx-auto" href="/">CPE-2B 2nd Semester SY 2025-2026</a>
            <?php endif; ?>

        </div>
    </nav>
    <div class="container pb-5">
        <?php require_once __DIR__ . '/helpers.php';
        displayFlash(); ?>