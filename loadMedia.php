
<?php
session_start();

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

$month = $_GET['month'];
$year = $_GET['year'];
$username = $_SESSION['username'];

$sql = "SELECT title, release_date FROM media WHERE MONTH(release_date) = ? AND YEAR(release_date) = ? AND username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iis", $month, $year, $username);
$stmt->execute();
$result = $stmt->get_result();

$movies = [];
while ($row = $result->fetch_assoc()) {
    $movies[] = $row;
}

echo json_encode($movies);


