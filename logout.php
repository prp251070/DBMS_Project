<?php
// Start the session
session_start();

// Destroy all session variables
session_unset();

// Destroy the session itself
session_destroy();

// Redirect to the index page (login page)
header("Location: ../exam_portal/home.html");
exit();
?>
