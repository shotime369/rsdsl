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
<title>User Account</title>
<style>
body {
  font-family: Arial, sans-serif;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  height: 100vh;
  background-color: #f4f4f4;
}
h2 {
  margin-bottom: 20px;
}
form {
  background: white;
  padding: 20px;
  border-radius: 10px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  width: 300px;
  margin-bottom: 20px;
}
label {
  display: block;
  margin-bottom: 10px;
  font-size: 18px;
}
input {
  width: 100%;
  padding: 10px;
  margin-bottom: 10px;
  border: 1px solid #ccc;
  border-radius: 5px;
  font-size: 16px;
}
button {
  width: 100%;
  padding: 10px;
  background: #007bff;
  color: white;
  border: none;
  border-radius: 5px;
  font-size: 16px;
  cursor: pointer;
}
button:hover {
  background: #0056b3;
}
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
