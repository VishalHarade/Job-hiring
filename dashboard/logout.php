<?php
session_start();

// Destroy the session to log out the student
session_destroy();

// Redirect back to the home page or login page
header("Location: ../index.php");
exit();
?>
