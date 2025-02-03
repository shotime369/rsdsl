<?php
$cache_file = "movies_cache.json";
$cache_time = 86400; // 24 hours

if (file_exists($cache_file) && (time() - filemtime($cache_file)) < $cache_time) {
    $data = json_decode(file_get_contents($cache_file), true);
} else {
    $api_key = "c82ebf9717a12eb247071bb5ec8168f2";
    $api_url = "https://api.themoviedb.org/3/movie/upcoming?api_key=$api_key&language=en-US";
    $response = file_get_contents($api_url);
    file_put_contents($cache_file, $response);
    $data = json_decode($response, true);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upcoming Movies</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; }
        .movie { margin: 20px; padding: 10px; border: 1px solid #ccc; display: inline-block; width: 250px; }
    </style>
</head>
<body>

<h1>Upcoming Movies</h1>
<div id="movies">
    <?php foreach ($data['results'] as $movie): ?>
        <div class="movie">
            <h2><?= $movie['title']; ?></h2>
            <p>Release Date: <?= $movie['release_date']; ?></p>
            <img src="https://image.tmdb.org/t/p/w200<?= $movie['poster_path']; ?>" alt="<?= $movie['title']; ?>">
        </div>
    <?php endforeach; ?>
</div>

</body>
</html>
