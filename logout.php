<?php
session_start();
session_destroy();  // Destroy the entire session
header("Location: login.php");
exit();
?>
