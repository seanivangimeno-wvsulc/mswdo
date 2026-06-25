<?php
if (!session_id()) {
    session_start();
}

if (!isset($_SESSION['focal_id'])) {
    header("Location: /focal/login.php?error=unauthorized");
    exit();
}
?>
