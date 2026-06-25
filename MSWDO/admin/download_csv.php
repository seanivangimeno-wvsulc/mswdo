<?php
require_once '../includes/auth_admin.php';
require_once '../includes/supabase.php';

$type = isset($_GET['type']) ? trim($_GET['type']) : '';

if (empty($type)) {
    die("Invalid request type.");
}

// Disable caching
header("Cache-Control: max-age=0, no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: Wed, 11 Jan 1984 05:00:00 GMT");

if ($type === 'clients') {
    // Export Clients Directory
    $response = supabase_query('tb_clients', 'GET', ["select=*"]);
    $filename = "MSWDO_Clients_Directory_" . date('Ymd_His') . ".csv";
    
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    
    $output = fopen('php://output', 'w');
    // Header Row
    fputcsv($output, ['Client ID', 'First Name', 'Middle Name', 'Last Name', 'Email', 'Contact Number', 'Address', 'Age', 'Sex', 'Civil Status', 'Created At']);
    
    if (is_array($response) && !isset($response['error'])) {
        foreach ($response as $row) {
            fputcsv($output, [
                $row['id'],
                $row['first_name'],
                $row['middle_name'],
                $row['last_name'],
                $row['email'],
                $row['contact_number'],
                $row['address'],
                $row['age'],
                $row['sex'],
                $row['civil_status'],
                $row['created_at']
            ]);
        }
    }
    fclose($output);
    exit();

} elseif ($type === 'applications') {
    // Export AICS Applications
    $response = supabase_query('tb_aics_applications', 'GET', ["select=*"]);
    
    // Fetch clients mapping
    $clients_res = supabase_query('tb_clients', 'GET', ["select=id,first_name,last_name"]);
    $clients = [];
    if (is_array($clients_res) && !isset($clients_res['error'])) {
        foreach ($clients_res as $c) {
            $clients[$c['id']] = $c['first_name'] . ' ' . $c['last_name'];
        }
    }

    $service_map = [
        1 => 'Medical Assistance',
        2 => 'Burial Assistance',
        3 => 'Educational Assistance',
        4 => 'Food Assistance',
        5 => 'Transportation Assistance'
    ];

    $filename = "MSWDO_AICS_Applications_" . date('Ymd_His') . ".csv";
    
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    
    $output = fopen('php://output', 'w');
    fputcsv($output, ['Application ID', 'Applicant Name', 'Type of Assistance', 'Application Date', 'Findings Narrative', 'Status', 'Caseworker Recommendation', 'Prepared By']);
    
    if (is_array($response) && !isset($response['error'])) {
        foreach ($response as $row) {
            fputcsv($output, [
                $row['id'],
                $clients[$row['client_id']] ?? 'Unknown Client',
                $service_map[$row['service_id']] ?? 'General AICS',
                $row['application_date'],
                $row['findings'],
                $row['status'],
                $row['recommendation'],
                $row['prepared_by']
            ]);
        }
    }
    fclose($output);
    exit();

} elseif ($type === 'beneficiaries') {
    // Export Program Beneficiaries
    $response = supabase_query('tb_beneficiaries', 'GET', ["select=*"]);
    
    // Fetch programs mapping
    $progs_res = supabase_query('tb_program', 'GET', ["select=id,program_name"]);
    $programs = [];
    if (is_array($progs_res) && !isset($progs_res['error'])) {
        foreach ($progs_res as $p) {
            $programs[$p['id']] = $p['program_name'];
        }
    }

    $filename = "MSWDO_Sectoral_Beneficiaries_" . date('Ymd_His') . ".csv";
    
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    
    $output = fopen('php://output', 'w');
    fputcsv($output, ['Beneficiary ID', 'Government ID Number', 'First Name', 'Middle Name', 'Last Name', 'Program Assigned', 'Age', 'Gender', 'Barangay Address', 'Contact Number']);
    
    if (is_array($response) && !isset($response['error'])) {
        foreach ($response as $row) {
            fputcsv($output, [
                $row['id'],
                $row['id_number'],
                $row['first_name'],
                $row['middle_name'],
                $row['last_name'],
                $programs[$row['program_id']] ?? 'General Relief',
                $row['age'],
                $row['gender'],
                $row['address'],
                $row['contact_number']
            ]);
        }
    }
    fclose($output);
    exit();
}

die("Unknown request type.");
?>
