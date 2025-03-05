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
    $notes = htmlspecialchars($_POST['notes']);
    $created_at = htmlspecialchars($_POST['created_at']);
    $updated_at = htmlspecialchars($_POST['updated_at']);

    if (!empty($service_name) && !empty($password) && !empty($login_name)) {

       if (isset($_POST['entry_id']) && !empty($_POST['entry_id'])){
            //Update an existing password
            $entry_id = intval($_POST['entry_id']);
            $stmt = $pdo->prepare("UPDATE passwords SET service_name = :service_name, login_name = :login_name, password_encrypted = :password, notes = :notes WHERE entry_id = :entry_id AND user_id = :user_id");
            $stmt->execute(['entry_id' => $entry_id, 'service_name' => $service_name, 'login_name' => $login_name, 'password' => $password, 'notes' => $notes, 'user_id' => $user_id]);
        }    else {
                //Insert new password record
                $stmt = $pdo->prepare("INSERT INTO passwords (user_id, service_name, login_name, password_encrypted, notes) VALUES (:user_id, :service_name, :login_name, :password, :notes)");
                $stmt->execute(['user_id' => $user_id, 'service_name' => $service_name, 'login_name' => $login_name, 'password' => $password, 'notes' => $notes ]);
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
$stmt = $pdo->prepare("SELECT entry_id, service_name, notes, created_at, updated_at FROM passwords WHERE user_id = :user_id");
$stmt->execute(['user_id' => $user_id]);
$passwords = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Determine sorting parameters from GET variables, with defaults
$sort = $_GET['sort'] ?? 'entry_id';
$order = $_GET['order'] ?? 'asc';
$order = ($order === 'desc') ? 'desc' : 'asc'; // basic validation

// Allow only specific columns for sorting
$allowedSort = ['entry_id', 'service_name', 'created_at', 'updated_at']; // ADDED: Allowed columns for sorting
if (!in_array($sort, $allowedSort)) {
    $sort = 'entry_id';
}

// Sort the $passwords array using usort()
if (!empty($passwords)) {
    usort($passwords, function($a, $b) use ($sort, $order) {
        if ($sort === 'entry_id') {
            $aVal = (int)$a[$sort];
            $bVal = (int)$b[$sort];
        } else {
            $aVal = strtolower($a[$sort]);
            $bVal = strtolower($b[$sort]);
        }
        if ($aVal == $bVal) {
            return 0;
        }
        return ($order === 'asc')
        ? (($aVal < $bVal) ? -1 : 1)
        : (($aVal > $bVal) ? -1 : 1);
    });
}


?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Manager</title>
    <link rel="stylesheet" type="text/css" href="css/passwstyles.css">
     <link rel="stylesheet" type="text/css" href="styleTab.css">

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
        <div class="form-group">
            <label for="notes">Notes</label>
            <input type="text" id="notes" name="notes" required>
        </div>
        <button type="submit">Add Password</button>
    </form>

    <?php if (!empty($passwords)): ?>
        <h2>
        Your Passwords</h2>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                    <th>
                        <!-- Toggle sorting: if currently sorting by entry_id asc, next click will be desc, and vice versa -->
                        <a href="?sort=entry_id&order=<?= ($sort == 'entry_id' && $order == 'asc') ? 'desc' : 'asc' ?>">EntryID</a>
                    </th>
                    <th>
                        <a href="?sort=service_name&order=<?= ($sort == 'service_name' && $order == 'asc') ? 'desc' : 'asc' ?>">Website</a>
                    </th>
                        <th>Notes</th>
                    <th>
                        <a href="?sort=created_at&order=<?= ($sort == 'created_at' && $order == 'asc') ? 'desc' : 'asc' ?>">Created</a>
                    </th>
                    <th>
                        <a href="?sort=updated_at&order=<?= ($sort == 'updated_at' && $order == 'asc') ? 'desc' : 'asc' ?>">Last Modified</a>
                    </th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($passwords as $entry): ?>
                        <tr>
                            <td><?= htmlspecialchars($entry['entry_id']) ?></td>
                            <td><?= htmlspecialchars($entry['service_name']) ?></td>
                            <td><?= htmlspecialchars($entry['notes']) ?></td>
                            <td><?= htmlspecialchars($entry['created_at']) ?></td>
                            <td><?= htmlspecialchars($entry['updated_at']) ?></td>
                            <td><button onclick="fetchPassword(<?= $entry['entry_id'] ?>)">View</button></td>
                            <td><button onclick="if(confirm('Are you sure?')) window.location.href='?delete=<?= $entry['entry_id'] ?>'">Delete</button></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

      <!-- Modal Form -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()" style="position: absolute; top: 10px; right: 10px; cursor: pointer; font-size: 24px;">&times;</span>
            <div class="modal-header">Password Details</div>

                <form id="editForm" method="POST" action="passwords.php" class="grid-form">
                    <input type="hidden" id="entry_id" name="entry_id">

                    <label for="modal_service_name">Website:</label>
                    <input type="text" id="modal_service_name" name="service_name" class="view-mode" readonly>

                    <label for="modal_login_name">Login Name:</label>
                    <input type="text" id="modal_login_name" name="login_name" class="view-mode" readonly>

                    <label for="modal_password">Password:</label>
                    <input type="text" id="modal_password" name="password" class="view-mode" readonly>

                    <!-- Notes field spans the full width -->
                    <label for="modal_notes" class="notes-label">Notes:</label>
                    <textarea id="modal_notes" name="notes" class="view-mode notes-input" readonly rows="3"></textarea>
                </form>

            <button class="edit-btn" onclick="enableEdit(this)" style="position: absolute; bottom: 10px; left: 50%; transform: translateX(-50%);">Edit</button>
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
                document.getElementById('modal_notes').value = data.notes;

                document.getElementById('entry_id').value = entryId;

                // Reset button text and behavior when opening modal
                let editButton = document.querySelector('.edit-btn');
                editButton.innerText = "Edit";
                editButton.setAttribute("onclick", "enableEdit(this)");
                editButton.removeAttribute("type");
                editButton.removeAttribute("form");

                // Ensure all inputs are in view mode when opening
                let inputs = document.querySelectorAll('.modal-content input, .modal-content textarea');
                inputs.forEach(input => {
                    input.setAttribute('readonly', true);
                    input.classList.remove('edit-mode');
                    input.classList.add('view-mode');
                });

                // Show modal
                document.getElementById('editModal').classList.add('active');
                document.getElementById('modalOverlay').classList.add('active');
            }
        })
        .catch(error => console.error('Error:', error));
    }




    function enableEdit(button) {
        event.preventDefault();

        // Enable input fields
        let inputs = document.querySelectorAll('.modal-content input, .modal-content textarea');
        inputs.forEach(input => {
            input.removeAttribute('readonly');
            input.classList.remove('view-mode');
            input.classList.add('edit-mode');
        });

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
