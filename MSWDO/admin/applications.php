<?php
require_once '../includes/auth_admin.php';
require_once '../includes/supabase.php';

// Fetch all applications
$apps_response = supabase_query('tb_aics_applications', 'GET', ["select=*"]);
$applications = is_array($apps_response) && !isset($apps_response['error']) ? $apps_response : [];

// Sort by date (recent first)
usort($applications, function($a, $b) {
    return strcmp($b['created_at'], $a['created_at']);
});

// Fetch all clients to map IDs to Names
$clients_response = supabase_query('tb_clients', 'GET', ["select=id,first_name,last_name,email,contact_number,address"]);
$client_map = [];
if (is_array($clients_response) && !isset($clients_response['error'])) {
    foreach ($clients_response as $c) {
        $client_map[$c['id']] = $c;
    }
}

$service_map = [
    1 => 'Medical Assistance',
    2 => 'Burial Assistance',
    3 => 'Educational Assistance',
    4 => 'Food Assistance',
    5 => 'Transportation Assistance'
];

// If ?id=X is specified, fetch that specific application's family members
$view_app_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$view_app = null;
$view_famcom = [];

if ($view_app_id > 0) {
    foreach ($applications as $app) {
        if ($app['id'] == $view_app_id) {
            $view_app = $app;
            break;
        }
    }
    
    if ($view_app) {
        // Fetch family composition
        $fam_filters = ["application_id=eq." . $view_app_id, "select=*"];
        $fam_response = supabase_query('tb_aics_famcom', 'GET', $fam_filters);
        if (is_array($fam_response) && !isset($fam_response['error'])) {
            $view_famcom = $fam_response;
        }
    }
}

// Support search and status filter
$search_query = isset($_GET['search']) ? trim($_GET['search']) : '';
$status_filter = isset($_GET['status']) ? trim($_GET['status']) : '';

