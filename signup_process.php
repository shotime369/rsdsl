<?php
session_start();

// Database connection settings
$servername = "localhost";
$username = "shona";
$password = '1234';
$dbname = "loginweb";

// Connect to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data from signup.html
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input_username = $_POST['username'];
    $input_email = $_POST['email'];
    $input_password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate that the passwords match
    if ($input_password !== $confirm_password) {
        echo "Passwords do not match. Please try again.";
        exit();
    }

    // Hash the password - default selects the most up to date hashing algorithm
    $password_hash = password_hash($input_password, PASSWORD_DEFAULT);

    // Prepare SQL statement - user_id is set to auto-increment so isn't included
    $stmt = $conn->prepare("INSERT INTO users (username, password_hash, email) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $input_username, $password_hash, $input_email);

    // Execute the statement and check for success
    if ($stmt->execute()) {
        echo "Sign-up successful! You can now log in.";
    } else {
        echo "Error: " . $stmt->error;
    }
    
    // Close the statement and the connection
    $stmt->close();
}

$conn->close();

header("Location: index.html");
exit();

  