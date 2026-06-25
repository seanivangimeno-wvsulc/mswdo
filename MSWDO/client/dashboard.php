<?php
require_once '../includes/auth_client.php';
require_once '../includes/supabase.php';

$client_id = $_SESSION['client_id'];
$client_name = $_SESSION['client_name'];

// Query count of client's applications
$filters = ["client_id=eq." . $client_id, "select=*"];
$apps = supabase_query('tb_aics_applications', 'GET', $filters);

$total_submitted = 0;
$total_pending = 0;
$total_approved = 0;
$total_rejected = 0;

$recent_apps = [];

if (is_array($apps) && !isset($apps['error'])) {
    $total_submitted = count($apps);
    foreach ($apps as $app) {
        if ($app['status'] == 'Pending') {
            $total_pending++;
        } elseif ($app['status'] == 'Approved') {
            $total_approved++;
        } elseif ($app['status'] == 'Rejected') {
            $total_rejected++;
        }
    }
    
    // Sort recent first
    usort($apps, function($a, $b) {
        return strcmp($b['created_at'], $a['created_at']);
    });
    
    // Take last 5
    $recent_apps = array_slice($apps, 0, 5);
}

// Map service IDs to names
$service_map = [
    1 => 'Medical Assistance',
    2 => 'Burial Assistance',
    3 => 'Educational Assistance',
    4 => 'Food Assistance',
    5 => 'Transportation Assistance'
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Dashboard - MSWDO Tubungan, Iloilo</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <div class="app-container">
        <?php include '../includes/navbar.php'; ?>

        <main class="main-content">
            <!-- Greet user banner -->
            <div style="background-color: var(--white); border-radius: var(--radius-md); padding: 1.75rem; border: 1px solid var(--border); box-shadow: var(--shadow); margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1.5rem;">
                <div>
                    <h2 style="font-size: 1.5rem; color: var(--primary-dark); margin: 0;">Welcome, <?php echo htmlspecialchars($client_name); ?>!</h2>
                    <p style="font-size: 0.85rem; color: var(--text-muted); margin-top: 4px;">Track your submitted applications, update your profile, or file new assistance claims from this portal.</p>
                </div>
                <div style="display: flex; gap: 12px;">
                    <a href="/aics_application_form.php" class="btn btn-primary">
                        <span class="material-symbols-outlined" style="font-size: 18px;">add</span> Apply for AICS
                    </a>
                    <a href="/client/profile.php" class="btn btn-outline">
                        <span class="material-symbols-outlined" style="font-size: 18px;">person</span> Edit Profile
                    </a>
                </div>
            </div>

            <!-- Stats grid -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
                <div class="card" style="margin: 0; text-align: center; border-left: 4px solid var(--primary-light);">
                    <p style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700; margin-bottom: 4px;">Total Submitted</p>
                    <div style="font-size: 1.75rem; font-weight: 800; color: var(--primary);"><?php echo $total_submitted; ?></div>
                </div>
                <div class="card" style="margin: 0; text-align: center; border-left: 4px solid var(--warning);">
                    <p style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700; margin-bottom: 4px;">Pending Review</p>
                    <div style="font-size: 1.75rem; font-weight: 800; color: var(--warning);"><?php echo $total_pending; ?></div>
                </div>
                <div class="card" style="margin: 0; text-align: center; border-left: 4px solid var(--success);">
                    <p style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700; margin-bottom: 4px;">Approved Claims</p>
                    <div style="font-size: 1.75rem; font-weight: 800; color: var(--success);"><?php echo $total_approved; ?></div>
                </div>
                <div class="card" style="margin: 0; text-align: center; border-left: 4px solid var(--danger);">
                    <p style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700; margin-bottom: 4px;">Disapproved Claims</p>
                    <div style="font-size: 1.75rem; font-weight: 800; color: var(--danger);"><?php echo $total_rejected; ?></div>
                </div>
            </div>

            <!-- Recent Applications -->
            <div class="card" style="padding: 1.75rem;">
                <h3 style="font-size: 1.15rem; color: var(--primary-dark); margin-bottom: 1.25rem; display: flex; align-items: center; gap: 8px;">
                    <span class="material-symbols-outlined" style="font-size: 20px;">assignment_late</span>
                    Recent AICS Claims (Last 5)
                </h3>

                <?php if (empty($recent_apps)): ?>
                    <div style="text-align: center; padding: 3rem 1.5rem; background: #f8fafc; border-radius: var(--radius-sm); border: 1px dashed var(--border);">
                        <span class="material-symbols-outlined" style="font-size: 40px; color: var(--text-muted); margin-bottom: 10px;">info</span>
                        <p style="font-size: 0.9rem; color: var(--text-muted); margin: 0;">You have not submitted any social assistance claims yet.</p>
                        <a href="/aics_application_form.php" class="btn btn-primary" style="margin-top: 1rem; font-size: 0.8rem; padding: 8px 20px;">Submit First Application</a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>App ID</th>
                                    <th>Assistance Type</th>
                                    <th>Application Date</th>
                                    <th>Status</th>
                                    <th style="text-align: right;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recent_apps as $app): ?>
                                    <tr>
                                        <td><strong>#<?php echo $app['id']; ?></strong></td>
                                        <td><?php echo htmlspecialchars($service_map[$app['service_id']] ?? 'General AICS'); ?></td>
                                        <td><?php echo date('M d, Y', strtotime($app['application_date'])); ?></td>
                                        <td>
                                            <span class="badge badge-<?php echo strtolower($app['status']); ?>">
                                                <?php echo $app['status']; ?>
                                            </span>
                                        </td>
                                        <td style="text-align: right;">
                                            <a href="/client/my_applications.php?id=<?php echo $app['id']; ?>" class="btn btn-outline" style="padding: 4px 10px; font-size: 0.75rem; border-radius: 4px;">View Full Details</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </main>

        <?php include '../includes/footer.php'; ?>
    </div>
</body>
</html>
