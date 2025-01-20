<?php
// Database connection settings
$servername = "localhost";
$username = "root";
$password = "P@ssw0rd";
$dbname = "loginweb";

//try connection
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// initialize variables
$message = "";

// get user input from form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';

    if (!empty($title) && !empty($content)) {
        $timestamp = date('Y-m-d H:i:s');

        // insert into the database title, content, timestamp
        $stmt = $pdo->prepare("INSERT INTO notes (title, content, date_time) VALUES (:title, :content, :date_time)");
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':date_time', $timestamp);

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