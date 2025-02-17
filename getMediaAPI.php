<?php
session_start();
$cache_file = "movies_cache.json";
$cache_time = 86400; // 24 hours

$today = new DateTime();  // Current date
$three_months_later = (new DateTime())->modify('+3 months')->format('Y-m-d');  // Date 3 months from now

if (file_exists($cache_file) && (time() - filemtime($cache_file)) < $cache_time) {
    $data = json_decode(file_get_contents($cache_file), true);
} else {
    $api_key = "c82ebf9717a12eb247071bb5ec8168f2";
    $api_url = "https://api.themoviedb.org/3/discover/movie?api_key=$api_key&language=en-GB&region=GB&primary_release_date.gte=" . $today->format('Y-m-d') . "&primary_release_date.lte=$three_months_later";
    $response = file_get_contents($api_url);
    file_put_contents($cache_file, $response);
    $data = json_decode($response, true);
}

$filtered_movies = $data['results'];

// Sort filtered movies by release date
usort($filtered_movies, function ($a, $b) {
    $dateA = new DateTime($a['release_date']);
    $dateB = new DateTime($b['release_date']);
    return $dateA <=> $dateB;
});

// Handle Add to Calendar action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_calendar'])) {
    $title = $_POST['title'];
    $release_date = $_POST['release_date'];

    $pdo = new PDO('mysql:host=localhost;dbname=loginweb', 'shona', '1234');
    $stmt = $pdo->prepare("INSERT INTO media (title, release_date, username) VALUES (:title, :release_date, :username)");
    $stmt->execute([
        'title' => $title,
        'release_date' => $release_date,
        'username' => $_SESSION['username']
    ]);

    if (!$stmt) {
        echo "<p style='color: red;'>Database error: " . $pdo->errorInfo()[2] . "</p>";
    } else {
        echo "<script>alert('Movie \"$title\" added to calendar!');</script>";

    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upcoming Movies</title>
     <link rel="stylesheet" href="movieStyle.css">
</head>
<body>

<h1>Upcoming Movies</h1>
<div id="movies">
    <?php foreach ($filtered_movies as $movie): ?>
        <div class="movie">
            <h2><?= htmlspecialchars($movie['title']); ?></h2>
            <p>Release: <?= (new DateTime($movie['release_date']))->format('d-m-Y'); ?></p>
            <img src="https://image.tmdb.org/t/p/w200<?= htmlspecialchars($movie['poster_path']); ?>" alt="<?= htmlspecialchars($movie['title']); ?>">
            <form method="post">
                <input type="hidden" name="title" value="<?= htmlspecialchars($movie['title']); ?>">
                <input type="hidden" name="release_date" value="<?= htmlspecialchars($movie['release_date']); ?>">
                <button type="submit" name="add_to_calendar">Add to Calendar</button>
            </form>
        </div>
    <?php endforeach; ?>
</div>

</body>
</html>
