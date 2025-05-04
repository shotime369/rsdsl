<?php
session_start();

require 'includes/dbh.inc.php'; // Include the PDO database connection

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

    // Hash the password - default selects the most up-to-date hashing algorithm
    $password_hash = password_hash($input_password, PASSWORD_DEFAULT);

    try {
        // Prepare SQL statement
        $stmt = $pdo->prepare("INSERT INTO users (username, password_hash, email) VALUES (?, ?, ?)");
        $stmt->execute([$input_username, $password_hash, $input_email]);

        echo "Sign-up successful! You can now log in.";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

header("Location: index.html");
exit();
?>
