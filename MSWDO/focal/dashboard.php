<?php
require_once '../includes/auth_focal.php';
require_once '../includes/supabase.php';

$focal_program_id = $_SESSION['focal_program_id'];

// 1. Fetch assigned Program details
$prog_filt = ["id=eq." . $focal_program_id, "select=*"];
$prog_res = supabase_query('tb_program', 'GET', $prog_filt);
$assigned_program = (!empty($prog_res) && is_array($prog_res) && !isset($prog_res['error'])) ? $prog_res[0] : null;

// 2. Fetch specific Beneficiary count for this program
$ben_filt = ["program_id=eq." . $focal_program_id, "select=id"];
$ben_res = supabase_query('tb_beneficiaries', 'GET', $ben_filt);
$total_beneficiaries = is_array($ben_res) && !isset($ben_res['error']) ? count($ben_res) : 0;

// 3. Fetch applications (specifically for their desk - e.g. AICS is program_id=1, so services mapping to AICS)
$apps_filt = ["select=*"];
$apps_res = supabase_query('tb_aics_applications', 'GET', $apps_filt);

$pending_applications = 0;
$total_applications = 0;
$recent_applications = [];

// Client names mapping
$client_map = [];
$client_details = supabase_query('tb_clients', 'GET', ["select=id,first_name,last_name"]);
if (is_array($client_details) && !isset($client_details['error'])) {
    foreach ($client_details as $c) {
        $client_map[$c['id']] = $c['first_name'] . ' ' . $c['last_name'];
    }
}

$service_map = [
    1 => 'Medical Assistance',
    2 => 'Burial Assistance',
    3 => 'Educational Assistance',
    4 => 'Food Assistance',
    5 => 'Transportation Assistance'
];

