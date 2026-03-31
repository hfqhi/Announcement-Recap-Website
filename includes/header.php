<?php
require_once __DIR__ . '/../config/config.php';
$isPublicView = strpos($_SERVER['SCRIPT_NAME'], '/public/index.php') !== false;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? $pageTitle . ' - CPE2B' : 'CPE2B Announcements' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4 shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="<?= BASE_URL ?>/public/index.php"> CPE-2B 2nd Semester SY 2025-2026</a>
        <?php if (!$isPublicView): ?>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <?php if (isset($_SESSION['admin_id'])): ?>
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/admin/index.php">Announcements</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/admin/subjects.php">Subjects</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/admin/history.php">Audit Log</a></li>
                        <li class="nav-item"><a class="nav-link text-danger" href="<?= BASE_URL ?>/auth/logout.php">Logout</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/auth/login.php">Admin Login</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>
</nav>
<div class="container pb-5">
<?php require_once __DIR__ . '/helpers.php'; displayFlash(); ?>