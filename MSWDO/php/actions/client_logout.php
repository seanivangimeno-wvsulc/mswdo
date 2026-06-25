<?php
if (!session_id()) {
    session_start();
}
// Unset client session variables
unset($_SESSION['client_id']);
unset($_SESSION['client_name']);
unset($_SESSION['client_email']);

header("Location: /index.php?loggedout=1");
exit();
?>
