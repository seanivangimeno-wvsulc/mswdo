<?php
require_once '../../includes/auth_client.php';
require_once '../../includes/supabase.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: /aics_application_form.php");
    exit();
}

$client_id = $_SESSION['client_id'];

// 1. Gather Section A inputs to update the client's permanent profile
$profile_data = [
    'first_name' => trim($_POST['first_name'] ?? ''),
    'last_name' => trim($_POST['last_name'] ?? ''),
    'age' => !empty($_POST['age']) ? intval($_POST['age']) : null,
    'sex' => trim($_POST['sex'] ?? ''),
    'civil_status' => trim($_POST['civil_status'] ?? ''),
    'date_of_birth' => !empty($_POST['date_of_birth']) ? $_POST['date_of_birth'] : null,
    'place_of_birth' => trim($_POST['place_of_birth'] ?? ''),
    'contact_number' => trim($_POST['contact_number'] ?? ''),
    'address' => trim($_POST['address'] ?? ''),
    'religion' => trim($_POST['religion'] ?? ''),
    'occupation' => trim($_POST['occupation'] ?? ''),
    'educational_attainment' => trim($_POST['educational_attainment'] ?? '')
];

// Perform PATCH update on client record in tb_clients
supabase_query('tb_clients', 'PATCH', ["id=eq." . $client_id], $profile_data);

// 2. Gather Section B, C & E details to submit application
$service_id = !empty($_POST['service_id']) ? intval($_POST['service_id']) : 1;
$findings = trim($_POST['findings'] ?? '');
$signature = trim($_POST['signature'] ?? '');

// Check file upload (handled by Express Multer proxy)
$uploaded_filename = '';
if (!empty($_FILES['supporting_doc']) && $_FILES['supporting_doc']['error'] === 0) {
    // Because server.ts has already stored the file in the /MSWDO/uploads directory,
    // we can retrieve the basename or move it. Basename is enough to save in 'others' or custom.
    $uploaded_filename = basename($_FILES['supporting_doc']['tmp_name']);
}

$app_data = [
    'client_id' => $client_id,
    'service_id' => $service_id,
    'findings' => $findings,
    'status' => 'Pending',
    'others' => $signature . ($uploaded_filename ? '|file:' . $uploaded_filename : '')
];

$app_response = supabase_query('tb_aics_applications', 'POST', [], $app_data);

if (empty($app_response) || isset($app_response['error']) || !is_array($app_response)) {
    header("Location: /client/dashboard.php?error=app_submit_failed");
    exit();
}

// Get the new application ID
$application = $app_response[0];
$application_id = $application['id'];

// 3. Save clientele categories if any checked
$categories = $_POST['categories'] ?? [];
if (is_array($categories) && !empty($categories)) {
    foreach ($categories as $cat) {
        $cat_data = [
            'category' => $cat,
            'client_id' => $client_id,
            'application_id' => $application_id
        ];
        supabase_query('clientele_category', 'POST', [], $cat_data);
    }
}

// 4. Save family composition rows
$famcom_rows = $_POST['famcom'] ?? [];
if (is_array($famcom_rows) && !empty($famcom_rows)) {
    foreach ($famcom_rows as $row) {
        if (empty($row['first_name']) || empty($row['last_name'])) {
            continue; // Skip invalid family members
        }
        $fam_data = [
            'application_id' => $application_id,
            'first_name' => trim($row['first_name']),
            'middle_name' => trim($row['middle_name'] ?? ''),
            'last_name' => trim($row['last_name']),
            'age' => !empty($row['age']) ? intval($row['age']) : null,
            'sex' => trim($row['sex'] ?? 'Male'),
            'civil_status' => trim($row['civil_status'] ?? 'Single'),
            'educational_attainment' => trim($row['educational_attainment'] ?? ''),
            'occupation' => trim($row['occupation'] ?? ''),
            'income' => trim($row['income'] ?? '')
        ];
        supabase_query('tb_aics_famcom', 'POST', [], $fam_data);
    }
}

// Redirect client to their applications page with success message
header("Location: /client/my_applications.php?submitted=1");
exit();
?>
