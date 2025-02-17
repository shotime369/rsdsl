<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.html"); // Redirect to login page if not logged in
    exit();
}

$username = $_SESSION['username']; // Retrieve the stored username

// Database connection
$servername = "localhost";
$dbname = "loginweb";
$dbusername = "shona";
$dbpassword = "1234";

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch tasks
$tasks = [];
$tasks_sql = "SELECT task FROM tasks WHERE username = ? AND dueDate = CURDATE()";
$stmt = $conn->prepare($tasks_sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $tasks[] = $row['task'];
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="dashStyle.css">
     <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body style="margin-top: 50px;" >


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

<div class="box-blue">
    <h3>Password Reminders</h3>
    <p>password countdown?</p>
</div>
<div class="box-purple2">
        <h3>Local Weather - Aberdeen</h3>
    <div id="weather" class="weather-container"></div>
</div>
</div>



<script>
    const d = new Date();
    document.getElementById("today").innerHTML = d.toLocaleDateString();

/*get one days weather forecast - lat and long set to aberdeen for now*/
    async function fetchWeather() {
      const url = 'https://api.open-meteo.com/v1/forecast?latitude=57.13&longitude=2.09&hourly=temperature_2m,weather_code&timezone=GMT&forecast_days=2';
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
              <h4>${new Date(time).toLocaleTimeString([], {hour: '2-digit', minute: '2-digit'})}</h3>
              <img src="${icon}" alt="Weather icon">
              <p>${temp}°C</p>
            </div>`;
        });
      } catch (error) {
        console.error('Failed to fetch weather data:', error);
      }
    }

    function getWeatherIcon(code) {
      const icons = {
        0: 'images/icons8-sun-50.png', // Clear
        1: 'images/icons8-haze-50.png', // Mainly clear
        2: 'images/icons8-partly-cloudy-day-50.png', // Partly cloudy
        3: 'images/icons8-cloud-50.png', // Overcast
        61: 'images/icons8-heavy-rain-50.png', // Rain
      };
      return icons[code] || 'images/icons8-partly-cloudy-day-50.png';
    }

    fetchWeather();
    setInterval(fetchWeather, 3600000); // Refresh every hour

</script>

</body>
</html>
