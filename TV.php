<?php
session_start();
$cache_file = "tv_cache.json";
$cache_time = 86400; // 24 hours cache

// Check if cache file exists and is less than 24 hours old
if (file_exists($cache_file) && (time() - filemtime($cache_file)) < $cache_time) {
    $data = json_decode(file_get_contents($cache_file), true);
} else {
    // our API key from TMDB
    $api_key = "c82ebf9717a12eb247071bb5ec8168f2";
    // URL for trending TV shows - this is a pre-built URL from TMDB API
    $api_url = "https://api.themoviedb.org/3/trending/tv/week?language=en-US&api_key=$api_key";
    $response = file_get_contents($api_url);

    if ($response !== false) {
        file_put_contents($cache_file, $response); // Cache the response
        $data = json_decode($response, true);
    } else {
        $data = ["error" => "Failed to fetch TV data"];
    }
}

// Output as array
$tv_shows = $data['results'] ?? [];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trending TV Shows</title>
    <link rel="stylesheet" href="movieStyle.css">
</head>
<body>

<h1>Trending TV Shows</h1>
<div id="tv-shows">
    <?php if (!empty($tv_shows)): ?>
        <?php foreach ($tv_shows as $show): ?>
            <div class="tv-show">
                <h2><?= htmlspecialchars($show['name']); ?></h2>
                <p>First Air Date: <?= (new DateTime($show['first_air_date']))->format('d-m-Y'); ?></p>
                <img src="https://image.tmdb.org/t/p/w200<?= htmlspecialchars($show['poster_path']); ?>" alt="<?= htmlspecialchars($show['name']); ?>">
                <p><strong>Rating:</strong> <?= number_format($show['vote_average'], 1); ?> ⭐ (<?= $show['vote_count']; ?> votes)</p>
                <p><strong>Popularity:</strong> <?= htmlspecialchars($show['popularity']); ?></p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No TV shows found.</p>
    <?php endif; ?>
</div>

</body>
</html>