if (is_array($apps_res) && !isset($apps_res['error'])) {
    // If they are managing program ID 1 (AICS), then all claims belong to them!
    if ($focal_program_id == 1) {
        foreach ($apps_res as $app) {
            $total_applications++;
            if ($app['status'] == 'Pending') {
                $pending_applications++;
            }
            $recent_applications[] = $app;
        }
    }
    
    // Sort recent first
    usort($recent_applications, function($a, $b) {
        return strcmp($b['created_at'], $a['created_at']);
    });
    $recent_applications = array_slice($recent_applications, 0, 5);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Desk Dashboard - Focal Person Console</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/admin.css">
</head>
<body>
    <div class="app-container">
        <div class="admin-layout">
            <?php include '../includes/focal_sidebar.php'; ?>

            <main class="admin-panel">
                <div class="admin-header">
                    <div>
                        <h2 style="font-size: 1.5rem; color: var(--primary-dark); margin: 0;">Social Desk Dashboard</h2>
                        <p style="font-size: 0.8rem; color: var(--text-muted); margin-top: 2px;">Managing Office: <strong><?php echo htmlspecialchars($assigned_program['program_name'] ?? 'Assigned Welfare Desk'); ?></strong></p>
                    </div>
                    <div style="font-size: 0.8rem; text-align: right; color: var(--text-muted);">
                        <strong>Welfare Node:</strong> Active<br>
                        <strong>Officer:</strong> <?php echo htmlspecialchars($_SESSION['focal_name']); ?>
                    </div>
                </div>

                <!-- Stats summary card for Focal program -->
                <div class="admin-stats-grid">
                    <div class="admin-stat-card" style="border-left-color: var(--primary);">
                        <div class="admin-stat-icon" style="background-color: #eff6ff; color: var(--primary);">
                            <span class="material-symbols-outlined">contacts</span>
                        </div>
                        <div class="admin-stat-info">
                            <p>Active Beneficiaries</p>
                            <h3><?php echo $total_beneficiaries; ?></h3>
                        </div>
                    </div>

                    <div class="admin-stat-card" style="border-left-color: var(--warning);">
                        <div class="admin-stat-icon" style="background-color: #fffbeb; color: var(--warning);">
                            <span class="material-symbols-outlined">assignment</span>
                        </div>
                        <div class="admin-stat-info">
                            <p>Desk Claims (All)</p>
                            <h3><?php echo $total_applications; ?></h3>
                        </div>
                    </div>

                    <div class="admin-stat-card" style="border-left-color: var(--danger);">
                        <div class="admin-stat-icon" style="background-color: #fef2f2; color: var(--danger);">
                            <span class="material-symbols-outlined">assignment_late</span>
                        </div>
                        <div class="admin-stat-info">
                            <p>Claims Pending Review</p>
                            <h3><?php echo $pending_applications; ?></h3>
                        </div>
                    </div>

                    <div class="admin-stat-card" style="border-left-color: var(--success);">
                        <div class="admin-stat-icon" style="background-color: #f0fdf4; color: var(--success);">
                            <span class="material-symbols-outlined">account_balance_wallet</span>
                        </div>
                        <div class="admin-stat-info">
                            <p>Program Balance Allocation</p>
                            <h3>₱<?php echo number_format($assigned_program['budget'] ?? 0, 2); ?></h3>
                        </div>
                    </div>
                </div>

                <!-- Welfare Desk info card -->
                <div class="card" style="padding: 1.5rem; margin-bottom: 2rem;">
                    <div style="display: flex; gap: 12px; align-items: flex-start;">
                        <span class="material-symbols-outlined" style="font-size: 32px; color: var(--primary);">info</span>
                        <div>
                            <h4 style="font-size: 1rem; color: var(--primary-dark); margin: 0 0 4px;">Desk Operation Memo</h4>
                            <p style="font-size: 0.85rem; color: var(--text-muted); line-height: 1.5; margin: 0;">You are logged in as the official focal representative of <strong><?php echo htmlspecialchars($assigned_program['program_name'] ?? ''); ?></strong>. You have administrative permissions to review and approve/disapprove AICS intake applications submitted online by residents of Tubungan under your program's scope.</p>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity Feed specifically for this Desk -->
                <div class="card" style="padding: 1.75rem; margin: 0;">
                    <h3 style="font-size: 1.1rem; color: var(--primary-dark); margin-bottom: 1.25rem; display: flex; align-items: center; gap: 8px;">
                        <span class="material-symbols-outlined">history</span>
                        Desk Activity Feed
                    </h3>

                    <?php if (empty($recent_applications)): ?>
                        <div style="text-align: center; padding: 2.5rem; background: #f8fafc; border-radius: var(--radius-sm); border: 1px dashed var(--border);">
                            <p style="color: var(--text-muted); margin: 0;">No active case applications registered for your desk.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th>App ID</th>
                                        <th>Applicant Name</th>
                                        <th>Service Category</th>
                                        <th>Submitting Date</th>
                                        <th>Current Status</th>
                                        <th style="text-align: right;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recent_applications as $app): ?>
                                        <tr>
                                            <td><strong>#<?php echo $app['id']; ?></strong></td>
                                            <td><strong><?php echo htmlspecialchars($client_map[$app['client_id']] ?? 'Guest client'); ?></strong></td>
                                            <td><?php echo htmlspecialchars($service_map[$app['service_id']] ?? 'General AICS'); ?></td>
                                            <td><?php echo date('M d, Y', strtotime($app['application_date'])); ?></td>
                                            <td>
                                                <span class="badge badge-<?php echo strtolower($app['status']); ?>">
                                                    <?php echo $app['status']; ?>
                                                </span>
                                            </td>
                                            <td style="text-align: right;">
                                                <a href="/focal/applications.php?id=<?php echo $app['id']; ?>" class="btn btn-primary" style="padding: 4px 10px; font-size: 0.75rem; border-radius: 4px;">Review Claim</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>

            </main>
        </div>
    </div>
</body>
</html>
