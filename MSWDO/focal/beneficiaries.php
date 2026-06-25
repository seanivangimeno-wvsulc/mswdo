<?php
require_once '../includes/auth_focal.php';
require_once '../includes/supabase.php';

$focal_program_id = $_SESSION['focal_program_id'];

// Fetch beneficiaries assigned specifically to this program desk
$ben_filters = ["program_id=eq." . $focal_program_id, "select=*"];
$bens_response = supabase_query('tb_beneficiaries', 'GET', $ben_filters);
$beneficiaries = is_array($bens_response) && !isset($bens_response['error']) ? $bens_response : [];

// Fetch program details
$prog_filt = ["id=eq." . $focal_program_id, "select=*"];
$prog_res = supabase_query('tb_program', 'GET', $prog_filt);
$assigned_program = (!empty($prog_res) && is_array($prog_res) && !isset($prog_res['error'])) ? $prog_res[0] : null;

// Support basic search filter
$search_query = isset($_GET['search']) ? trim($_GET['search']) : '';
$filtered_bens = [];

foreach ($beneficiaries as $ben) {
    if ($search_query !== '') {
        $fullname = $ben['first_name'] . ' ' . $ben['last_name'];
        if (stripos($fullname, $search_query) === false && stripos($ben['id_number'], $search_query) === false) {
            continue;
        }
    }
    $filtered_bens[] = $ben;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sectoral Beneficiaries Directory - Focal Panel</title>
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
                        <h2 style="font-size: 1.5rem; color: var(--primary-dark); margin: 0;">Welfare Beneficiaries Registered</h2>
                        <p style="font-size: 0.8rem; color: var(--text-muted); margin-top: 2px;">Social Protection Registry for Desk: <strong><?php echo htmlspecialchars($assigned_program['program_name'] ?? ''); ?></strong></p>
                    </div>
                </div>

                <!-- Search block -->
                <div class="card" style="padding: 1.25rem; margin-bottom: 1.5rem;">
                    <form action="/focal/beneficiaries.php" method="GET" style="display: flex; gap: 12px; align-items: flex-end;">
                        <div class="form-group" style="margin: 0; flex: 1;">
                            <label for="search" style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase;">Search Beneficiary Name or ID</label>
                            <input type="text" name="search" id="search" class="form-control" placeholder="E.g., Juan Dela Cruz" value="<?php echo htmlspecialchars($search_query); ?>">
                        </div>
                        <button type="submit" class="btn btn-primary" style="padding: 10px 20px;">
                            <span class="material-symbols-outlined">search</span> Search
                        </button>
                        <?php if ($search_query !== ''): ?>
                            <a href="/focal/beneficiaries.php" class="btn btn-outline" style="padding: 10px 16px;">Clear</a>
                        <?php endif; ?>
                    </form>
                </div>

                <!-- Beneficiaries List Table -->
                <div class="card" style="padding: 0;">
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Government ID Number</th>
                                    <th>Beneficiary Full Name</th>
                                    <th>Age/Sex</th>
                                    <th>Resident Address</th>
                                    <th>Contact Phone</th>
                                    <th>Registration Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($filtered_bens)): ?>
                                    <tr>
                                        <td colspan="6" style="text-align: center; padding: 3rem 1.5rem; color: var(--text-muted);">
                                            No approved program beneficiaries registered matching your filters.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($filtered_bens as $ben): ?>
                                        <tr>
                                            <td><strong><code><?php echo htmlspecialchars($ben['id_number']); ?></code></strong></td>
                                            <td><strong><?php echo htmlspecialchars($ben['first_name'] . ' ' . $ben['last_name']); ?></strong></td>
                                            <td><?php echo ($ben['age'] ?: 'N/A') . ' / ' . ($ben['gender'] ?: 'N/A'); ?></td>
                                            <td><?php echo htmlspecialchars($ben['address'] ?: 'Tubungan, Iloilo'); ?></td>
                                            <td><?php echo htmlspecialchars($ben['contact_number'] ?: 'N/A'); ?></td>
                                            <td><?php echo date('M d, Y', strtotime($ben['created_at'])); ?></td>
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
