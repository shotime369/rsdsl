<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.html"); // Redirect to login page if not logged in
    exit();
}

$username = $_SESSION['username']; // Retrieve the stored username

// Database connection
$servername = "localhost";
$dbname = "loginweb";
$dbusername = "shona";
$dbpassword = "1234";

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch tasks
$tasks = [];
$tasks_sql = "SELECT task FROM tasks WHERE username = ? AND dueDate = CURDATE()";
$stmt = $conn->prepare($tasks_sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $tasks[] = $row['task'];
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="dashStyle.css">
</head>
<body style="margin-top: 50px;" >


<div class="box-welcome">
    <h1>Welcome <?php echo htmlspecialchars($username); ?>!</h1>
    <h2>Today's Date
    <span id="today"></span></h2>
</div>

<div class="grid-container">
<div class="box-blue">
    <h2>Today's Tasks</h2>
    <ul>
        <?php foreach ($tasks as $task): ?>
            <li><?php echo htmlspecialchars($task); ?></li>
        <?php endforeach; ?>
    </ul>
</div>

<div class="box-purple2">
    <h2>Password Reminders</h2>
    <p>password countdown?</p>
</div>

<div class="box-purple2">
        <h2>Box 3</h2>
        <p>box 3</p>
</div>

<div class="box-blue">
    <h2>Today's Media Releases</h2>
    <p>load from database</p>
</div>



</div>

<script>
    const d = new Date();
    document.getElementById("today").innerHTML = d.toLocaleDateString();
</script>

</body>
</html>
