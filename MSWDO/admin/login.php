<?php
if (!session_id()) {
    session_start();
}
// If already logged in, redirect
if (isset($_SESSION['admin_id'])) {
    header("Location: /admin/dashboard.php");
    exit();
}

$error = isset($_GET['error']) ? $_GET['error'] : '';
$error_msg = '';
if ($error == 'empty') {
    $error_msg = 'Please enter your username/email and password.';
} elseif ($error == 'invalid') {
    $error_msg = 'Incorrect username/email or password.';
} elseif ($error == 'unauthorized') {
    $error_msg = 'Access denied. Please log in first.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Staff Login - MSWDO Tubungan, Iloilo</title>
    <link rel="stylesheet" href="/css/style.css">
    <style>
        body {
            background-color: #0f172a;
        }
    </style>
</head>
<body>
    <div class="app-container">
        <?php include '../includes/navbar.php'; ?>

        <div class="auth-wrapper">
            <div class="auth-container" style="border-top-color: var(--accent); background-color: #1e293b; color: white;">
                <div class="auth-header">
                    <div class="auth-logo" style="background-color: #1e293b;">
                        <div class="auth-logo-inner" style="background-color: var(--accent);"></div>
                    </div>
                    <h2 class="auth-title" style="color: white;">OFFICIAL ADMIN LOGIN</h2>
                    <p class="auth-subtitle" style="color: #cbd5e1;">MSWDO Staff & Officers Only</p>
                </div>

                <?php if ($error_msg): ?>
                    <div class="alert alert-danger" style="background-color: rgba(220, 38, 38, 0.2); color: #fca5a5; border-color: rgba(220, 38, 38, 0.4);">
                        <span class="material-symbols-outlined" style="font-size: 20px;">error</span>
                        <div><?php echo htmlspecialchars($error_msg); ?></div>
                    </div>
                <?php endif; ?>

                <form action="/php/actions/admin_login.php" method="POST">
                    <div class="form-group">
                        <label for="admin-user" style="color: #cbd5e1;">Username or Email</label>
                        <input type="text" name="username_or_email" id="admin-user" class="form-control" style="background: #0f172a; color: white; border-color: #475569;" placeholder="Enter admin username" required>
                    </div>

                    <div class="form-group">
                        <label for="admin-pass" style="color: #cbd5e1;">Security Password</label>
                        <input type="password" name="password" id="admin-pass" class="form-control" style="background: #0f172a; color: white; border-color: #475569;" placeholder="••••••••" required>
                    </div>

                    <button type="submit" class="btn btn-accent" style="width: 100%; margin-top: 1.5rem; font-weight: 700;">
                        <span class="material-symbols-outlined" style="font-size: 18px;">admin_panel_settings</span> Authenticate Staff
                    </button>
                </form>

                <div style="text-align: center; margin-top: 1.5rem; font-size: 0.8rem; color: #94a3b8;">
                    Secure terminal access. Unauthorized attempts will be logged.
                </div>
            </div>
        </div>

        <?php include '../includes/footer.php'; ?>
    </div>
</body>
</html>
