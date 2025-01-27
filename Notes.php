<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notes</title>
    <link rel="stylesheet" href="tabStyle.css">
</head>
<body>
<h1>Notes</h1>
<?php if (!empty($message)): ?>
<p class="message"><?= htmlspecialchars($message) ?></p>
<?php endif; ?>
<form method="POST" action="saveNote.php">
    <input type="text" name="title" placeholder="Note Title Required" required>
    <textarea name="content" placeholder="Write your note here..." rows="5" required></textarea>
    <button type="submit">Save Note</button>
</form>

<div class="output-box">
    <h2>Your Notes</h2>
    <table>
        <thead>
        <tr>
            <th>Date</th>
            <th>Title</th>
            <th>Content</th>
        </tr>
        </thead>
        <tbody>
        <?php include 'loadNotes.php'; ?>
        </tbody>
    </table>
</div>

</body>
</html>
</body>
</html>