<?php
//test code to pull from the notes
//these aren't working yet
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
// Database connection settings
$servername = "localhost";
$username = "root";
$password = "P@ssw0rd";
$dbname = "loginweb";

// Connect to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

 $sql = "SELECT date, title, content FROM notes";
 $result = $conn->query($sql);

 if ($result->num_rows > 0) {
     // Output data of each row
     while($row = $result->fetch_assoc()) {
         echo "<tr><td>" . $row["date"]. "</td><td>" . $row["title"]. "</td><td>" . $row["content"]. "</td></tr>";
     }
 } else {
     echo "<tr><td colspan='3'>No notes found</td></tr>";
 }

   // Close statement and connection
   $stmt->close();
   $conn->close();

   ?>