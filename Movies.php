<?php
session_start();
$cache_file = "movies_cache.json";
$cache_time = 86400; // 24 hours

$today = new DateTime();  // Current date
$three_months_later = (new DateTime())->modify('+3 months')->format('Y-m-d');  // Date 3 months from now

// Use cache if it exists and is less than 24 hours old
if (file_exists($cache_file) && (time() - filemtime($cache_file)) < $cache_time) {
    $data = json_decode(file_get_contents($cache_file), true);
} else {
    // Our API key from TMDB
    $api_key = "c82ebf9717a12eb247071bb5ec8168f2";
    // The request URL for upcoming movies
    $api_url = "https://api.themoviedb.org/3/discover/movie?api_key=$api_key&language=en-GB&region=GB&primary_release_date.gte=" . $today->format('Y-m-d') . "&primary_release_date.lte=$three_months_later";
    $response = file_get_contents($api_url);

    // Check if response is false
    if ($response === false) {
        die("Failed to fetch data from TMDB API.");
    }

    // Log the response for debugging purposes
   // file_put_contents("api_response_log.txt", $response); // Logs the raw response to a file

    // Decode the response
    $data = json_decode($response, true);

    // Check if 'results' is in the response
    if (isset($data['results']) && is_array($data['results'])) {
        // Cache the response
        file_put_contents($cache_file, $response);
    } else {
        die("Invalid API response: no results found.");
    }
}

$filtered_movies = $data['results'] ?? null;

// Ensure $filtered_movies is an array before using usort
if (is_array($filtered_movies)) {
    // Sort filtered movies by release date
    usort($filtered_movies, function ($a, $b) {
        try {
            $dateA = new DateTime($a['release_date']);
        } catch (DateMalformedStringException $e) {

        }
        try {
            $dateB = new DateTime($b['release_date']);
        } catch (DateMalformedStringException $e) {

        }
        return $dateA <=> $dateB;
    });
} else {
    die("No movies found or failed to parse the movie data.");
}

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
    <?php if (is_array($filtered_movies) && count($filtered_movies) > 0): ?>
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
    <?php else: ?>
        <p>No upcoming movies found.</p>
    <?php endif; ?>
</div>

</body>
</html>

