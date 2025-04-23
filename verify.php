<?php
//verify.php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input_otp = $_POST['otp'];

    if (isset($_SESSION['otp']) && $_SESSION['otp'] == $input_otp) {
        // OTP is valid, complete login
        $_SESSION['logged_in'] = true;
        // Optionally clear the OTP after successful verification
        unset($_SESSION['otp']);
        header("Location: home.php");
        exit();
    } else {
        $error = "Invalid OTP. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Verify OTP</title>
<link rel="stylesheet" href="indexstyles.css">
</head>
<body>
<div class="wave"></div>
<div class="login-container">
<img src="LVlogo.png" alt="LifeVault Logo" width="200px" height="200px" class="logoindex">
<h1 class="title">Enter OTP</h1>

<?php if (isset($error)) { echo "<p class='error-message'>$error</p>"; } ?>

<form id="otp-form" action="verify.php" method="post">
<div class="input-group">
<label for="otp">OTP Code</label>
<input type="text" id="otp" name="otp" required>
</div>
<button type="submit" class="login-button">Verify</button>
</form>
<img src="LVlogo.png" alt="LifeVault Logo" width="200px" height="200px" class="logoindex">
</div>
</body>
</html>
