<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require 'mailer.php';

// Database connection settings
$servername = "localhost";
$username = "shona";
$password = "1234";
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
    $stmt = $conn->prepare("SELECT user_id, password_hash, email FROM users WHERE username = ?");
    
    //use the account ID to get the account info
    $stmt->bind_param("s", $input_username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $password_hash, $email);
        $stmt->fetch();

        // Verify the password
        if (password_verify($input_password, $password_hash)) {
            session_regenerate_id(true);

            // Generate OTP code (6 digits)
            $otp = rand(100000, 999999);
            $_SESSION['otp'] = $otp;
            $_SESSION['email'] = $email;
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $input_username;

            // Send OTP via email using PHPMailer and Mailtrap
            $subject = "Your OTP Code";
            $body = "Your OTP code is: <b>$otp</b>. Please enter this code to complete your login.";

            $result = sendEmail($email, $subject, $body);

            if ($result === true) {
                // Redirect to OTP verification page
                header("Location: verify.php");
                exit();
            } else {
                echo "Failed to send OTP email: ". htmlspecialchars($result);
            }
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

