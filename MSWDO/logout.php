<?php
if (!session_id()) {
    session_start();
}
session_destroy();
header("Location: /index.php?loggedout=1");
exit();
?>
