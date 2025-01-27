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


//timestamp for testing
$timestamp = "2025-01-22 10:00:00"; // Replace this with values - or value range?

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

    // Prepare SQL statement
       $stmt = $conn->prepare("SELECT * FROM notes ORDER BY date_time DESC");
       if ($stmt === false) {
           die("Error preparing statement: " . $conn->error);
       }

   $stmt->execute();

   // Fetch results
   $result = $stmt->get_result();
   if ($result->num_rows > 0) {
       while ($row = $result->fetch_assoc()) {
           echo "Title: " . htmlspecialchars($row["title"]) . "<br>";
           echo "Content: " . nl2br(htmlspecialchars($row["content"])) . "<br>";
           echo "Date: " . htmlspecialchars($row["date_time"]) . "<br><br>";
       }
   } else {
       echo "No notes found";
   }

   // Close statement and connection
   $stmt->close();
   $conn->close();

   ?>