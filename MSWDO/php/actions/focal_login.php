<?php
if (!session_id()) {
    session_start();
}
require_once '../../includes/supabase.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: /focal/login.php");
    exit();
}

$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

if (empty($email) || empty($password)) {
    header("Location: /focal/login.php?error=empty");
    exit();
}

// Fetch focal person by email
$filters = ["email=eq." . urlencode($email), "select=*"];
$response = supabase_query('tb_focal_person', 'GET', $filters);

if (empty($response) || isset($response['error']) || !is_array($response)) {
    header("Location: /focal/login.php?error=invalid");
    exit();
}

$focal = $response[0];

// Verify password
if (!password_verify($password, $focal['password'])) {
    header("Location: /focal/login.php?error=invalid");
    exit();
}

// Set Session variables
$_SESSION['focal_id'] = $focal['id'];
$_SESSION['focal_name'] = $focal['first_name'] . ' ' . $focal['last_name'];
$_SESSION['focal_email'] = $focal['email'];
$_SESSION['focal_program_id'] = $focal['program_id']; // The ID of program they are managing!

header("Location: /focal/dashboard.php");
exit();
?>
