<?php
require_once '../includes/auth_admin.php';
require_once '../includes/supabase.php';

// Fetch stats counts
$clients = supabase_query('tb_clients', 'GET', ["select=id"]);
$total_clients = is_array($clients) && !isset($clients['error']) ? count($clients) : 0;

$applications = supabase_query('tb_aics_applications', 'GET', ["select=*"]);
$total_applications = is_array($applications) && !isset($applications['error']) ? count($applications) : 0;

$pending_applications = 0;
$monthly_stats = array_fill(1, 12, 0); // Jan to Dec
$recent_applications = [];

if (is_array($applications) && !isset($applications['error'])) {
    foreach ($applications as $app) {
        if ($app['status'] == 'Pending') {
            $pending_applications++;
        }
        // Extract month of application
        $month = intval(date('n', strtotime($app['application_date'])));
        $monthly_stats[$month]++;
    }
    
    // Sort recent first
    usort($applications, function($a, $b) {
        return strcmp($b['created_at'], $a['created_at']);
    });
    $recent_applications = array_slice($applications, 0, 5);
}

$beneficiaries = supabase_query('tb_beneficiaries', 'GET', ["select=id"]);
$total_beneficiaries = is_array($beneficiaries) && !isset($beneficiaries['error']) ? count($beneficiaries) : 0;

$programs = supabase_query('tb_program', 'GET', ["select=*"]);
$active_programs = 0;
$total_budget = 0;
if (is_array($programs) && !isset($programs['error'])) {
    foreach ($programs as $prog) {
        if ($prog['status'] == 'Active') {
            $active_programs++;
            $total_budget += floatval($prog['budget']);
        }
    }
}

// Client mapping helper for table display
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

