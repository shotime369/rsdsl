<?php
session_start();

// Database connection settings
$servername = "localhost";
$username = "root";
$password = "P@ssw0rd"; 
$dbname = "loginweb";

// Connect to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data from index.html
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input_username = $_POST['username'];
    $input_password = $_POST['password'];


    //get the results from the database
    $stmt = $conn->prepare("SELECT user_id, password_hash FROM users WHERE username = ?");
    
    //use the account ID to get the account info
    $stmt->bind_param("s", $input_username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $password_hash);
        $stmt->fetch();

        // Verify the password
        if (password_verify($input_password, $password_hash)) {
            session_regenerate_id(true);

            // Login successful
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $input_username;
            echo "Login successful! Welcome, " . htmlspecialchars($input_username) . "!";
            //send the user to the home page on login
            header("Location: home.html");
            exit();
        } else {
            echo "Invalid username or password.";
        }
    } else {
        echo "Invalid username or password.";
    }

    $stmt->close();
}

$conn->close();
?>

