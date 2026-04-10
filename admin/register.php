<?php
// turn off registration for security hardening. To enable, uncomment the code below and the link in header.php
/*
// admin/register.php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth_guard.php';
require_once __DIR__ . '/../includes/helpers.php';
$pageTitle = "Register New Admin";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf($_POST['csrf_token'] ?? '');

    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    // 1. Check if username already exists
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM tbl_admins WHERE username = ?");
    $stmt->execute([$username]);

    if (empty($username) || empty($password)) {
        setFlash('danger', 'All fields are required.');
    } elseif ($stmt->fetchColumn() > 0) {
        setFlash('danger', 'That username is already taken. Choose another one.');
    } elseif ($password !== $confirm) {
        setFlash('danger', 'Passwords do not match.');
    } elseif (strlen($password) < 6) {
        setFlash('danger', 'Password must be at least 6 characters long.');
    } else {
        // 2. Hash and insert
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $insert = $pdo->prepare("INSERT INTO tbl_admins (username, password_hash) VALUES (?, ?)");
        $insert->execute([$username, $hash]);

        // 3. Log the action
        logAction($pdo, $_SESSION['admin_id'], null, 'created', null, 'New Admin Account Created: ' . $username);

        setFlash('success', "Account for <strong>{$username}</strong> has been created successfully!");
        header("Location: register.php");
        exit();
    }
}

include __DIR__ . '/../includes/header.php';
?>
<div class="row justify-content-center mt-4">
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white text-center py-3">
                <h4 class="mb-0"><i class="bi bi-person-plus-fill"></i> Create Sub-Admin Account</h4>
            </div>
            <div class="card-body p-4 bg-light">
                <p class="text-muted small text-center mb-4">Create a secure login for a fellow teacher or class representative.</p>
                <form method="POST">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                    <div class="mb-3">
                        <label class="form-label fw-bold text-secondary">Username</label>
                        <input type="text" name="username" class="form-control" placeholder="e.g. mr_smith" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-secondary">Temporary Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold text-secondary">Confirm Password</label>
                        <input type="password" name="confirm_password" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">Create Account</button>
                </form>
            </div>
        </div>
    </div>
</div>
*/