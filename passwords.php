<?php
session_start();
include 'includes/dbh.inc.php';

// Encryption key
$hashed_password = $_SESSION['password_hash'];
$user_id = $_SESSION['user_id'];
$encryption_key = hash_pbkdf2("sha256", $hashed_password, $user_id, 100000, 32, true);

// Function to encrypt password
function encryptPassword($password, $key) {
    return openssl_encrypt($password, 'AES-256-CBC', $key, 0, substr($key, 0, 16));
}

// Add  new or update existing password
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $service_name = htmlspecialchars($_POST['service_name']);
    $login_name = htmlspecialchars($_POST['login_name']);
    $password = encryptPassword($_POST['password'], $encryption_key);


    if (!empty($service_name) && !empty($password) && !empty($login_name)) {

       if (isset($_POST['entry_id']) && !empty($_POST['entry_id'])){
            //Update an existing password
            $entry_id = intval($_POST['entry_id']);
            $stmt = $pdo->prepare("UPDATE passwords SET service_name = :service_name, login_name = :login_name, password_encrypted = :password WHERE entry_id = :entry_id AND user_id = :user_id");
            $stmt->execute(['entry_id' => $entry_id, 'service_name' => $service_name, 'login_name' => $login_name, 'password' => $password, 'user_id' => $user_id]);
        }    else {
                //Insert new password record
                $stmt = $pdo->prepare("INSERT INTO passwords (user_id, service_name, login_name, password_encrypted) VALUES (:user_id, :service_name, :login_name, :password)");
                $stmt->execute(['user_id' => $user_id, 'service_name' => $service_name, 'login_name' => $login_name, 'password' => $password]);
        }
    }
}



// Delete a password
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $pdo->prepare("DELETE FROM passwords WHERE entry_id = :id");
    $stmt->execute(['id' => $id]);
}

// Fetch all passwords for display (without the actual password)
$stmt = $pdo->prepare("SELECT entry_id, service_name FROM passwords WHERE user_id = :user_id");
$stmt->execute(['user_id' => $user_id]);
$passwords = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Manager</title>
    <link rel="stylesheet" type="text/css" href="css/passwstyles.css">

</head>
<body>
    <h1>Password Manager</h1>

    <form method="POST" action="">
        <div class="form-group">
            <label for="service_name">Website </label>
            <input type="text" id="service_name" name="service_name" required>
        </div>
        <div class="form-group">
            <label for="login_name">Login Name </label>
            <input type="text" id="login_name" name="login_name" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="text" id="password" name="password" required>
        </div>
        <button type="submit">Add Password</button>
    </form>

    <?php if (!empty($passwords)): ?>
        <h2> Your Passwords</h2>
        <table>
            <thead>
                <tr>
                    <th>Entry ID</th>
                    <th>Website</th>
                    <th>View</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($passwords as $entry): ?>
                    <tr>
                        <td><?= htmlspecialchars($entry['entry_id']) ?></td>
                        <td><?= htmlspecialchars($entry['service_name']) ?></td>
                        <td><button onclick="fetchPassword(<?= $entry['entry_id'] ?>)">View</button></td>
                        <td><a href="?delete=<?= $entry['entry_id'] ?>" onclick="return confirm('Are you sure?')">Delete</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <!-- Modal Form -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()"  style="position: absolute; top: 10px; right: 10px; cursor: pointer; font-size: 24px;">&times;</span>
            <div class="modal-header">Password Details</div>

            <form id="editForm" method="POST" action="passwords.php">
                <input type="hidden" id="entry_id" name="entry_id">

                <label for="modal_service_name">Website:</label>
                <input type="text" id="modal_service_name" name="service_name"  class="view-mode"  readonly>

                <label for="modal_login_name">Login Name:</label>
                <input type="text" id="modal_login_name" name="login_name"  class="view-mode"  readonly>

                <label for="modal_password">Password:</label>
                <input type="text" id="modal_password" name="password"  class="view-mode"  readonly>


            </form>

            <button class="edit-btn" onclick="enableEdit(this)" style="position: absolute; bottom: 10px; left: 50%; transform: translateX(-50%);">Edit </button>


        </div>
    </div>


    <script>
        function fetchPassword(entryId) {
        fetch(`get_password.php?entry_id=${entryId}`)
            .then(response => response.json())
           .then(data => {
               if (data.error) {
                 alert(data.error);
                } else {
                   document.getElementById('modal_service_name').value = data.service_name;
                  document.getElementById('modal_login_name').value = data.login_name;
                  document.getElementById('modal_password').value = data.password;

                  document.getElementById('entry_id').value = entryId;

                   document.getElementById('editModal').classList.add('active');
                  document.getElementById('modalOverlay').classList.add('active'); // Show overlay
              }
         })
           .catch(error => console.error('Error:', error));
        }



        function enableEdit(button) {
            event.preventDefault();

            // Get all input fields in the modal
            let serviceInput = document.getElementById('modal_service_name');
            let loginInput = document.getElementById('modal_login_name');
            let passwordInput = document.getElementById('modal_password');

            // Enable editing and add border styles
            serviceInput.removeAttribute('readonly');
            loginInput.removeAttribute('readonly');
            passwordInput.removeAttribute('readonly');

            serviceInput.classList.remove('view-mode');
            loginInput.classList.remove('view-mode');
            passwordInput.classList.remove('view-mode');

            serviceInput.classList.add('edit-mode');
            loginInput.classList.add('edit-mode');
            passwordInput.classList.add('edit-mode');

            // Change button text to "Update" and set it to submit the form
            button.innerText = "Update";
            button.setAttribute("onclick", "");
            button.setAttribute("type", "submit");
            button.setAttribute("form", "editForm");
        }



        function closeModal() {
             document.getElementById('editModal').classList.remove('active');
            document.getElementById('modalOverlay').classList.remove('active'); // Hide overlay
        }


    </script>

    <div id="modalOverlay" class="modal-overlay" onclick="closeModal()"></div>

</body>
</html>
