<?php
session_start();
require 'includes/dbh.inc.php'; // Database connection file

//  user_id is stored in session after login
if (!isset($_SESSION['user_id'])) {
  header('Location: login.php'); // Redirect to login if not logged in
  exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details
$stmt = $pdo->prepare("SELECT username, email FROM users WHERE user_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['update_username'])) {
    $new_username = trim($_POST['username']);
    $stmt = $pdo->prepare("UPDATE users SET username = ? WHERE user_id = ?");
    $stmt->execute([$new_username, $user_id]);
  }

  if (isset($_POST['update_email'])) {
    $new_email = trim($_POST['email']);
    $stmt = $pdo->prepare("UPDATE users SET email = ? WHERE user_id = ?");
    $stmt->execute([$new_email, $user_id]);
  }

  header('Location: account.php'); // Refresh page after update
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="styleAccount.css">
</style>
</head>
<body>
<h2>User Account</h2>
<form method="post">
<label>Username:</label>
<input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>">
<button type="submit" name="update_username">Edit</button>
</form>

<form method="post">
<label>Email:</label>
<input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>">
<button type="submit" name="update_email">Edit</button>
</form>
</body>
</html>
