<?php
// Test code to pull from the notes
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

// Database connection settings
$servername = "localhost";
$username = "shona";
$password = "1234";
$dbname = "loginweb";

// Connect to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to fetch notes
$sql = "SELECT timestamp, title, content FROM notes";
$result = $conn->query($sql);

// Check if there are any rows returned
if ($result->num_rows > 0) {
    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        echo "<tr><td>" . $row["timestamp"] . "</td><td>" . $row["title"] . "</td><td>" . $row["content"] . "</td></tr>";
    }
} else {
    echo "<tr><td colspan='3'>No notes found</td></tr>";
}

// Close connection
$conn->close();
?>