<?php
if (!session_id()) {
    session_start();
}
require_once '../../includes/supabase.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: /index.php");
    exit();
}

$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

if (empty($email) || empty($password)) {
    header("Location: /index.php?error=empty");
    exit();
}

// Fetch user from tb_clients via Supabase Rest API
$filters = ["email=eq." . urlencode($email), "select=*"];
$response = supabase_query('tb_clients', 'GET', $filters);

if (empty($response) || isset($response['error']) || !is_array($response)) {
    header("Location: /index.php?error=nouser");
    exit();
}

$user = $response[0];

// Verify hashed password
if (!password_verify($password, $user['password'])) {
    header("Location: /index.php?error=invalid");
    exit();
}

// Successful login
$_SESSION['client_id'] = $user['id'];
$_SESSION['client_name'] = $user['first_name'] . ' ' . $user['last_name'];
$_SESSION['client_email'] = $user['email'];

header("Location: /client/dashboard.php");
exit();
?>
