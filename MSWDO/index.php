<?php
if (!session_id()) {
    session_start();
}
// If already logged in, redirect to dashboard
if (isset($_SESSION['client_id'])) {
    header("Location: /client/dashboard.php");
    exit();
}

$error = isset($_GET['error']) ? $_GET['error'] : '';
$success = isset($_GET['registered']) ? '1' : '';
$loggedout = isset($_GET['loggedout']) ? '1' : '';

$error_msg = '';
if ($error == 'nouser') {
    $error_msg = 'No account found with this email address.';
} elseif ($error == 'invalid') {
    $error_msg = 'Incorrect password. Please try again.';
} elseif ($error == 'exists') {
    $error_msg = 'This email address is already registered.';
} elseif ($error == 'mismatch') {
    $error_msg = 'Passwords do not match.';
} elseif ($error == 'empty') {
    $error_msg = 'Please fill in all required fields.';
} elseif ($error == 'unauthorized') {
    $error_msg = 'Please log in to access this page.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Portal Login - MSWDO Tubungan, Iloilo</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <div class="app-container">
        <?php include 'includes/navbar.php'; ?>

        <div class="auth-wrapper">
            <div class="auth-container">
                <div class="auth-header">
                    <div class="auth-logo">
                        <div class="auth-logo-inner"></div>
                    </div>
                    <h2 class="auth-title">MSWDO CLIENT PORTAL</h2>
                    <p class="auth-subtitle">Municipal Government of Tubungan, Iloilo</p>
                </div>

                <?php if ($error_msg): ?>
                    <div class="alert alert-danger">
                        <span class="material-symbols-outlined" style="font-size: 20px;">error</span>
                        <div><?php echo htmlspecialchars($error_msg); ?></div>
                    </div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <div class="alert alert-success">
                        <span class="material-symbols-outlined" style="font-size: 20px;">check_circle</span>
                        <div>Registration successful! You can now log in.</div>
                    </div>
                <?php endif; ?>

                <?php if ($loggedout): ?>
                    <div class="alert alert-success" style="background-color: #f0fdf4; color: #15803d; border-color: #bbf7d0;">
                        <span class="material-symbols-outlined" style="font-size: 20px;">info</span>
                        <div>You have been successfully logged out.</div>
                    </div>
                <?php endif; ?>

                <!-- LOGIN FORM PANEL -->
                <div id="login-panel" class="auth-form-panel active">
                    <form action="/php/actions/client_login.php" method="POST">
                        <div class="form-group">
                            <label for="login-email">Email Address</label>
                            <input type="email" name="email" id="login-email" class="form-control" placeholder="Enter your email" required autocomplete="email">
                        </div>

                        <div class="form-group">
                            <label for="login-password">Password</label>
                            <input type="password" name="password" id="login-password" class="form-control" placeholder="Enter your password" required autocomplete="current-password">
                        </div>

                        <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">
                            <span class="material-symbols-outlined" style="font-size: 18px;">login</span> Sign In
                        </button>
                    </form>

                    <div style="text-align: center; margin-top: 1.5rem; font-size: 0.875rem; color: var(--text-muted);">
                        Don't have an account yet? <a class="auth-toggle-link" onclick="toggleAuthForm('register')">Register here</a>
                    </div>
                </div>

                <!-- REGISTER FORM PANEL -->
                <div id="register-panel" class="auth-form-panel">
                    <form action="/php/actions/client_register.php" method="POST">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                            <div class="form-group">
                                <label for="reg-firstname">First Name</label>
                                <input type="text" name="first_name" id="reg-firstname" class="form-control" placeholder="First Name" required>
                            </div>
                            <div class="form-group">
                                <label for="reg-lastname">Last Name</label>
                                <input type="text" name="last_name" id="reg-lastname" class="form-control" placeholder="Last Name" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="reg-email">Email Address</label>
                            <input type="email" name="email" id="reg-email" class="form-control" placeholder="name@example.com" required autocomplete="email">
                        </div>

                        <div class="form-group">
                            <label for="reg-password">Password</label>
                            <input type="password" name="password" id="reg-password" class="form-control" placeholder="Minimum 6 characters" required autocomplete="new-password">
                        </div>

                        <div class="form-group">
                            <label for="reg-confirm">Confirm Password</label>
                            <input type="password" name="confirm_password" id="reg-confirm" class="form-control" placeholder="Repeat password" required autocomplete="new-password">
                        </div>

                        <button type="submit" class="btn btn-accent" style="width: 100%; margin-top: 1rem;">
                            <span class="material-symbols-outlined" style="font-size: 18px;">how_to_reg</span> Register Account
                        </button>
                    </form>

                    <div style="text-align: center; margin-top: 1.5rem; font-size: 0.875rem; color: var(--text-muted);">
                        Already have an account? <a class="auth-toggle-link" onclick="toggleAuthForm('login')">Login here</a>
                    </div>
                </div>

            </div>
        </div>

        <?php include 'includes/footer.php'; ?>
    </div>

    <script>
        function toggleAuthForm(mode) {
            const loginPanel = document.getElementById('login-panel');
            const registerPanel = document.getElementById('register-panel');
            
            if (mode === 'register') {
                loginPanel.classList.remove('active');
                registerPanel.classList.add('active');
            } else {
                registerPanel.classList.remove('active');
                loginPanel.classList.add('active');
            }
        }
    </script>
</body>
</html>
