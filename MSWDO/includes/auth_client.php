<?php
if (!session_id()) {
    session_start();
}

if (!isset($_SESSION['client_id'])) {
    header("Location: /index.php?error=unauthorized");
    exit();
}
?>
