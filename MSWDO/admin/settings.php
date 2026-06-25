<?php
require_once '../includes/auth_admin.php';
require_once '../includes/supabase.php';

$success_msg = '';
$error_msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_pass = $_POST['current_password'] ?? '';
    $new_pass = $_POST['new_password'] ?? '';
    $confirm_pass = $_POST['confirm_password'] ?? '';

    if (empty($current_pass) || empty($new_pass) || empty($confirm_pass)) {
        $error_msg = 'All password fields are required.';
    } elseif ($new_pass !== $confirm_pass) {
        $error_msg = 'New Password and Password Confirmation do not match.';
    } elseif (strlen($new_pass) < 6) {
        $error_msg = 'New Password must be at least 6 characters.';
    } else {
        // Fetch current admin profile to verify current password
        $admin_id = $_SESSION['admin_id'];
        $filt = ["id=eq." . $admin_id, "select=*"];
        $res = supabase_query('tb_admin', 'GET', $filt);

        if (!empty($res) && is_array($res) && !isset($res['error'])) {
            $admin = $res[0];
            
            if (password_verify($current_pass, $admin['password'])) {
                // Update with new password
                $hashed_password = password_hash($new_pass, PASSWORD_BCRYPT);
                supabase_query('tb_admin', 'PATCH', ["id=eq." . $admin_id], ['password' => $hashed_password]);
                $success_msg = 'Your administrative system password was successfully updated.';
            } else {
                $error_msg = 'Current password entered is incorrect.';
            }
        } else {
            $error_msg = 'Administrative account not found.';
        }
    }
}
?>
<!DOCTYPEDOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Settings - MSWDO Portal</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/admin.css">
</head>
<body>
    <div class="app-container">
        <div class="admin-layout">
            <?php include '../includes/admin_sidebar.php'; ?>

            <main class="admin-panel" style="max-width: 800px;">
                <div class="admin-header">
                    <div>
                        <h2 style="font-size: 1.5rem; color: var(--primary-dark); margin: 0;">Administrative Console Settings</h2>
                        <p style="font-size: 0.8rem; color: var(--text-muted); margin-top: 2px;">Update credential keys, security passwords, and core staff settings.</p>
                    </div>
                </div>

                <?php if ($success_msg): ?>
                    <div class="alert alert-success">
                        <span class="material-symbols-outlined">check_circle</span>
                        <div><?php echo htmlspecialchars($success_msg); ?></div>
                    </div>
                <?php endif; ?>

                <?php if ($error_msg): ?>
                    <div class="alert alert-danger">
                        <span class="material-symbols-outlined">error</span>
                        <div><?php echo htmlspecialchars($error_msg); ?></div>
                    </div>
                <?php endif; ?>

                <div class="card">
                    <h3 style="font-size: 1.1rem; color: var(--primary-dark); margin-bottom: 1.5rem; display: flex; align-items: center; gap: 8px;">
                        <span class="material-symbols-outlined">key</span>
                        Change System Access Password
                    </h3>

                    <form action="/admin/settings.php" method="POST" style="max-width: 500px;">
                        <div class="form-group">
                            <label for="current_password">Enter Current Password *</label>
                            <input type="password" name="current_password" id="current_password" class="form-control" placeholder="••••••••" required>
                        </div>

                        <div class="form-group">
                            <label for="new_password">Enter New Password *</label>
                            <input type="password" name="new_password" id="new_password" class="form-control" placeholder="••••••••" required>
                            <small style="color: var(--text-muted); font-size: 0.75rem;">Minimum of 6 alphanumeric characters.</small>
                        </div>

                        <div class="form-group">
                            <label for="confirm_password">Confirm New Password *</label>
                            <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="••••••••" required>
                        </div>

                        <div style="border-top: 1px solid #e2e8f0; padding-top: 1.25rem; margin-top: 1.75rem; text-align: right;">
                            <button type="submit" class="btn btn-primary">
                                <span class="material-symbols-outlined" style="font-size: 16px;">lock_reset</span> Reset Access Code
                            </button>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
