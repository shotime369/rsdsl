<?php

// Include the Database class to handle the connection
require_once 'Database.php';

session_start(); // Ensure the session is started to access the username

// Retrieve the username from session
$username = $_SESSION['username'];

// Use the Database class to get the PDO instance
$pdo = Database::getInstance();

// Prepare and execute the query to fetch media for the specific username
$stmt = $pdo->prepare("SELECT title, release_date FROM media WHERE username = :username");
$stmt->execute(['username' => $username]);

// Fetch all results as an associative array
$media = $stmt->fetchAll();

// Return the result as JSON
echo json_encode($media);
?>

