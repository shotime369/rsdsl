<?php
session_start();

// Include database connection file
require 'includes/dbh.inc.php';

// Ensure user is logged in
if (!isset($_SESSION['username'])) {
    die("You must be logged in to view this data.");
}

$month = isset($_GET['month']) ? intval($_GET['month']) : date('m');
$year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');
$username = $_SESSION['username'];

try {
    // Prepare SQL to fetch movies for the logged-in user
    $sql = "SELECT title, release_date FROM media WHERE MONTH(release_date) = :month AND YEAR(release_date) = :year AND username = :username";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':month', $month, PDO::PARAM_INT);
    $stmt->bindParam(':year', $year, PDO::PARAM_INT);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->execute();

    // Fetch results
    $movies = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return JSON response
    echo json_encode($movies);

} catch (PDOException $e) {
    echo json_encode(["error" => "Failed to fetch movies: " . $e->getMessage()]);
}
?>



