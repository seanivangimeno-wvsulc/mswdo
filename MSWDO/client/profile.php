<?php
require_once '../includes/auth_client.php';
require_once '../includes/supabase.php';

$client_id = $_SESSION['client_id'];
$success_msg = '';
$error_msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $age = !empty($_POST['age']) ? intval($_POST['age']) : null;
    $sex = trim($_POST['sex'] ?? '');
    $civil_status = trim($_POST['civil_status'] ?? '');
    $date_of_birth = !empty($_POST['date_of_birth']) ? $_POST['date_of_birth'] : null;
    $place_of_birth = trim($_POST['place_of_birth'] ?? '');
    $contact_number = trim($_POST['contact_number'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $religion = trim($_POST['religion'] ?? '');
    $occupation = trim($_POST['occupation'] ?? '');
    $educational_attainment = trim($_POST['educational_attainment'] ?? '');

    if (empty($first_name) || empty($last_name) || empty($address) || empty($contact_number)) {
        $error_msg = 'First Name, Last Name, Address, and Contact Number are required fields.';
    } else {
        $update_data = [
            'first_name' => $first_name,
            'last_name' => $last_name,
            'age' => $age,
            'sex' => $sex,
            'civil_status' => $civil_status,
            'date_of_birth' => $date_of_birth,
            'place_of_birth' => $place_of_birth,
            'contact_number' => $contact_number,
            'address' => $address,
            'religion' => $religion,
            'occupation' => $occupation,
            'educational_attainment' => $educational_attainment
        ];

        $patch_response = supabase_query('tb_clients', 'PATCH', ["id=eq." . $client_id], $update_data);

        if (isset($patch_response['error'])) {
            $error_msg = 'Database error. Failed to save updates.';
        } else {
            $success_msg = 'Your client profile has been updated successfully.';
            $_SESSION['client_name'] = $first_name . ' ' . $last_name;
        }
    }
}

// Fetch current profile details
$filters = ["id=eq." . $client_id, "select=*"];
$profile_response = supabase_query('tb_clients', 'GET', $filters);
$client = (!empty($profile_response) && is_array($profile_response) && !isset($profile_response['error'])) ? $profile_response[0] : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - MSWDO Tubungan, Iloilo</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <div class="app-container">
        <?php include '../includes/navbar.php'; ?>

        <main class="main-content" style="max-width: 800px;">
            <div class="card">
                <h3 style="font-size: 1.25rem; color: var(--primary-dark); margin-bottom: 1.5rem; display: flex; align-items: center; gap: 8px; border-bottom: 2px solid var(--accent); padding-bottom: 6px;">
                    <span class="material-symbols-outlined">account_circle</span>
                    Edit Your Client Profile
                </h3>

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

                <form action="/client/profile.php" method="POST">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div class="form-group">
                            <label for="first_name">First Name *</label>
                            <input type="text" name="first_name" id="first_name" class="form-control" value="<?php echo htmlspecialchars($client['first_name'] ?? ''); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="last_name">Last Name *</label>
                            <input type="text" name="last_name" id="last_name" class="form-control" value="<?php echo htmlspecialchars($client['last_name'] ?? ''); ?>" required>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px;">
                        <div class="form-group">
                            <label for="age">Age</label>
                            <input type="number" name="age" id="age" class="form-control" value="<?php echo htmlspecialchars($client['age'] ?? ''); ?>" min="0" max="120">
                        </div>
                        <div class="form-group">
                            <label for="sex">Sex</label>
                            <select name="sex" id="sex" class="form-control">
                                <option value="">Select Sex</option>
                                <option value="Male" <?php echo ($client['sex'] ?? '') === 'Male' ? 'selected' : ''; ?>>Male</option>
                                <option value="Female" <?php echo ($client['sex'] ?? '') === 'Female' ? 'selected' : ''; ?>>Female</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="civil_status">Civil Status</label>
                            <select name="civil_status" id="civil_status" class="form-control">
                                <option value="">Select Status</option>
                                <option value="Single" <?php echo ($client['civil_status'] ?? '') === 'Single' ? 'selected' : ''; ?>>Single</option>
                                <option value="Married" <?php echo ($client['civil_status'] ?? '') === 'Married' ? 'selected' : ''; ?>>Married</option>
                                <option value="Widowed" <?php echo ($client['civil_status'] ?? '') === 'Widowed' ? 'selected' : ''; ?>>Widowed</option>
                                <option value="Separated" <?php echo ($client['civil_status'] ?? '') === 'Separated' ? 'selected' : ''; ?>>Separated</option>
                            </select>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div class="form-group">
                            <label for="date_of_birth">Date of Birth</label>
                            <input type="date" name="date_of_birth" id="date_of_birth" class="form-control" value="<?php echo htmlspecialchars($client['date_of_birth'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label for="place_of_birth">Place of Birth</label>
                            <input type="text" name="place_of_birth" id="place_of_birth" class="form-control" value="<?php echo htmlspecialchars($client['place_of_birth'] ?? ''); ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="address">Full Address (Barangay, Tubungan, Iloilo) *</label>
                        <input type="text" name="address" id="address" class="form-control" value="<?php echo htmlspecialchars($client['address'] ?? ''); ?>" required>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div class="form-group">
                            <label for="contact_number">Contact Number *</label>
                            <input type="text" name="contact_number" id="contact_number" class="form-control" value="<?php echo htmlspecialchars($client['contact_number'] ?? ''); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="religion">Religion</label>
                            <input type="text" name="religion" id="religion" class="form-control" value="<?php echo htmlspecialchars($client['religion'] ?? ''); ?>">
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div class="form-group">
                            <label for="occupation">Occupation</label>
                            <input type="text" name="occupation" id="occupation" class="form-control" value="<?php echo htmlspecialchars($client['occupation'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label for="educational_attainment">Educational Attainment</label>
                            <input type="text" name="educational_attainment" id="educational_attainment" class="form-control" value="<?php echo htmlspecialchars($client['educational_attainment'] ?? ''); ?>">
                        </div>
                    </div>

                    <div style="border-top: 1px solid #e2e8f0; padding-top: 1.5rem; margin-top: 2rem; display: flex; justify-content: flex-end; gap: 12px;">
                        <a href="/client/dashboard.php" class="btn btn-outline">Back to Dashboard</a>
                        <button type="submit" class="btn btn-primary">Save Profile Updates</button>
                    </div>
                </form>
            </div>
        </main>

        <?php include '../includes/footer.php'; ?>
    </div>
</body>
</html>
