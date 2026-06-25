<?php
require_once '../includes/auth_admin.php';
require_once '../includes/supabase.php';

// Fetch all beneficiaries
$ben_response = supabase_query('tb_beneficiaries', 'GET', ["select=*"]);
$beneficiaries = is_array($ben_response) && !isset($ben_response['error']) ? $ben_response : [];

// Fetch all programs for select filter
$prog_response = supabase_query('tb_program', 'GET', ["select=*"]);
$programs = is_array($prog_response) && !isset($prog_response['error']) ? $prog_response : [];

// Program ID mapping
$program_map = [];
foreach ($programs as $prog) {
    $program_map[$prog['id']] = $prog['program_name'];
}

// Support simple filters
$search_query = isset($_GET['search']) ? trim($_GET['search']) : '';
$prog_filter = isset($_GET['program_id']) ? intval($_GET['program_id']) : 0;

$filtered_bens = [];
foreach ($beneficiaries as $ben) {
    if ($prog_filter > 0 && $ben['program_id'] != $prog_filter) {
        continue;
    }
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
    <title>Welfare Beneficiaries - MSWDO Portal</title>
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
                        <h2 style="font-size: 1.5rem; color: var(--primary-dark); margin: 0;">Approved Sectoral Beneficiaries</h2>
                        <p style="font-size: 0.8rem; color: var(--text-muted); margin-top: 2px;">Database of individuals registered in primary social assistance campaigns.</p>
                    </div>
                </div>

                <!-- Filters & search -->
                <div class="card" style="padding: 1.25rem; margin-bottom: 1.5rem;">
                    <form action="/admin/beneficiaries.php" method="GET" style="display: flex; gap: 12px; align-items: flex-end; flex-wrap: wrap;">
                        <div class="form-group" style="margin: 0; flex: 1; min-width: 250px;">
                            <label for="search" style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase;">Search by Beneficiary Name or ID</label>
                            <input type="text" name="search" id="search" class="form-control" placeholder="E.g., Juan Dela Cruz" value="<?php echo htmlspecialchars($search_query); ?>">
                        </div>
                        <div class="form-group" style="margin: 0; width: 220px;">
                            <label for="program_id" style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase;">Filter by Program</label>
                            <select name="program_id" id="program_id" class="form-control">
                                <option value="0">All Programs</option>
                                <?php foreach ($programs as $prog): ?>
                                    <option value="<?php echo $prog['id']; ?>" <?php echo $prog_filter == $prog['id'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($prog['program_name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary" style="padding: 10px 20px;">
                            <span class="material-symbols-outlined">filter_list</span> Apply Filter
                        </button>
                        <?php if ($search_query !== '' || $prog_filter > 0): ?>
                            <a href="/admin/beneficiaries.php" class="btn btn-outline" style="padding: 10px 16px;">Clear</a>
                        <?php endif; ?>
                    </form>
                </div>

                <!-- Beneficiary list table -->
                <div class="card" style="padding: 0;">
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>ID Number</th>
                                    <th>Beneficiary Name</th>
                                    <th>Social Program</th>
                                    <th>Age/Sex</th>
                                    <th>Barangay Address</th>
                                    <th>Contact Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($filtered_bens)): ?>
                                    <tr>
                                        <td colspan="6" style="text-align: center; padding: 3rem 1.5rem; color: var(--text-muted);">
                                            No active program beneficiaries registered matching your filters.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($filtered_bens as $ben): ?>
                                        <tr>
                                            <td><strong><code><?php echo htmlspecialchars($ben['id_number']); ?></code></strong></td>
                                            <td><strong><?php echo htmlspecialchars($ben['first_name'] . ' ' . $ben['last_name']); ?></strong></td>
                                            <td>
                                                <span style="font-weight: 600; color: var(--primary);"><?php echo htmlspecialchars($program_map[$ben['program_id']] ?? 'General Relief'); ?></span>
                                            </td>
                                            <td><?php echo ($ben['age'] ?: 'N/A') . ' / ' . ($ben['gender'] ?: 'N/A'); ?></td>
                                            <td><?php echo htmlspecialchars($ben['address'] ?: 'Tubungan, Iloilo'); ?></td>
                                            <td><?php echo htmlspecialchars($ben['contact_number'] ?: 'None'); ?></td>
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
