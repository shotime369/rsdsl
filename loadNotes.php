<?php
// Enable error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

// Database connection settings
$servername = "localhost";
$user = "shona";
$password = "1234";
$dbname = "loginweb";

// Connect to the database
$conn = new mysqli($servername, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure the user is logged in
if (!isset($_SESSION['username'])) {
    die("You must be logged in to view notes.");
}
$username = $_SESSION['username'];

// Handle delete request
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $delete_sql = "DELETE FROM notes WHERE noteID = ? AND username = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("is", $delete_id, $username);
    if ($stmt->execute()) {
        $message = "Note deleted successfully!";
    } else {
        $message = "Error deleting note: " . $stmt->error;
    }
    $stmt->close();
}

// SQL query to fetch notes for the logged-in user
$sql = "SELECT noteID, timestamp, title, content FROM notes WHERE username = ? ORDER BY timestamp DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

// Output rows of notes
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
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

// Close connection
$conn->close();
?>
