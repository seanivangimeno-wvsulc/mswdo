<?php
require_once '../includes/auth_admin.php';
require_once '../includes/supabase.php';

// Fetch all registered clients
$clients_response = supabase_query('tb_clients', 'GET', ["select=*"]);
$clients = is_array($clients_response) && !isset($clients_response['error']) ? $clients_response : [];

// Support simple search filter
$search_query = isset($_GET['search']) ? trim($_GET['search']) : '';
$filtered_clients = [];

foreach ($clients as $c) {
    if ($search_query !== '') {
        $fullname = $c['first_name'] . ' ' . $c['last_name'];
        if (stripos($fullname, $search_query) === false && stripos($c['email'], $search_query) === false) {
            continue;
        }
    }
    $filtered_clients[] = $c;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Directory - MSWDO Portal</title>
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
                        <h2 style="font-size: 1.5rem; color: var(--primary-dark); margin: 0;">Registered Clients Directory</h2>
                        <p style="font-size: 0.8rem; color: var(--text-muted); margin-top: 2px;">View all citizens registered in the municipal client portal.</p>
                    </div>
                </div>

                <!-- Search box -->
                <div class="card" style="padding: 1.25rem; margin-bottom: 1.5rem;">
                    <form action="/admin/clients.php" method="GET" style="display: flex; gap: 12px; align-items: flex-end;">
                        <div class="form-group" style="margin: 0; flex: 1;">
                            <label for="search" style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase;">Search by client name or email</label>
                            <input type="text" name="search" id="search" class="form-control" placeholder="E.g., Pedro Penduko" value="<?php echo htmlspecialchars($search_query); ?>">
                        </div>
                        <button type="submit" class="btn btn-primary" style="padding: 10px 20px;">
                            <span class="material-symbols-outlined">search</span> Search
                        </button>
                        <?php if ($search_query !== ''): ?>
                            <a href="/admin/clients.php" class="btn btn-outline" style="padding: 10px 16px;">Clear</a>
                        <?php endif; ?>
                    </form>
                </div>

                <!-- Table -->
                <div class="card" style="padding: 0;">
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Client ID</th>
                                    <th>Full Name</th>
                                    <th>Email Address</th>
                                    <th>Contact Number</th>
                                    <th>Address</th>
                                    <th>Age/Sex</th>
                                    <th>Registration Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($filtered_clients)): ?>
                                    <tr>
                                        <td colspan="7" style="text-align: center; padding: 3rem 1.5rem; color: var(--text-muted);">
                                            No registered client accounts match your search.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($filtered_clients as $c): ?>
                                        <tr>
                                            <td><strong>#<?php echo $c['id']; ?></strong></td>
                                            <td><strong><?php echo htmlspecialchars($c['first_name'] . ' ' . $c['last_name']); ?></strong></td>
                                            <td><?php echo htmlspecialchars($c['email']); ?></td>
                                            <td><?php echo htmlspecialchars($c['contact_number'] ?: 'N/A'); ?></td>
                                            <td><?php echo htmlspecialchars($c['address']); ?></td>
                                            <td><?php echo ($c['age'] ?: 'N/A') . ' / ' . ($c['sex'] ?: 'N/A'); ?></td>
                                            <td><?php echo date('M d, Y', strtotime($c['created_at'])); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </main>
        </div>
    </div>
</body>
</html>
