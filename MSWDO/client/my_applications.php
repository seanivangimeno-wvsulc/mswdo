<?php
require_once '../includes/auth_client.php';
require_once '../includes/supabase.php';

$client_id = $_SESSION['client_id'];
$success_msg = isset($_GET['submitted']) ? 'Your AICS application has been submitted successfully to the MSWDO Office! Social caseworkers will review it shortly.' : '';

$selected_app_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$selected_app = null;
$selected_famcom = [];

// Map service IDs to names
$service_map = [
    1 => 'Medical Assistance',
    2 => 'Burial Assistance',
    3 => 'Educational Assistance',
    4 => 'Food Assistance',
    5 => 'Transportation Assistance'
];

if ($selected_app_id > 0) {
    // Fetch specific application
    $app_filters = ["id=eq." . $selected_app_id, "client_id=eq." . $client_id, "select=*"];
    $app_res = supabase_query('tb_aics_applications', 'GET', $app_filters);
    
    if (!empty($app_res) && is_array($app_res) && !isset($app_res['error'])) {
        $selected_app = $app_res[0];
        
        // Fetch family composition for this application
        $fam_filters = ["application_id=eq." . $selected_app_id, "select=*"];
        $fam_res = supabase_query('tb_aics_famcom', 'GET', $fam_filters);
        if (is_array($fam_res) && !isset($fam_res['error'])) {
            $selected_famcom = $fam_res;
        }
    }
}

// Fetch list of all applications for the logged-in client
$filters = ["client_id=eq." . $client_id, "select=*"];
$apps = supabase_query('tb_aics_applications', 'GET', $filters);

