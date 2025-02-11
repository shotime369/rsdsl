<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

$username = $_SESSION['username']; // Retrieve the stored username

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

// Get form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $task = $_POST['task'];
    $details = $_POST['details'];
    $dueDate = $_POST['dueDate'];

    // Prepare SQL statement
    $stmt = $conn->prepare("INSERT INTO tasks (task, details, username, dueDate) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $task, $details, $username, $dueDate);

    // Execute the statement and check for success
    if ($stmt->execute()) {
        $message = "Task saved successfully!";
    } else {
        $message = "Failed to save task: " . $stmt->error;
    }

    // Close the statement and the connection
    $stmt->close();
    $conn->close();

    // Pass the message to JavaScript
    echo "<script>
            alert('" . addslashes($message) . "');
            window.location.href = 'Tasks.html'; // Reload the page
          </script>";
    exit();
}
?>
