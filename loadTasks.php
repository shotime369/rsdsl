<?php

// Include database connection
require 'includes/dbh.inc.php';

// Get the month and year from query params or use current
$month = isset($_GET['month']) ? (int)$_GET['month'] : date('m');
$year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');

// Prepare date range for the given month
$startDate = "$year-$month-01";
$endDate = "$year-$month-" . date('t', strtotime($startDate));

// Prepare and execute SQL query using PDO
$sql = "SELECT task, details, dueDate FROM tasks WHERE dueDate BETWEEN :start AND :end ORDER BY dueDate";
$stmt = $pdo->prepare($sql);
$stmt->execute(['start' => $startDate, 'end' => $endDate]);

// Fetch results
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Return JSON response
header('Content-Type: application/json');
echo json_encode($tasks);

