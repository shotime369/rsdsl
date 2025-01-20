<?php
// Database connection
$host = 'localhost';
$dbname = 'notes_app';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Initialize variables
$message = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';

    if (!empty($title) && !empty($content)) {
        $timestamp = date('Y-m-d H:i:s');

        // Insert into the database
        $stmt = $pdo->prepare("INSERT INTO notes (title, content, created_at) VALUES (:title, :content, :created_at)");
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':created_at', $timestamp);

        if ($stmt->execute()) {
            $message = "Note saved successfully!";
        } else {
            $message = "Failed to save the note.";
        }
    } else {
        $message = "Please fill in all fields.";
    }
}
?>