if (is_array($apps) && !isset($apps['error'])) {
    // Sort recent first
    usort($apps, function($a, $b) {
        return strcmp($b['created_at'], $a['created_at']);
    });
} else {
    $apps = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Applications - MSWDO Tubungan, Iloilo</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <div class="app-container">
        <?php include '../includes/navbar.php'; ?>

        <main class="main-content">
            <?php if ($success_msg): ?>
                <div class="alert alert-success" style="margin-bottom: 2rem;">
                    <span class="material-symbols-outlined">check_circle</span>
                    <div><?php echo htmlspecialchars($success_msg); ?></div>
                </div>
            <?php endif; ?>

            <div style="display: grid; grid-template-columns: <?php echo $selected_app ? '5fr 7fr' : '1fr'; ?>; gap: 2rem; align-items: start;">
                
                <!-- COLUMN A: List of Applications -->
                <div class="card" style="margin: 0;">
                    <h3 style="font-size: 1.15rem; color: var(--primary-dark); margin-bottom: 1.25rem; display: flex; align-items: center; gap: 8px;">
                        <span class="material-symbols-outlined">list_alt</span>
                        Your Social Assistance Claims
                    </h3>

                    <?php if (empty($apps)): ?>
                        <div style="text-align: center; padding: 3rem 1.5rem; background: #f8fafc; border-radius: var(--radius-sm); border: 1px dashed var(--border);">
                            <span class="material-symbols-outlined" style="font-size: 40px; color: var(--text-muted); margin-bottom: 10px;">assignment_late</span>
                            <p style="color: var(--text-muted); font-size: 0.9rem;">You have no active or historical applications submitted.</p>
                            <a href="/aics_application_form.php" class="btn btn-primary" style="margin-top: 1.25rem; font-size: 0.8rem;">File AICS Application</a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Type of Assistance</th>
                                        <th>Date Filed</th>
                                        <th>Status</th>
                                        <th style="text-align: right;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($apps as $app): ?>
                                        <tr class="<?php echo $selected_app_id == $app['id'] ? 'active-row' : ''; ?>" style="<?php echo $selected_app_id == $app['id'] ? 'background-color: #eff6ff;' : ''; ?>">
                                            <td><strong>#<?php echo $app['id']; ?></strong></td>
                                            <td><?php echo htmlspecialchars($service_map[$app['service_id']] ?? 'General AICS'); ?></td>
                                            <td><?php echo date('M d, Y', strtotime($app['application_date'])); ?></td>
                                            <td>
                                                <span class="badge badge-<?php echo strtolower($app['status']); ?>">
                                                    <?php echo $app['status']; ?>
                                                </span>
                                            </td>
                                            <td style="text-align: right;">
                                                <a href="/client/my_applications.php?id=<?php echo $app['id']; ?>" class="btn btn-outline" style="padding: 4px 10px; font-size: 0.75rem; border-radius: 4px;">
                                                    Inspect
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- COLUMN B: Application Details (Visible ONLY if an app is selected) -->
                <?php if ($selected_app): ?>
                    <div class="card" style="margin: 0;">
                        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e2e8f0; padding-bottom: 1rem; margin-bottom: 1.5rem;">
                            <h3 style="font-size: 1.2rem; color: var(--primary-dark); margin: 0;">Application #<?php echo $selected_app['id']; ?> Details</h3>
                            <span class="badge badge-<?php echo strtolower($selected_app['status']); ?>" style="padding: 6px 12px; font-size: 0.8rem;">
                                <?php echo $selected_app['status']; ?>
                            </span>
                        </div>

                        <div style="display: flex; flex-direction: column; gap: 1rem; font-size: 0.9rem;">
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; background: #f8fafc; padding: 12px; border-radius: 6px;">
                                <div>
                                    <p style="font-size: 0.7rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700; margin: 0;">Type of Assistance</p>
                                    <p style="font-weight: 600; margin: 0; color: var(--primary-dark);"><?php echo htmlspecialchars($service_map[$selected_app['service_id']] ?? 'General AICS'); ?></p>
                                </div>
                                <div>
                                    <p style="font-size: 0.7rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700; margin: 0;">Date Submitted</p>
                                    <p style="font-weight: 600; margin: 0; color: var(--primary-dark);"><?php echo date('F d, Y', strtotime($selected_app['application_date'])); ?></p>
                                </div>
                            </div>

                            <div>
                                <h4 style="font-size: 0.85rem; text-transform: uppercase; color: var(--primary-dark); border-bottom: 1px solid #f1f5f9; padding-bottom: 4px; margin-bottom: 8px;">Findings / Problem Narrative</h4>
                                <p style="color: var(--text-muted); font-size: 0.85rem; line-height: 1.6; background-color: #fffbeb; padding: 12px; border-radius: 6px; border-left: 3px solid var(--accent); margin: 0; white-space: pre-line;">
                                    <?php echo htmlspecialchars($selected_app['findings'] ?? 'No narrative provided.'); ?>
                                </p>
                            </div>

                            <?php if (!empty($selected_app['recommendation'])): ?>
                                <div>
                                    <h4 style="font-size: 0.85rem; text-transform: uppercase; color: var(--success); border-bottom: 1px solid #f1f5f9; padding-bottom: 4px; margin-bottom: 8px;">Caseworker Recommendation</h4>
                                    <p style="color: #166534; font-size: 0.85rem; background-color: #f0fdf4; padding: 12px; border-radius: 6px; border-left: 3px solid var(--success); margin: 0;">
                                        <?php echo htmlspecialchars($selected_app['recommendation']); ?>
                                    </p>
                                    <p style="font-size: 0.75rem; color: var(--text-muted); margin-top: 6px;">Prepared by: <strong><?php echo htmlspecialchars($selected_app['prepared_by'] ?? 'Assigned Focal'); ?></strong></p>
                                </div>
                            <?php endif; ?>

                            <!-- Family composition block -->
                            <div style="margin-top: 1rem;">
                                <h4 style="font-size: 0.85rem; text-transform: uppercase; color: var(--primary-dark); border-bottom: 1px solid #f1f5f9; padding-bottom: 4px; margin-bottom: 8px;">Submitted Family Composition</h4>
                                <?php if (empty($selected_famcom)): ?>
                                    <p style="font-size: 0.8rem; color: var(--text-muted); italic">No family composition details filed.</p>
                                <?php else: ?>
                                    <div class="table-responsive" style="max-height: 250px;">
                                        <table style="font-size: 0.8rem;">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Age/Sex</th>
                                                    <th>Civil Status</th>
                                                    <th>Occupation</th>
                                                    <th>Income</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($selected_famcom as $member): ?>
                                                    <tr>
                                                        <td><strong><?php echo htmlspecialchars($member['first_name'] . ' ' . $member['last_name']); ?></strong></td>
                                                        <td><?php echo $member['age'] . ' / ' . $member['sex']; ?></td>
                                                        <td><?php echo $member['civil_status']; ?></td>
                                                        <td><?php echo htmlspecialchars($member['occupation'] ?? 'None'); ?></td>
                                                        <td><?php echo htmlspecialchars($member['income'] ?: '0'); ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div style="border-top: 1px solid #e2e8f0; padding-top: 1rem; margin-top: 1.5rem; text-align: right;">
                            <a href="/client/my_applications.php" class="btn btn-outline" style="font-size: 0.8rem; padding: 6px 16px;">Dismiss Inspection</a>
                        </div>
                    </div>
                <?php endif; ?>

            </div>
        </main>

        <?php include '../includes/footer.php'; ?>
    </div>
</body>
</html>
