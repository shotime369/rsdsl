<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

$username = $_SESSION['username']; // Retrieve the stored username

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

// Get form data from notes.html
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $content = $_POST['content'];

    // Prepare SQL statement - notesID is set to auto-increment so isn't included, date_time also auto timestamp
   $stmt = $conn->prepare("INSERT INTO notes (title, content, username) VALUES (?, ?, ?)");
   //bind parameters - s is string
     $stmt->bind_param("sss", $title, $content, $username);

        // Execute the statement and check for success
        if ($stmt->execute()) {
                $message = "Note saved successfully!";
            } else {
                $message = "Failed to save the note: " . $stmt->error;
            }

            // Close the statement and the connection
            $stmt->close();
            $conn->close();

 // Pass the message to JavaScript and reload the page
    echo "<script>
        alert('" . addslashes($message) . "');
        window.location.href = 'notes.php';  // Redirect back to the notes page
    </script>";

                exit();

        }

?>