// Filter list
$filtered_apps = [];
foreach ($applications as $app) {
    $client = $client_map[$app['client_id']] ?? null;
    $client_name = $client ? ($client['first_name'] . ' ' . $client['last_name']) : '';
    
    // Status filter
    if ($status_filter !== '' && strtolower($app['status']) !== strtolower($status_filter)) {
        continue;
    }
    
    // Search query
    if ($search_query !== '') {
        $match_name = stripos($client_name, $search_query) !== false;
        $match_id = $app['id'] == $search_query;
        if (!$match_name && !$match_id) {
            continue;
        }
    }
    
    $filtered_apps[] = $app;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Applications - MSWDO Portal</title>
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
                        <h2 style="font-size: 1.5rem; color: var(--primary-dark); margin: 0;">AICS Intake Applications</h2>
                        <p style="font-size: 0.8rem; color: var(--text-muted); margin-top: 2px;">Review, update, and sign off municipal social assistance claims.</p>
                    </div>
                </div>

                <!-- Filters & Search Toolbar -->
                <div class="card" style="padding: 1.25rem; margin-bottom: 1.5rem;">
                    <form action="/admin/applications.php" method="GET" style="display: flex; gap: 12px; flex-wrap: wrap; align-items: flex-end;">
                        <div class="form-group" style="margin: 0; flex: 1; min-width: 250px;">
                            <label for="search" style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase;">Search by Applicant Name or App ID</label>
                            <input type="text" name="search" id="search" class="form-control" placeholder="E.g., Juan Dela Cruz" value="<?php echo htmlspecialchars($search_query); ?>">
                        </div>
                        
                        <div class="form-group" style="margin: 0; width: 180px;">
                            <label for="status" style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase;">Status Filter</label>
                            <select name="status" id="status" class="form-control">
                                <option value="">Show All Statuses</option>
                                <option value="Pending" <?php echo $status_filter == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                <option value="Approved" <?php echo $status_filter == 'Approved' ? 'selected' : ''; ?>>Approved</option>
                                <option value="Rejected" <?php echo $status_filter == 'Rejected' ? 'selected' : ''; ?>>Rejected</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary" style="padding: 10px 20px;">
                            <span class="material-symbols-outlined">search</span> Search
                        </button>
                        
                        <?php if ($search_query !== '' || $status_filter !== ''): ?>
                            <a href="/admin/applications.php" class="btn btn-outline" style="padding: 10px 16px;">Clear</a>
                        <?php endif; ?>
                    </form>
                </div>

                <!-- Applications Table -->
                <div class="card" style="padding: 0;">
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>App ID</th>
                                    <th>Applicant Name</th>
                                    <th>Filing Date</th>
                                    <th>Assistance Type</th>
                                    <th>Status</th>
                                    <th style="text-align: right; padding-right: 24px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($filtered_apps)): ?>
                                    <tr>
                                        <td colspan="6" style="text-align: center; padding: 3rem 1.5rem; color: var(--text-muted);">
                                            No matching social welfare applications found in system registry.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($filtered_apps as $app): ?>
                                        <?php 
                                            $client = $client_map[$app['client_id']] ?? null;
                                            $applicant_name = $client ? ($client['first_name'] . ' ' . $client['last_name']) : 'Unknown Client';
                                        ?>
                                        <tr>
                                            <td><strong>#<?php echo $app['id']; ?></strong></td>
                                            <td>
                                                <div><strong><?php echo htmlspecialchars($applicant_name); ?></strong></div>
                                                <div style="font-size: 0.7rem; color: var(--text-muted);"><?php echo htmlspecialchars($client['contact_number'] ?? ''); ?></div>
                                            </td>
                                            <td><?php echo date('F d, Y', strtotime($app['application_date'])); ?></td>
                                            <td>
                                                <span style="font-weight: 500; color: var(--primary-dark);"><?php echo htmlspecialchars($service_map[$app['service_id']] ?? 'General AICS'); ?></span>
                                            </td>
                                            <td>
                                                <span class="badge badge-<?php echo strtolower($app['status']); ?>">
                                                    <?php echo $app['status']; ?>
                                                </span>
                                            </td>
                                            <td style="text-align: right; padding-right: 24px;">
                                                <a href="/admin/applications.php?id=<?php echo $app['id']; ?>&search=<?php echo urlencode($search_query); ?>&status=<?php echo urlencode($status_filter); ?>" class="btn btn-primary" style="padding: 6px 12px; font-size: 0.8rem; border-radius: 4px;">
                                                    <span class="material-symbols-outlined" style="font-size: 16px;">rate_review</span> Review
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Detailed Inspection Modal (Loads if ?id=X is present) -->
                <?php if ($view_app): ?>
                    <?php 
                        $client = $client_map[$view_app['client_id']] ?? null;
                        $applicant_name = $client ? ($client['first_name'] . ' ' . $client['last_name']) : 'Unknown Client';
                        
                        // Parse signature and file
                        $signature_str = '';
                        $filename_str = '';
                        if (!empty($view_app['others'])) {
                            $parts = explode('|file:', $view_app['others']);
                            $signature_str = $parts[0];
                            if (isset($parts[1])) {
                                $filename_str = $parts[1];
                            }
                        }
                    ?>
                    <div class="modal active" id="review-modal">
                        <div class="modal-content" style="max-width: 700px;">
                            <div class="modal-header">
                                <h3 class="modal-title">Review AICS Case: #<?php echo $view_app['id']; ?></h3>
                                <a href="/admin/applications.php?search=<?php echo urlencode($search_query); ?>&status=<?php echo urlencode($status_filter); ?>" class="modal-close" style="text-decoration: none;">&times;</a>
                            </div>
                            
                            <form action="/php/actions/update_app_status.php" method="POST">
                                <input type="hidden" name="application_id" value="<?php echo $view_app['id']; ?>">
                                <input type="hidden" name="search" value="<?php echo htmlspecialchars($search_query); ?>">
                                <input type="hidden" name="status_filter" value="<?php echo htmlspecialchars($status_filter); ?>">

                                <div class="modal-body" style="display: flex; flex-direction: column; gap: 1.25rem;">
                                    
                                    <!-- Applicant bio -->
                                    <div style="background-color: #f8fafc; border-radius: 6px; padding: 1rem; border: 1px solid var(--border); display: grid; grid-template-columns: 1fr 1fr; gap: 10px; font-size: 0.85rem;">
                                        <div>
                                            <p style="color: var(--text-muted); font-size: 0.7rem; text-transform: uppercase; margin: 0;">Applicant Name</p>
                                            <p style="font-weight: 700; color: var(--primary-dark); margin: 0;"><?php echo htmlspecialchars($applicant_name); ?></p>
                                        </div>
                                        <div>
                                            <p style="color: var(--text-muted); font-size: 0.7rem; text-transform: uppercase; margin: 0;">Contact Details</p>
                                            <p style="font-weight: 700; color: var(--primary-dark); margin: 0;"><?php echo htmlspecialchars($client['contact_number'] ?? 'None'); ?></p>
                                        </div>
                                        <div style="grid-column: span 2; margin-top: 4px; border-top: 1px solid #e2e8f0; padding-top: 4px;">
                                            <p style="color: var(--text-muted); font-size: 0.7rem; text-transform: uppercase; margin: 0;">Permanent Address</p>
                                            <p style="font-weight: 600; color: var(--text); margin: 0;"><?php echo htmlspecialchars($client['address'] ?? 'Tubungan, Iloilo'); ?></p>
                                        </div>
                                    </div>

                                    <div>
                                        <h4 style="font-size: 0.8rem; text-transform: uppercase; color: var(--primary-dark); border-bottom: 1px solid #f1f5f9; padding-bottom: 4px; margin-bottom: 6px;">Requested Service</h4>
                                        <p style="font-weight: 700; color: var(--primary); margin: 0; font-size: 0.95rem;"><?php echo htmlspecialchars($service_map[$view_app['service_id']] ?? 'General AICS'); ?></p>
                                    </div>

                                    <div>
                                        <h4 style="font-size: 0.8rem; text-transform: uppercase; color: var(--primary-dark); border-bottom: 1px solid #f1f5f9; padding-bottom: 4px; margin-bottom: 6px;">Findings & Circumstances</h4>
                                        <p style="font-size: 0.85rem; color: var(--text-muted); line-height: 1.5; background-color: #fffbeb; padding: 10px; border-radius: 4px; border-left: 3px solid var(--accent); margin: 0; white-space: pre-line;">
                                            <?php echo htmlspecialchars($view_app['findings'] ?: 'None declared.'); ?>
                                        </p>
                                    </div>

                                    <!-- Family composition list -->
                                    <div>
                                        <h4 style="font-size: 0.8rem; text-transform: uppercase; color: var(--primary-dark); border-bottom: 1px solid #f1f5f9; padding-bottom: 4px; margin-bottom: 8px;">Household Composition</h4>
                                        <?php if (empty($view_famcom)): ?>
                                            <p style="font-size: 0.8rem; color: var(--text-muted); font-style: italic;">No household members listed in application database.</p>
                                        <?php else: ?>
                                            <div class="table-responsive" style="max-height: 160px; overflow-y: auto;">
                                                <table style="font-size: 0.75rem;">
                                                    <thead>
                                                        <tr>
                                                            <th>Name</th>
                                                            <th>Age/Sex</th>
                                                            <th>Status</th>
                                                            <th>Occupation</th>
                                                            <th>Monthly Income</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($view_famcom as $fam): ?>
                                                            <tr>
                                                                <td><strong><?php echo htmlspecialchars($fam['first_name'] . ' ' . $fam['last_name']); ?></strong></td>
                                                                <td><?php echo $fam['age'] . ' / ' . $fam['sex']; ?></td>
                                                                <td><?php echo $fam['civil_status']; ?></td>
                                                                <td><?php echo htmlspecialchars($fam['occupation'] ?? 'None'); ?></td>
                                                                <td><?php echo htmlspecialchars($fam['income'] ?: '0'); ?></td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                                        <!-- Digital signature display -->
                                        <div>
                                            <h4 style="font-size: 0.8rem; text-transform: uppercase; color: var(--primary-dark); border-bottom: 1px solid #f1f5f9; padding-bottom: 4px; margin-bottom: 4px;">Digital Signature</h4>
                                            <p style="font-family: monospace; font-size: 1rem; font-style: italic; color: var(--primary); font-weight: 600; margin: 4px 0 0;">/s/ <?php echo htmlspecialchars($signature_str ?: $applicant_name); ?></p>
                                        </div>

                                        <!-- Attachment display -->
                                        <div>
                                            <h4 style="font-size: 0.8rem; text-transform: uppercase; color: var(--primary-dark); border-bottom: 1px solid #f1f5f9; padding-bottom: 4px; margin-bottom: 4px;">Uploaded Attachment</h4>
                                            <?php if ($filename_str): ?>
                                                <a href="/uploads/<?php echo htmlspecialchars($filename_str); ?>" target="_blank" class="btn btn-outline" style="padding: 4px 10px; font-size: 0.75rem; border-radius: 4px; display: inline-flex; align-items: center; gap: 4px; margin-top: 4px;">
                                                    <span class="material-symbols-outlined" style="font-size: 14px;">visibility</span> Inspect File Attachment
                                                </a>
                                            <?php else: ?>
                                                <p style="font-size: 0.8rem; color: var(--text-muted); font-style: italic; margin-top: 4px;">No files uploaded.</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <!-- Decision fields -->
                                    <div style="border-top: 1px solid #e2e8f0; padding-top: 1rem; display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                                        <div class="form-group" style="margin: 0;">
                                            <label for="status-decision"><strong>Intake Action / Decision</strong></label>
                                            <select name="status" id="status-decision" class="form-control" required style="background: #fffbeb; border-color: var(--accent); font-weight: 600;">
                                                <option value="Pending" <?php echo $view_app['status'] == 'Pending' ? 'selected' : ''; ?>>Keep Pending / In-Review</option>
                                                <option value="Approved" <?php echo $view_app['status'] == 'Approved' ? 'selected' : ''; ?>>Approve Claims</option>
                                                <option value="Rejected" <?php echo $view_app['status'] == 'Rejected' ? 'selected' : ''; ?>>Disapprove / Reject Claims</option>
                                            </select>
                                        </div>
                                        <div class="form-group" style="margin: 0;">
                                            <label for="prepared_by"><strong>Officer-In-Charge Signature</strong></label>
                                            <input type="text" name="prepared_by" id="prepared_by" class="form-control" value="<?php echo htmlspecialchars($view_app['prepared_by'] ?: $_SESSION['admin_name']); ?>" required>
                                        </div>
                                    </div>

                                    <div class="form-group" style="margin: 0;">
                                        <label for="recommendation"><strong>Caseworker Action Remarks / Recommendation details</strong></label>
                                        <textarea name="recommendation" id="recommendation" class="form-control" rows="3" placeholder="Provide final recommendation summary..." required><?php echo htmlspecialchars($view_app['recommendation'] ?? ''); ?></textarea>
                                    </div>

                                </div>
                                <div class="modal-footer">
                                    <a href="/admin/applications.php?search=<?php echo urlencode($search_query); ?>&status=<?php echo urlencode($status_filter); ?>" class="btn btn-outline">Close Reviewer</a>
                                    <button type="submit" class="btn btn-primary">
                                        <span class="material-symbols-outlined" style="font-size: 16px;">save</span> Save Decision & Update
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php endif; ?>

            </main>
        </div>
    </div>
</body>
</html>
