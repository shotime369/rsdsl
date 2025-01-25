<?php
//test code to pull from the notes
//these aren't working yet
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Database connection settings
$servername = "localhost";
$username = "root";
$password = "P@ssw0rd";
$dbname = "loginweb";

// Connect to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

// Fetch events
$query = "SELECT * FROM tasks";
$result = $conn->query($query);

$events = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $events[] = [
            'date' => $row['event_date'],
            'task' => $row['task'],
            'details' => $row['details']
        ];
    }
}

// Close connection
$conn->close();

// Return events as JSON
header('Content-Type: application/json');
echo json_encode($events);
?>
