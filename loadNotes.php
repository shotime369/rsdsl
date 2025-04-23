<?php
// Enable error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

// Include database connection file
require 'includes/dbh.inc.php';

// Ensure the user is logged in
if (!isset($_SESSION['username'])) {
    die("You must be logged in to view notes.");
}
$username = $_SESSION['username'];

// Handle delete request using PDO
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $delete_sql = "DELETE FROM notes WHERE noteID = :noteID AND username = :username";
    $stmt = $pdo->prepare($delete_sql);

    if ($stmt->execute(['noteID' => $delete_id, 'username' => $username])) {
        $message = "Note deleted successfully!";
    } else {
        $errorInfo = $stmt->errorInfo();
        $message = "Error deleting note: " . $errorInfo[2];
    }
}


// SQL query to fetch notes for the logged-in user using PDO
$sql = "SELECT noteID, timestamp, title, content FROM notes WHERE username = :username ORDER BY timestamp DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute(['username' => $username]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Output rows of notes
if ($rows) {
    foreach ($rows as $row) {
        echo "<tr>
                <td>" . htmlspecialchars($row["timestamp"]) . "</td>
                <td>" . htmlspecialchars($row["title"]) . "</td>
                <td>" . htmlspecialchars($row["content"]) . "</td>
                <td>
                    <a href='?delete_id=" . $row['noteID'] . "' onclick='return confirm(\"Are you sure you want to delete this note?\")'>Delete</a>
                </td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='4'>No notes found</td></tr>";
}

?>
