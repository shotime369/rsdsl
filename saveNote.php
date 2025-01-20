<?php

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

// Get form data from notes.html
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $content = $_POST['content'];
    //get timestamp
    $timestamp = date('Y-m-d H:i:s');

    // Prepare SQL statement - notesID is set to auto-increment so isn't included
   $stmt = $conn->prepare("INSERT INTO notes (title, content, timestamp) VALUES (?, ?, ?)");
     $stmt->bind_param("sss", $title, $content, $timestamp);

        // Execute the statement and check for success
        if ($stmt->execute()) {
                $message = "Note saved successfully!";
            } else {
                $message = "Failed to save the note: " . $stmt->error;
            }

            // Close the statement and the connection
            $stmt->close();
            $conn->close();

            // Output the message
            echo $message;
        }

?>