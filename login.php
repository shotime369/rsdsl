<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require 'includes/dbh.inc.php';
require 'mailer.php';

// Get form data from index.html
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input_username = $_POST['username'];
    $input_password = $_POST['password'];

    try {
        // Get the results from the database using PDO
        $stmt = $pdo->prepare("SELECT user_id, password_hash, email FROM users WHERE username = ?");
        $stmt->execute([$input_username]);

        // Check if user exists
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $user_id = $row['user_id'];
            $password_hash = $row['password_hash'];
            $email = $row['email'];

            // Verify the password
            if (password_verify($input_password, $password_hash)) {
                session_regenerate_id(true);

                // Generate OTP code (6 digits)
                $otp = rand(100000, 999999);
                $_SESSION['otp'] = $otp;
                $_SESSION['email'] = $email;
                $_SESSION['user_id'] = $user_id;
                $_SESSION['username'] = $input_username;
                $_SESSION['password_hash'] = $password_hash;


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
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
