<?php
require_once '../includes/auth_admin.php';
require_once '../includes/supabase.php';

$success_msg = '';
$error_msg = '';

// Check if submitting a new focal person
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_focal') {
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $contact = trim($_POST['contact'] ?? '');
    $program_id = isset($_POST['program_id']) ? intval($_POST['program_id']) : 0;
    $password = $_POST['password'] ?? '';

    if (empty($first_name) || empty($last_name) || empty($email) || empty($password) || $program_id <= 0) {
        $error_msg = 'All fields (First Name, Last Name, Email, Password, Program assignment) are required.';
    } else {
        // Hash the password with standard PHP bcrypt
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        
        $new_focal_data = [
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'contact_number' => $contact,
            'program_id' => $program_id,
            'password' => $hashed_password
        ];

        $post_res = supabase_query('tb_focal_person', 'POST', [], $new_focal_data);

        if (isset($post_res['error'])) {
            $error_msg = 'Failed to register Focal Person. The email might already be registered.';
        } else {
            $success_msg = "Successfully added " . htmlspecialchars($first_name . ' ' . $last_name) . " as Sectoral Focal Officer.";
        }
    }
}

// Fetch focal persons
$foc_response = supabase_query('tb_focal_person', 'GET', ["select=*"]);
$focal_persons = is_array($foc_response) && !isset($foc_response['error']) ? $foc_response : [];

// Fetch programs
$prog_response = supabase_query('tb_program', 'GET', ["select=*"]);
$programs = is_array($prog_response) && !isset($prog_response['error']) ? $prog_response : [];

$program_map = [];
foreach ($programs as $prog) {
    $program_map[$prog['id']] = $prog['program_name'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Focal Persons - MSWDO Portal</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/admin.css">
</head>
<body>
    <div class="app-container">
        <div class="admin-layout">
            <?php include '../includes/admin_sidebar.php'; ?>

            <main class="admin-panel">
                <div class="admin-header">
                    <div>
                        <h2 style="font-size: 1.5rem; color: var(--primary-dark); margin: 0;">Social Protection Focal Officers</h2>
                        <p style="font-size: 0.8rem; color: var(--text-muted); margin-top: 2px;">Manage official focal officers assigned to run individual sectoral offices.</p>
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

                <div style="display: grid; grid-template-columns: 7fr 5fr; gap: 1.5rem; align-items: start;">
                    
                    <!-- Left: focal officers table -->
                    <div class="card" style="margin: 0;">
                        <h3 style="font-size: 1.1rem; color: var(--primary-dark); margin-bottom: 1.25rem;">Authorized Focal Officers</h3>
                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Officer ID</th>
                                        <th>Full Name</th>
                                        <th>Email Address</th>
                                        <th>Assigned Desk</th>
                                        <th>Contact</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($focal_persons)): ?>
                                        <tr>
                                            <td colspan="5" style="text-align: center; padding: 2rem; color: var(--text-muted);">No focal officers registered.</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($focal_persons as $foc): ?>
                                            <tr>
                                                <td><strong>#<?php echo $foc['id']; ?></strong></td>
                                                <td><strong><?php echo htmlspecialchars($foc['first_name'] . ' ' . $foc['last_name']); ?></strong></td>
                                                <td><?php echo htmlspecialchars($foc['email']); ?></td>
                                                <td>
                                                    <span style="font-weight: 600; color: var(--primary);"><?php echo htmlspecialchars($program_map[$foc['program_id']] ?? 'General Relief'); ?></span>
                                                </td>
                                                <td><?php echo htmlspecialchars($foc['contact_number'] ?: 'N/A'); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Right: Add form -->
                    <div class="card" style="margin: 0;">
                        <h3 style="font-size: 1.1rem; color: var(--primary-dark); margin-bottom: 1.25rem;">Register New Focal Officer</h3>
                        <form action="/admin/focal_persons.php" method="POST">
                            <input type="hidden" name="action" value="add_focal">

                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                                <div class="form-group">
                                    <label for="f-first">First Name *</label>
                                    <input type="text" name="first_name" id="f-first" class="form-control" required placeholder="E.g., Maria">
                                </div>
                                <div class="form-group">
                                    <label for="f-last">Last Name *</label>
                                    <input type="text" name="last_name" id="f-last" class="form-control" required placeholder="E.g., Santos">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="f-email">Email Address *</label>
                                <input type="email" name="email" id="f-email" class="form-control" required placeholder="E.g., maria@tubungan.gov.ph">
                            </div>

                            <div class="form-group">
                                <label for="f-contact">Contact Number</label>
                                <input type="text" name="contact" id="f-contact" class="form-control" placeholder="E.g., 09123456789">
                            </div>

                            <div class="form-group">
                                <label for="f-program">Assigned Social Program *</label>
                                <select name="program_id" id="f-program" class="form-control" required>
                                    <option value="">-- Choose Assigned Desk --</option>
                                    <?php foreach ($programs as $prog): ?>
                                        <option value="<?php echo $prog['id']; ?>"><?php echo htmlspecialchars($prog['program_name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="f-pass">Temporary Password *</label>
                                <input type="password" name="password" id="f-pass" class="form-control" required placeholder="Minimum 6 characters">
                            </div>

                            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">
                                <span class="material-symbols-outlined">badge</span> Register Sectoral Focal
                            </button>
                        </form>
                    </div>

                </div>
            </main>
        </div>
    </div>
</body>
</html>
