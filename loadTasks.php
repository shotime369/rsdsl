<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

// Include database connection
require 'includes/dbh.inc.php';

// Get the current user
$username = $_SESSION['username'];

// Get month and year from GET parameters or default to current
$month = isset($_GET['month']) ? (int)$_GET['month'] : date('m');
$year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');

// Format start and end dates for the given month
$startDate = sprintf("%04d-%02d-01", $year, $month);
$endDate = sprintf("%04d-%02d-%02d", $year, $month, date('t', strtotime($startDate)));

// Prepare and execute SQL query (assuming 'username' is stored in the 'tasks' table)
$sql = "SELECT task, details, dueDate FROM tasks
WHERE username = :username AND dueDate BETWEEN :start AND :end
ORDER BY dueDate";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    'username' => $username,
    'start' => $startDate,
    'end' => $endDate,
]);

$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Return JSON response
header('Content-Type: application/json');
echo json_encode($tasks);

