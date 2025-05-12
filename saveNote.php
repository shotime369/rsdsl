<?php

// Include database connection
require 'includes/dbh.inc.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

$username = $_SESSION['username'] ?? null; // Retrieve the stored username

if (!$username) {
    die("User not logged in.");
}

// Handle POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';

    try {
        $stmt = $pdo->prepare("INSERT INTO notes (title, content, username) VALUES (:title, :content, :username)");
        $stmt->execute([
            'title' => $title,
            'content' => $content,
            'username' => $username
        ]);

        $message = "Note saved successfully!";
    } catch (PDOException $e) {
        $message = "Failed to save the note: " . $e->getMessage();
    }

    // Pass the message to JavaScript and reload the page
    echo "<script>
        alert('" . addslashes($message) . "');
        window.location.href = 'Notes.php';
    </script>";
    exit();
}
?>
