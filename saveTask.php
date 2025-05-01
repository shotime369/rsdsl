<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

require 'includes/dbh.inc.php';

$username = $_SESSION['username'] ?? null;

if (!$username) {
    die("User not logged in.");
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $task = $_POST['task'] ?? '';
    $details = $_POST['details'] ?? '';
    $dueDate = $_POST['dueDate'] ?? '';

    try {
        $stmt = $pdo->prepare("INSERT INTO tasks (task, details, username, dueDate) VALUES (:task, :details, :username, :dueDate)");
        $stmt->execute([
            'task' => $task,
            'details' => $details,
            'username' => $username,
            'dueDate' => $dueDate
        ]);
        $message = "Task saved successfully!";
    } catch (PDOException $e) {
        $message = "Failed to save task: " . $e->getMessage();
    }

    echo "<script>
        alert('" . addslashes($message) . "');
        window.location.href = 'Tasks.html';
    </script>";
    exit();
}
?>

