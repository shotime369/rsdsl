<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.html"); // Redirect to login page if not logged in
    exit();
}

$username = $_SESSION['username']; // Retrieve the stored username
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Styled Divs</title>
    <style>
        div {
            padding: 20px;
            margin: 10px;
            border-radius: 10px;
            color: white;
            font-family: Arial, sans-serif;
        }

        .box-purple {
            background-color: #9f82b7;
        }

        .box-blue {
            background-color: #9293d9;
        }
    </style>
</head>
<body style="margin-top: 50px;">

<div class="box-purple">
    <h1>Welcome <?php echo htmlspecialchars($username); ?>!</h1>
    <h2>Today's Date
    <span id="today"></span></h2>
</div>

<div class="box-blue">
    <h2>Today's Tasks</h2>
    <p>load from database</p>
</div>

<div class="box-purple">
    <h2>Password Reminders</h2>
    <p>Change your passwords regularly</p>
</div>

<div class="box-blue">
    <h2>Today's Media Releases</h2>
    <p>load from database</p>
</div>

<script>
    const d = new Date();
    document.getElementById("today").innerHTML = d.toLocaleDateString();
</script>

</body>
</html>
