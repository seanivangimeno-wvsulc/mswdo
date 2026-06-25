<?php
if (!session_id()) {
    session_start();
}
require_once '../../includes/supabase.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: /admin/login.php");
    exit();
}

$input = isset($_POST['username_or_email']) ? trim($_POST['username_or_email']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

if (empty($input) || empty($password)) {
    header("Location: /admin/login.php?error=empty");
    exit();
}

// PostgREST "or" syntax to check username or email matching
$filters = [
    "or=(username.eq." . urlencode($input) . ",email.eq." . urlencode($input) . ")",
    "select=*"
];

$response = supabase_query('tb_admin', 'GET', $filters);

if (empty($response) || isset($response['error']) || !is_array($response)) {
    header("Location: /admin/login.php?error=invalid");
    exit();
}

$admin = $response[0];

// Verify hashed password
if (!password_verify($password, $admin['password'])) {
    header("Location: /admin/login.php?error=invalid");
    exit();
}

// Successful login
$_SESSION['admin_id'] = $admin['id'];
$_SESSION['admin_name'] = $admin['first_name'] . ' ' . $admin['last_name'];
$_SESSION['admin_username'] = $admin['username'];
$_SESSION['admin_email'] = $admin['email'];

header("Location: /admin/dashboard.php");
exit();
?>
