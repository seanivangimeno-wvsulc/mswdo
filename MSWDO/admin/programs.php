<?php
require_once '../includes/auth_admin.php';
require_once '../includes/supabase.php';

$success_msg = '';
$error_msg = '';

// Check if adding an allocation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'allocate') {
    $program_id = isset($_POST['program_id']) ? intval($_POST['program_id']) : 0;
    $amount = isset($_POST['amount']) ? floatval($_POST['amount']) : 0;
    $remarks = isset($_POST['remarks']) ? trim($_POST['remarks']) : '';

    if ($program_id <= 0 || $amount <= 0) {
        $error_msg = 'Please select a program and provide a positive funding amount.';
    } else {
        // 1. Fetch current program details to find current budget
        $prog_filt = ["id=eq." . $program_id, "select=*"];
        $prog_res = supabase_query('tb_program', 'GET', $prog_filt);
        
        if (!empty($prog_res) && is_array($prog_res) && !isset($prog_res['error'])) {
            $program = $prog_res[0];
            $new_budget = floatval($program['budget']) + $amount;

            // 2. Insert into tb_allocation_history
            $history_data = [
                'amount' => $amount,
                'remarks' => $remarks,
                'admin_id' => $_SESSION['admin_id'],
                'program_id' => $program_id,
                'date' => date('Y-m-d')
            ];
            supabase_query('tb_allocation_history', 'POST', [], $history_data);

            // 3. Update the program budget in tb_program via PATCH
            supabase_query('tb_program', 'PATCH', ["id=eq." . $program_id], ['budget' => $new_budget]);

            $success_msg = "Successfully allocated additional ₱" . number_format($amount) . " to " . $program['program_name'] . ".";
        } else {
            $error_msg = 'Program not found.';
        }
    }
}

// Fetch programs
$prog_response = supabase_query('tb_program', 'GET', ["select=*"]);
$programs = is_array($prog_response) && !isset($prog_response['error']) ? $prog_response : [];

// Fetch allocation history
$hist_response = supabase_query('tb_allocation_history', 'GET', ["select=*"]);
$allocations = is_array($hist_response) && !isset($hist_response['error']) ? $hist_response : [];

// Sort allocations (recent first)
usort($allocations, function($a, $b) {
    return strcmp($b['date'], $a['date']);
});

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
    <title>Programs & Budgets - MSWDO Portal</title>
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
                        <h2 style="font-size: 1.5rem; color: var(--primary-dark); margin: 0;">Social Programs & LGU Budgets</h2>
                        <p style="font-size: 0.8rem; color: var(--text-muted); margin-top: 2px;">Track and update funding allocations for individual municipal social protection desks.</p>
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

                <!-- Two columns: active programs & allocation form -->
                <div style="display: grid; grid-template-columns: 7fr 5fr; gap: 1.5rem; margin-bottom: 2rem; align-items: start;">
                    
                    <!-- Left: active programs list -->
                    <div class="card" style="margin: 0;">
                        <h3 style="font-size: 1.1rem; color: var(--primary-dark); margin-bottom: 1.25rem;">Active Social Welfare Programs</h3>
                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Program ID</th>
                                        <th>Program Desk Name</th>
                                        <th>Status</th>
                                        <th style="text-align: right;">Current Budget Balance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($programs as $p): ?>
                                        <tr>
                                            <td><strong>#<?php echo $p['id']; ?></strong></td>
                                            <td>
                                                <div><strong><?php echo htmlspecialchars($p['program_name']); ?></strong></div>
                                                <div style="font-size: 0.75rem; color: var(--text-muted);"><?php echo htmlspecialchars($p['description']); ?></div>
                                            </td>
                                            <td>
                                                <span class="badge" style="background-color: #dcfce7; color: #15803d; font-size: 0.7rem;">
                                                    <?php echo $p['status']; ?>
                                                </span>
                                            </td>
                                            <td style="text-align: right; font-weight: 700; color: var(--primary);">
                                                ₱<?php echo number_format($p['budget'], 2); ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Right: allocation form -->
                    <div class="card" style="margin: 0;">
                        <h3 style="font-size: 1.1rem; color: var(--primary-dark); margin-bottom: 1.25rem;">Allocate Additional Funds</h3>
                        <form action="/admin/programs.php" method="POST">
                            <input type="hidden" name="action" value="allocate">
                            
                            <div class="form-group">
                                <label for="alloc-program">Select Welfare Desk *</label>
                                <select name="program_id" id="alloc-program" class="form-control" required>
                                    <option value="">-- Choose Program --</option>
                                    <?php foreach ($programs as $prog): ?>
                                        <option value="<?php echo $prog['id']; ?>"><?php echo htmlspecialchars($prog['program_name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="alloc-amount">Budget Increment (PHP) *</label>
                                <input type="number" name="amount" id="alloc-amount" class="form-control" placeholder="E.g., 50000" min="1" step="any" required>
                            </div>

                            <div class="form-group">
                                <label for="alloc-remarks">Reference / Remarks *</label>
                                <textarea name="remarks" id="alloc-remarks" class="form-control" rows="3" placeholder="E.g., Quarterly LGU General Appropriations release" required></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary" style="width: 100%;">
                                <span class="material-symbols-outlined">payments</span> Release Funding Increment
                            </button>
                        </form>
                    </div>

                </div>

                <!-- Bottom: allocation history list -->
                <div class="card" style="margin: 0;">
                    <h3 style="font-size: 1.1rem; color: var(--primary-dark); margin-bottom: 1.25rem;">Release & Allocation History</h3>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Release Date</th>
                                    <th>Social Program Desk</th>
                                    <th>Reference / Remarks</th>
                                    <th style="text-align: right;">Amount Released</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($allocations)): ?>
                                    <tr>
                                        <td colspan="4" style="text-align: center; padding: 2rem; color: var(--text-muted);">
                                            No active historical budget records found.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($allocations as $alloc): ?>
                                        <tr>
                                            <td><?php echo date('M d, Y', strtotime($alloc['date'])); ?></td>
                                            <td><strong><?php echo htmlspecialchars($program_map[$alloc['program_id']] ?? 'General Relief'); ?></strong></td>
                                            <td><span style="font-size: 0.85rem; color: var(--text-muted);"><?php echo htmlspecialchars($alloc['remarks']); ?></span></td>
                                            <td style="text-align: right; font-weight: 700; color: var(--success);">
                                                + ₱<?php echo number_format($alloc['amount'], 2); ?>
                                            </td>
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
