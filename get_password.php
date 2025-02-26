<?php
session_start();
include 'includes/dbh.inc.php';

// Encryption key
$hashed_password = $_SESSION['password_hash'];
$user_id = $_SESSION['user_id'];
$encryption_key = hash_pbkdf2("sha256", $hashed_password, $user_id, 100000, 32, true);

if (isset($_GET['entry_id'])) {
    $entry_id = intval($_GET['entry_id']);
    $stmt = $pdo->prepare("SELECT service_name, login_name, password_encrypted FROM passwords WHERE entry_id = :entry_id");
    $stmt->execute(['entry_id' => $entry_id]);
    $password = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($password) {
        $password['password'] = openssl_decrypt($password['password_encrypted'], 'AES-256-CBC', $encryption_key, 0, substr($encryption_key, 0, 16));
        echo json_encode($password);
    } else {
        echo json_encode(['error' => 'Password not found']);
    }
}
?>
