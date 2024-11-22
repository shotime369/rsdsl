<?php
$servername = "localhost";
$username = "root";        
$password = "P@ssw0rd";            
$dbname = "loginweb";   // Name of the database

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully!";
?>
