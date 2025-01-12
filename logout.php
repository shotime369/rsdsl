<?php

session_start();

// Destroy all session variables
session_unset();
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect the user to the login page
header("Location: index.html");
exit();
?>
