<?php
// Start the session
session_start();

// Destroy the session
session_unset();
session_destroy();

// Redirect to homepage (assuming your homepage is index.php)
header("Location: /Gearchanix/GEARCHANIX-main/gearchanix/public/pages/hompage/homepage.html");
exit();
?>
