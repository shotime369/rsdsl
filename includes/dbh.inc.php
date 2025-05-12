<?php
// Database connection settings
$host = 'localhost';
$dbname = 'loginweb';
$user = 'root';
$pass = 'P@ssw0rd';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}