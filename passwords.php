<?php
//// Start a session
session_start();

include 'includes/dbh.inc.php';


// Encryption key 
$hashed_password = $_SESSION['password_hash'];
$user_id = $_SESSION['user_id'];
//hash_pbkdf2() uses 100,000 iterations for security. Generates a 32-byte key directly.More secure against brute-force attacks than a simple hash.
$encryption_key = hash_pbkdf2("sha256", $hashed_password, $user_id, 100000, 32, true);  

// Encryption function
function encryptPassword($password, $key) {
    return openssl_encrypt($password, 'AES-256-CBC', $key, 0, substr($key, 0, 16));
}


// Decryption function
function decryptPassword($encryptedPassword, $key) {
    return openssl_decrypt($encryptedPassword, 'AES-256-CBC', $key, 0, substr($key, 0, 16));
}

// Add a new password
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $service_name = htmlspecialchars($_POST['service_name']);
    $login_name = htmlspecialchars($_POST['login_name']);
    $password = encryptPassword($_POST['password'], $encryption_key);
    $user_id = $_SESSION['user_id'];

    if (!empty($service_name) && !empty($login_name) && !empty($password)) {
        $stmt = $pdo->prepare("INSERT INTO passwords (user_id, service_name, login_name, password_encrypted) VALUES (:user_id,:service_name, :login_name, :password)");
        $stmt->execute(['user_id' =>$user_id,'service_name' => $service_name, 'login_name' => $login_name, 'password' => $password]);
    }
}

// Delete a password
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $pdo->prepare("DELETE FROM passwords WHERE entry_id = :id");
    $stmt->execute(['id' => $id]);
        
}

// Fetch all passwords for a user


$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT entry_id, service_name, login_name, password_encrypted FROM passwords WHERE user_id = :user_id");
$stmt->execute(['user_id' => $user_id]);

$passwords = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Password Manager</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f4f4f4; }
        .form-group { margin-bottom: 10px; }
        .form-group label { display: block; margin-bottom: 5px; }
    </style>
</head>
<body>
    <h1> Password Manager</h1>

    <form method="POST" action="">
        <div class="form-group">
            <label for="service_name"> Service Name</label>
            <input type="text" id="service_name" name="service_name" required>
        </div>
        <div class="form-group">
            <label for="login_name">Username</label>
            <input type="text" id="" name="login_name" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="text" id="password" name="password" required>
        </div>
        <button type="submit">Add Password</button>
    </form>

    <?php if (!empty($passwords)): ?>
        <table>
            <thead>
                <tr>
                    <th>Entry id</th>
                    <th>Service name</th>
                    <th>Username</th>
                    <th>Password</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($passwords as $entry): ?>
                    <tr>
                        <td><?= htmlspecialchars($entry['entry_id']) ?></td>
                        <td><?= htmlspecialchars($entry['service_name']) ?></td>
                        <td><?= htmlspecialchars($entry['login_name']) ?></td>
                        <td><?= htmlspecialchars(decryptPassword($entry['password_encrypted'], $encryption_key)) ?></td>
                        <td><a href="?delete=<?= $entry['entry_id'] ?>" onclick="return confirm('Are you sure?')">Delete</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>
