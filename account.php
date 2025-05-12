<?php
session_start();
require 'includes/dbh.inc.php'; // Database connection file

//  user_id is stored in session after login
if (!isset($_SESSION['user_id'])) {
  header('Location: index.html'); // Redirect to login if not logged in
  exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details
$stmt = $pdo->prepare("SELECT username, email, user_icon FROM users WHERE user_id = ?");
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

    if (isset($_POST['update_icon'])) {
        $selected_icon = $_POST['profile_icon']; // Get selected icon path from form
        $stmt = $pdo->prepare("UPDATE users SET user_icon = ? WHERE user_id = ?");
        $stmt->execute([$selected_icon, $user_id]);
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

</head>
<body>
<h2>User Account</h2>
<form class="narrow-form" method="post">
<label>Username:</label>
<input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>">
<button type="submit" name="update_username">Edit</button>
</form>

<form class="narrow-form"  method="post">
<label>Email:</label>
<input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>">
<button type="submit" name="update_email">Edit</button>
</form>

<!-- Profile Icon Selection Form -->
<form class="wide-form" method="post">
    <label>Icon:</label>
    <div class="icons-container">
        <?php
        // Define a set of available profile icons
        $icons = [
            'icons8-abraham-simpson-48.png',
            'icons8-bt21-koya-48.png',
            'icons8-grey-48.png',
            'icons8-hello-kitty-48.png',
            'icons8-huggy-wuggy-48.png',
            'icons8-iron-man-48.png',
            'icons8-mando-48.png',
            'icons8-super-mario-48.png',
            'icons8-totoro-48.png'
        ];
        // Display each icon as a clickable option
        foreach ($icons as $icon) {
            echo "<label class='icon-label'>
        <input type='radio' name='profile_icon' value='images/profileicon/$icon' " . ($user['user_icon'] === "images/profileicon/$icon" ? "checked" : "") . ">
        <img src='images/profileicon/$icon' alt='$icon' width='48' height='48'>
    </label>";
        }
        ?>
    </div>
    <button type="submit" name="update_icon">Update Icon</button>
</form>

<!-- Display current profile icon -->
<h4>Your Current Icon:</h4>
<?php if (!empty($user['user_icon'])): ?>
    <img src="<?php echo htmlspecialchars($user['user_icon']); ?>" alt="Profile Icon" width="100px" height="100px">
<?php else: ?>
    <p>No profile icon set.</p>
<?php endif; ?>


</body>
</html>
