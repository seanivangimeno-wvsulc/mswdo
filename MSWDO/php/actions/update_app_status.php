<?php
if (!session_id()) {
    session_start();
}
require_once '../../includes/supabase.php';

// Auth validation - must be admin or focal person
if (!isset($_SESSION['admin_id']) && !isset($_SESSION['focal_id'])) {
    header("Location: /index.php?error=unauthorized");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: /home.php");
    exit();
}

$app_id = isset($_POST['application_id']) ? intval($_POST['application_id']) : 0;
$status = isset($_POST['status']) ? trim($_POST['status']) : '';
$recommendation = isset($_POST['recommendation']) ? trim($_POST['recommendation']) : '';
$prepared_by = isset($_POST['prepared_by']) ? trim($_POST['prepared_by']) : '';

if ($app_id <= 0 || empty($status) || empty($recommendation)) {
    // Redirect back with error
    if (isset($_SESSION['admin_id'])) {
        header("Location: /admin/applications.php?id=$app_id&error=empty_fields");
    } else {
        header("Location: /focal/applications.php?id=$app_id&error=empty_fields");
    }
    exit();
}

// 1. If approved, we might want to automatically register this applicant as an active beneficiary of Program #1 (AICS)!
// Let's check if we should add them. Let's first retrieve the applicant details
if ($status === 'Approved') {
    // Query client profile first
    $app_filter = ["id=eq." . $app_id, "select=*"];
    $app_res = supabase_query('tb_aics_applications', 'GET', $app_filter);
    if (!empty($app_res) && is_array($app_res) && !isset($app_res['error'])) {
        $app_details = $app_res[0];
        $client_id = $app_details['client_id'];
        
        // Query client
        $cli_filter = ["id=eq." . $client_id, "select=*"];
        $cli_res = supabase_query('tb_clients', 'GET', $cli_filter);
        if (!empty($cli_res) && is_array($cli_res) && !isset($cli_res['error'])) {
            $client = $cli_res[0];
            
            // Check if this beneficiary already exists
            $id_number = 'AICS-' . str_pad($client['id'], 6, '0', STR_PAD_LEFT);
            $ben_filter = ["id_number=eq." . $id_number];
            $ben_res = supabase_query('tb_beneficiaries', 'GET', $ben_filter);
            
            if (empty($ben_res) || isset($ben_res['error'])) {
                // Register new beneficiary under program #1 (AICS)
                $ben_data = [
                    'id_number' => $id_number,
                    'first_name' => $client['first_name'],
                    'middle_name' => $client['middle_name'],
                    'last_name' => $client['last_name'],
                    'program_id' => 1, // AICS Program
                    'age' => $client['age'],
                    'gender' => $client['sex'],
                    'address' => $client['address'],
                    'contact_number' => $client['contact_number'],
                    'birthdate' => $client['date_of_birth']
                ];
                supabase_query('tb_beneficiaries', 'POST', [], $ben_data);
            }
        }
    }
}

// 2. Perform PATCH update on tb_aics_applications
$update_data = [
    'status' => $status,
    'recommendation' => $recommendation,
    'prepared_by' => $prepared_by
];

$filters = ["id=eq." . $app_id];
$patch_response = supabase_query('tb_aics_applications', 'PATCH', $filters, $update_data);

// 3. Redirect back to caller page
$search_query = $_POST['search'] ?? '';
$status_filter = $_POST['status_filter'] ?? '';

if (isset($_SESSION['admin_id'])) {
    header("Location: /admin/applications.php?search=" . urlencode($search_query) . "&status=" . urlencode($status_filter));
} else {
    header("Location: /focal/applications.php");
}
exit();
?>
