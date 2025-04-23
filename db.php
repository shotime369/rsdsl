<?php
$servername = "localhost";
$dbname = "loginweb";
$dbusername = "shona";
$dbpassword = "1234";

// Create connection
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

