<?php
session_start();

//simulated user for testing purposes only
//$_SESSION['username'] = 'testuser'; // comment this line out after testing

// Redirect to login if not logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit();
}


require 'includes/dbh.inc.php'; // Database connection file
$username = $_SESSION['username']; // Retrieve username uncomment this line after testing

date_default_timezone_set('London'); // Set timezone to London

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Life Vault</title>
    <link rel="stylesheet" href="styleNavigation.css">
</head>
<body>
<!-- the javascript code for the menu tabs -->
<script src="scriptMenu.js"></script>

<!-- Navigation Menu -->
<div class="navbar">
    <img src="LVlogo.png" alt="LifeVault Logo" width="50px" height="50px">
    <span class="title">Life Vault</span>
    <a href="#home" onclick="showSection('home')">Home</a>
    <a href="#passwords" onclick="showSection('passwords')">Passwords</a>
    <a href="#calendar" onclick="showSection('calendar')">Calendar</a>
    <a href="#notes" onclick="showSection('notes')">Notes</a>
    <a href="#tasks" onclick="showSection('tasks')">Tasks</a>

    <!-- Dropdown for Media -->
    <div class="dropdown">
        <button onclick="dropFunction()" class="dropbtn">Media</button>
        <div id="myDropdown" class="dropdown-content">
            <a href="#movies" onclick="showSection('movies')">Movies</a>
            <a href="#tv" onclick="showSection('tv')">TV Shows</a>
        </div>
    </div>
    <a href="#account" onclick="showSection('account')">Account</a>

    <!-- Display session username and profile icon -->
    <?php
    if (isset($_SESSION['username'])):
        // Assuming you have a database connection already set up
        // Replace with your database query to fetch the user details
        $stmt = $pdo->prepare("SELECT user_icon, username FROM users WHERE username = :username");
        $stmt->execute(['username' => $_SESSION['username']]);
        $user = $stmt->fetch();

        if ($user): ?>
            <div class="user-profile">
                <!-- Display the user's profile icon from the database -->
                <img src="<?= htmlspecialchars($user['user_icon'] ?: 'images/profileicon/default-icon.png') ?>" alt="Profile Icon" class="profile-icon">
                <span class="username"><?= htmlspecialchars($user['username']) ?></span>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <a href="logout.php" class="logout-button">Logout</a>
</div>



<!-- content for each tab -->
<div class="content">
    <div id="home" class="section">
        <iframe src="dashboard.php" style="border:none;"></iframe>
    </div>

    <div id="passwords" class="section">
        <iframe src="passwords.php"  style="border:none"></iframe>
    </div>

    <div id="calendar" class="section">
        <iframe src="calendar.html"  style="border:none"></iframe>
    </div>

    <div id="notes" class="section">
        <iframe src="Notes.php" style="border:none"></iframe>
    </div>

    <div id="tasks" class="section">
        <iframe src="Tasks.html" style="border:none"></iframe>
    </div>

    <div id="movies" class="section">
        <iframe src="Movies.php" style="border:none"></iframe>
    </div>

    <div id="tv" class="section">
        <iframe src="TV.php" style="border:none"></iframe>
    </div>

    <div id="account" class="section">
        <iframe src="account.php" style="border:none"></iframe>
    </div>
</div>

</body>
</html>

