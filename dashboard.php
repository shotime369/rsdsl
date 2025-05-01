<?php
session_start();
//simulated user for testing purposes only
//$_SESSION['username'] = 'testuser'; // comment this line out after testing

require 'includes/dbh.inc.php'; // Database connection file

if (!isset($_SESSION['username'])) {
    header("Location: index.html"); // Redirect to login page if not logged in
    exit();
}

$username = $_SESSION['username']; // Retrieve the stored username

// Fetch tasks
$tasks = [];
$tasks_sql = "SELECT task FROM tasks WHERE username = :username AND dueDate = CURDATE()";
$stmt = $pdo->prepare($tasks_sql);
$stmt->execute(['username' => $username]);

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $tasks[] = $row['task'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="styleDash.css">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>


<div class="box-welcome">
    <h1>Welcome <?php echo htmlspecialchars($username); ?></h1>
    <h3>Today's Date:
    <span id="today"></span></h3>
</div>

<div class="grid-container">
    <div class="box-blue">
        <h3>Today's Tasks</h3>
        <ul>
            <?php if (empty($tasks)): ?>
                <li>No tasks today</li>
            <?php else: ?>
                <?php foreach ($tasks as $task): ?>
                    <li><?php echo htmlspecialchars($task); ?></li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </div>

    <!-- Upcoming Media -->
    <div class="box-blue">
            <h3>Upcoming Media</h3>
            <div class="upcoming-media">
                <ul>
                    <?php
                    // Using PDO for fetching media
                    $media_sql = "SELECT title, release_date FROM media WHERE username = :username AND release_date >= CURDATE() ORDER BY release_date ASC LIMIT 3";
                    $stmt = $pdo->prepare($media_sql);  // Use $pdo instead of $conn
                    $stmt->execute(['username' => $username]);

                    if ($stmt->rowCount() > 0) {
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<li><strong>" . htmlspecialchars($row['title']) . "</strong> - " . date("d-m-Y", strtotime($row['release_date'])) . "</li>";
                        }
                    } else {
                        echo "<li>No upcoming media</li>";
                    }
                ?>
                </ul>
            </div>
    </div>

    <div class="box-purple2">
        <h3>Local Weather</h3>
            
        <!-- Search Bar & Button -->
        <div class="weather-search">
            <input type="text" id="city-input" placeholder="Enter city name">
            <button id="search-btn">Search</button>

        </div>
        <p id="city-display" style="margin-top: 10px; font-weight: bold;"></p>
        <!-- Weather Display -->
        <div id="weather" class="weather-container"></div>
    </div>

</div>

<script>
    /* Get the day for the welcome message */
    const d = new Date();
    document.getElementById("today").innerHTML = d.toLocaleDateString('en-GB');

    async function fetchWeather(lat = 57.13, lon = -2.09) {
        const url = `https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lon}&hourly=temperature_2m,weather_code&timezone=GMT&forecast_days=2`;
        try {
            const response = await axios.get(url);
            const data = response.data;
            const container = document.getElementById('weather');
            container.innerHTML = '';

            const now = new Date();
            const localTimeIndex = data.hourly.time.findIndex(t => new Date(t) >= now);

            data.hourly.time.slice(localTimeIndex, localTimeIndex + 12).forEach((time, index) => {
                const temp = data.hourly.temperature_2m[index];
                const code = data.hourly.weather_code[index];
                const icon = getWeatherIcon(code);
                container.innerHTML += `
                    <div class="weather-card">
                        <h4>${new Date(time).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}</h4>
                        <img src="${icon}" alt="Weather icon">
                        <p>${temp}°C</p>
                    </div>`;
            });
        } catch (error) {
            console.error('Failed to fetch weather data:', error);
        }
    }

    async function getCoordinates(city) {
        const url = `https://geocoding-api.open-meteo.com/v1/search?name=${city}&count=1&language=en&format=json`;
        try {
            const response = await axios.get(url);
            if (response.data.results && response.data.results.length > 0) {
                const { latitude, longitude, name, country } = response.data.results[0];
                document.getElementById('city-display').textContent = `${name}, ${country}`;
                fetchWeather(latitude, longitude);

            } else {
                alert('City not found. Please enter a valid city name.');
            }
        } catch (error) {
            console.error('Failed to fetch coordinates:', error);
        }
    }

    function getWeatherIcon(code) {
        const icons = {
            0: 'images/icons8-sun-50.png',
            1: 'images/icons8-haze-50.png',
            2: 'images/icons8-partly-cloudy-day-50.png',
            3: 'images/icons8-cloud-50.png',
            61: 'images/icons8-heavy-rain-50.png',
        };
        return icons[code] || 'images/icons8-partly-cloudy-day-50.png';
    }

    document.getElementById('search-btn').addEventListener('click', () => {
        const city = document.getElementById('city-input').value.trim();
        if (city) {
            getCoordinates(city);
        }
    });

    fetchWeather();
    setInterval(fetchWeather, 3600000); // Refreshes hourly
</script>

</body>
</html>