// Compile chart monthly labels for the last 6 months
$months_label = [];
$months_data = [];
for ($i = 5; $i >= 0; $i--) {
    $m = intval(date('n', strtotime("-$i months")));
    $months_label[] = date('M', strtotime("-$i months"));
    $months_data[] = $monthly_stats[$m];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - MSWDO Portal</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/admin.css">
</head>
<body>
    <div class="app-container">
        <!-- Main Admin Layout Wrapper -->
        <div class="admin-layout">
            
            <!-- Left Sidebar Navigation -->
            <?php include '../includes/admin_sidebar.php'; ?>

            <!-- Right Panel Content -->
            <main class="admin-panel">
                <div class="admin-header">
                    <div>
                        <h2 style="font-size: 1.5rem; color: var(--primary-dark); margin: 0;">MSWDO Executive Summary</h2>
                        <p style="font-size: 0.8rem; color: var(--text-muted); margin-top: 2px;">Municipal Social Welfare & Development Office • Tubungan, Iloilo</p>
                    </div>
                    <div style="font-size: 0.8rem; text-align: right; color: var(--text-muted);">
                        <strong>System Date:</strong> <?php echo date('F d, Y'); ?><br>
                        <strong>Status:</strong> <span style="color: var(--success); font-weight: 700;">● Admin Node Online</span>
                    </div>
                </div>

                <!-- Admin Stats Grid -->
                <div class="admin-stats-grid">
                    <div class="admin-stat-card">
                        <div class="admin-stat-icon" style="background-color: #eff6ff; color: var(--primary);">
                            <span class="material-symbols-outlined">group</span>
                        </div>
                        <div class="admin-stat-info">
                            <p>Total Clients</p>
                            <h3><?php echo $total_clients; ?></h3>
                        </div>
                    </div>

                    <div class="admin-stat-card">
                        <div class="admin-stat-icon" style="background-color: #fffbeb; color: var(--warning);">
                            <span class="material-symbols-outlined">assignment</span>
                        </div>
                        <div class="admin-stat-info">
                            <p>AICS Claims</p>
                            <h3><?php echo $total_applications; ?></h3>
                        </div>
                    </div>

                    <div class="admin-stat-card">
                        <div class="admin-stat-icon" style="background-color: #fef2f2; color: var(--danger);">
                            <span class="material-symbols-outlined">assignment_late</span>
                        </div>
                        <div class="admin-stat-info">
                            <p>Pending Claims</p>
                            <h3><?php echo $pending_applications; ?></h3>
                        </div>
                    </div>

                    <div class="admin-stat-card">
                        <div class="admin-stat-icon" style="background-color: #f0fdf4; color: var(--success);">
                            <span class="material-symbols-outlined">contacts</span>
                        </div>
                        <div class="admin-stat-info">
                            <p>Beneficiaries</p>
                            <h3><?php echo $total_beneficiaries; ?></h3>
                        </div>
                    </div>

                    <div class="admin-stat-card">
                        <div class="admin-stat-icon" style="background-color: #faf5ff; color: #8b5cf6;">
                            <span class="material-symbols-outlined">account_balance_wallet</span>
                        </div>
                        <div class="admin-stat-info">
                            <p>Total Budget</p>
                            <h3>₱<?php echo number_format($total_budget); ?></h3>
                        </div>
                    </div>
                </div>

                <!-- Grid layout for Chart and Recent Activity -->
                <div style="display: grid; grid-template-columns: 5fr 4fr; gap: 1.5rem; margin-bottom: 2rem; align-items: start;">
                    
                    <!-- HTML5 Chart Canvas -->
                    <div class="chart-container" style="margin: 0;">
                        <h4 style="font-size: 0.9rem; text-transform: uppercase; color: var(--primary-dark); margin-bottom: 1.25rem; border-bottom: 1px solid #f1f5f9; padding-bottom: 0.5rem;">Applications Intake Trends (Last 6 Months)</h4>
                        <div style="position: relative; height: 260px; width: 100%;">
                            <canvas id="trendsChart" style="display: block; width: 100%; height: 240px;"></canvas>
                        </div>
                    </div>

                    <!-- Side quick navigation / links -->
                    <div class="card" style="margin: 0; padding: 1.5rem;">
                        <h4 style="font-size: 0.9rem; text-transform: uppercase; color: var(--primary-dark); margin-bottom: 1rem; border-bottom: 1px solid #f1f5f9; padding-bottom: 0.5rem;">Social Protection Services</h4>
                        <div style="display: flex; flex-direction: column; gap: 12px;">
                            <a href="/admin/applications.php" class="btn btn-outline" style="justify-content: flex-start; text-align: left;">
                                <span class="material-symbols-outlined" style="color: var(--primary);">checklist</span> Manage Claims & Approvals
                            </a>
                            <a href="/admin/clients.php" class="btn btn-outline" style="justify-content: flex-start; text-align: left;">
                                <span class="material-symbols-outlined" style="color: var(--primary);">manage_accounts</span> Manage Client Directory
                            </a>
                            <a href="/admin/programs.php" class="btn btn-outline" style="justify-content: flex-start; text-align: left;">
                                <span class="material-symbols-outlined" style="color: var(--primary);">payments</span> Allocate Program Budgets
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Recent Applications Activity Table -->
                <div class="card" style="padding: 1.75rem; margin: 0;">
                    <h3 style="font-size: 1.15rem; color: var(--primary-dark); margin-bottom: 1.25rem; display: flex; align-items: center; gap: 8px;">
                        <span class="material-symbols-outlined">history</span>
                        Recent Applications Feed
                    </h3>
                    
                    <?php if (empty($recent_applications)): ?>
                        <div style="text-align: center; padding: 2.5rem; background: #f8fafc; border-radius: var(--radius-sm); border: 1px dashed var(--border);">
                            <p style="color: var(--text-muted); margin: 0;">No application records found.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th>App ID</th>
                                        <th>Applicant Name</th>
                                        <th>Assistance Type</th>
                                        <th>Filing Date</th>
                                        <th>Status Badge</th>
                                        <th style="text-align: right;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recent_applications as $app): ?>
                                        <tr>
                                            <td><strong>#<?php echo $app['id']; ?></strong></td>
                                            <td><?php echo htmlspecialchars($client_map[$app['client_id']] ?? 'Guest client'); ?></td>
                                            <td><?php echo htmlspecialchars($service_map[$app['service_id']] ?? 'General AICS'); ?></td>
                                            <td><?php echo date('M d, Y', strtotime($app['application_date'])); ?></td>
                                            <td>
                                                <span class="badge badge-<?php echo strtolower($app['status']); ?>">
                                                    <?php echo $app['status']; ?>
                                                </span>
                                            </td>
                                            <td style="text-align: right;">
                                                <a href="/admin/applications.php?id=<?php echo $app['id']; ?>" class="btn btn-primary" style="padding: 4px 10px; font-size: 0.75rem; border-radius: 4px;">Review Claim</a>
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

    <!-- Chart rendering logic on standard HTML5 canvas -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const canvas = document.getElementById('trendsChart');
            if (!canvas) return;
            const ctx = canvas.getContext('2d');
            
            // Set canvas height/width based on scale
            canvas.width = canvas.parentElement.clientWidth;
            canvas.height = 240;

            const labels = <?php echo json_encode($months_label); ?>;
            const data = <?php echo json_encode($months_data); ?>;

            // Draw a very neat high contrast bar chart manually
            const paddingLeft = 40;
            const paddingRight = 20;
            const paddingTop = 20;
            const paddingBottom = 40;
            
            const chartWidth = canvas.width - paddingLeft - paddingRight;
            const chartHeight = canvas.height - paddingTop - paddingBottom;

            // Draw axes
            ctx.strokeStyle = '#cbd5e1';
            ctx.lineWidth = 1;
            ctx.beginPath();
            ctx.moveTo(paddingLeft, paddingTop);
            ctx.lineTo(paddingLeft, canvas.height - paddingBottom);
            ctx.lineTo(canvas.width - paddingRight, canvas.height - paddingBottom);
            ctx.stroke();

            // Find max value in data for scaling
            const maxVal = Math.max(...data, 5); // Fallback to at least 5 for grid scaling
            
            // Draw horizontal reference lines & Y-axis labels
            ctx.fillStyle = '#64748b';
            ctx.font = '10px Poppins, sans-serif';
            ctx.textAlign = 'right';
            ctx.textBaseline = 'middle';
            
            const numLines = 4;
            for (let i = 0; i <= numLines; i++) {
                const yVal = Math.round((maxVal / numLines) * i);
                const yPos = canvas.height - paddingBottom - (chartHeight / numLines) * i;
                
                // Text label
                ctx.fillText(yVal, paddingLeft - 8, yPos);
                
                // Grid line
                if (i > 0) {
                    ctx.strokeStyle = '#f1f5f9';
                    ctx.beginPath();
                    ctx.moveTo(paddingLeft, yPos);
                    ctx.lineTo(canvas.width - paddingRight, yPos);
                    ctx.stroke();
                }
            }

            // Draw bars and X-axis labels
            const numBars = labels.length;
            const barSpacing = chartWidth / numBars;
            const barWidth = barSpacing * 0.5;

            ctx.textAlign = 'center';
            ctx.textBaseline = 'top';

            labels.forEach((label, idx) => {
                const val = data[idx];
                const barHeight = (chartHeight / maxVal) * val;
                const xPos = paddingLeft + (barSpacing * idx) + (barSpacing - barWidth) / 2;
                const yPos = canvas.height - paddingBottom - barHeight;

                // Draw Bar with custom color
                ctx.fillStyle = '#1a4b8c'; // Deep blue
                ctx.beginPath();
                ctx.roundRect(xPos, yPos, barWidth, barHeight, [4, 4, 0, 0]);
                ctx.fill();

                // Draw Value above Bar
                if (val > 0) {
                    ctx.fillStyle = '#1e293b';
                    ctx.fillText(val, xPos + barWidth / 2, yPos - 12);
                }

                // Draw X-axis Label
                ctx.fillStyle = '#64748b';
                ctx.fillText(label, xPos + barWidth / 2, canvas.height - paddingBottom + 8);
            });
        });
    </script>
</body>
</html>
