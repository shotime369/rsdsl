<?php

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

// Get the month and year
$month = isset($_GET['month']) ? (int)$_GET['month'] : date('m');
$year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');

// Prepare the start and end date for the month
$startDate = "$year-$month-01";
$endDate = "$year-$month-" . date('t', strtotime($startDate)); // Get the last day of the month

// Prepare the SQL query to fetch tasks for the month and year
$sql = "SELECT task, details, dueDate FROM tasks WHERE dueDate BETWEEN ? AND ? ORDER BY dueDate";

// Prepare the statement
$stmt = $conn->prepare($sql);

// Bind parameters
$stmt->bind_param("ss", $startDate, $endDate);

// Execute the query
$stmt->execute();

// Get the result
$result = $stmt->get_result();

// Fetch all tasks as an associative array
$tasks = $result->fetch_all(MYSQLI_ASSOC);

// Close the statement and connection
$stmt->close();
$conn->close();

// Return the tasks as JSON
header('Content-Type: application/json');
echo json_encode($tasks);
?>