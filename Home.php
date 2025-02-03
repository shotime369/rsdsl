<?php
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit();
}

$username = $_SESSION['username']; // Retrieve username
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Life Vault</title>
    <link rel="stylesheet" href="styleRSDSL.css">
</head>
<body>
<!-- the javascript code for the menu tabs -->
<script src="scriptRSDSL.js"></script>

<!-- menu tabs -->
<div class="navbar">
    <img src="LVlogo.png" alt="LifeVault Logo" width="50px" height="50px">
    <span class="title">Life Vault</span>
    <a href="#home" onclick="showSection('home')">Home</a>
    <a href="#passwords" onclick="showSection('passwords')">Passwords</a>
    <a href="#calendar" onclick="showSection('calendar')">Calendar</a> <!-- Redirects to calendar.html -->
    <a href="#notes" onclick="showSection('notes')">Notes</a>
    <a href="#tasks" onclick="showSection('tasks')">Tasks</a>
    <a href="#media" onclick="showSection('media')">Media</a>
    <a href="logout.php" class="logout-button">Logout</a>
</div>

<!-- content for each tab -->
<div class="content">
    <div id="home" class="section">
        <iframe src="dashboard.php" width="100%" height="100%" style="border:none; margin-top: 10px;"></iframe>
    </div>

    <div id="passwords" class="section" style="display: none;">
        <h2>Passwords</h2>
        <p>This is the Passwords section.</p>
    </div>

    <div id="calendar" class="section" style="display: none;">
        <iframe src="calendar.html"  width="100%" height="100%" style="border:none; margin-top: 10px;"></iframe>
    </div>

    <div id="notes" class="section" style="display: none;">
        <iframe src="Notes.php" width="100%" height="100%" style="border:none; margin-top: 10px;"></iframe>
    </div>

    <div id="tasks" class="section" style="display: none;">
        <iframe src="Tasks.html" width="100%" height="100%" style="border:none; margin-top: 10px;"></iframe>
    </div>

    <div id="media" class="section" style="display: none;">
        <iframe src="getMediaAPI.php" width="100%" height="100%" style="border:none; margin-top: 10px;"></iframe>
    </div>
</div>

</body>
</html>

