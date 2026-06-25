<?php
if (!session_id()) {
    session_start();
}
require_once '../../includes/supabase.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: /index.php");
    exit();
}

$first_name = isset($_POST['first_name']) ? trim($_POST['first_name']) : '';
$last_name = isset($_POST['last_name']) ? trim($_POST['last_name']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';
$confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

if (empty($first_name) || empty($last_name) || empty($email) || empty($password)) {
    header("Location: /index.php?error=empty");
    exit();
}

if ($password !== $confirm_password) {
    header("Location: /index.php?error=mismatch");
    exit();
}

// 1. Check if email already exists
$filters = ["email=eq." . urlencode($email)];
$exists_response = supabase_query('tb_clients', 'GET', $filters);

if (!empty($exists_response) && is_array($exists_response) && !isset($exists_response['error'])) {
    header("Location: /index.php?error=exists");
    exit();
}

// 2. Hash password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// 3. Post user data to Supabase tb_clients
$body = [
    'first_name' => $first_name,
    'last_name' => $last_name,
    'email' => $email,
    'password' => $hashed_password,
    'address' => 'Tubungan, Iloilo' // Default address field is NOT NULL in database
];

$insert_response = supabase_query('tb_clients', 'POST', [], $body);

if (isset($insert_response['error']) || empty($insert_response)) {
    header("Location: /index.php?error=db_error");
    exit();
}

// Success
header("Location: /index.php?registered=1");
exit();
?>
