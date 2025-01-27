// Get references to DOM elements
const calendarBody = document.getElementById('calendar-body');
const monthAndYear = document.getElementById('monthAndYear');
const monthSelect = document.getElementById('monthSelect');
const previousButton = document.getElementById('previous');
const nextButton = document.getElementById('next');

const currentDate = new Date();
let currentMonth = currentDate.getMonth();
let currentYear = currentDate.getFullYear();

// Event listeners for navigation
previousButton.addEventListener('click', () => changeMonth(-1));
nextButton.addEventListener('click', () => changeMonth(1));
monthSelect.addEventListener('change', () => {
    currentMonth = parseInt(monthSelect.value);
    renderCalendar(currentMonth, currentYear);
});

// Fetch tasks from the backend (PHP)
async function fetchTasks(month, year) {
    const response = await fetch(`loadTasks.php?month=${month + 1}&year=${year}`);
    return await response.json();
}

// Load tasks and render the calendar with events
async function loadTasks() {
    const tasksFromDb = await fetchTasks(currentMonth, currentYear);

    // Organize tasks by date
    const tasks = tasksFromDb.reduce((acc, task) => {
        const taskDate = new Date(task.dueDate).getDate();
        if (!acc[taskDate]) {
            acc[taskDate] = [];
        }
        acc[taskDate].push(task.task);
        return acc;
    }, {});

    renderCalendar(currentMonth, currentYear, tasks);
}

// Render the calendar for the current month and year
function renderCalendar(month, year, tasks = {}) {
    // Clear previous calendar
    calendarBody.innerHTML = '';

    // Update header
    monthAndYear.textContent = `${new Date(year, month).toLocaleString('default', { month: 'long' })} ${year}`;
    monthSelect.value = month;

    // Get the first and last day of the month
    const firstDay = new Date(year, month, 1).getDay();
    const daysInMonth = new Date(year, month + 1, 0).getDate();

    // Generate calendar rows
    let date = 1;
    for (let row = 0; row < 6; row++) {
        const weekRow = document.createElement('tr');

        for (let col = 0; col < 7; col++) {
            const cell = document.createElement('td');

            if (row === 0 && col < firstDay) {
                // Empty cell before the first day
                cell.textContent = '';
            } else if (date > daysInMonth) {
                // Empty cell after the last day
                cell.textContent = '';
            } else {
                // Date cell
                cell.textContent = date;
                cell.classList.add('date-picker');
                if (date === currentDate.getDate() && month === currentDate.getMonth() && year === currentDate.getFullYear()) {
                    cell.classList.add('today'); // Highlight the current day
                }

                // Add event markers if there are tasks for this date
                if (tasks[date]) {
                    tasks[date].forEach(task => {
                        const marker = document.createElement('div');
                        marker.classList.add('event-marker');
                        marker.title = `Task: ${task}`;
                        cell.appendChild(marker);
                    });
                }

                date++;
            }

            weekRow.appendChild(cell);
        }

        calendarBody.appendChild(weekRow);

        if (date > daysInMonth) break; // Stop if we finish all days
    }
}

// Change the month and re-render the calendar
async function changeMonth(delta) {
    currentMonth += delta;

    if (currentMonth < 0) {
        currentMonth = 11;
        currentYear--;
    } else if (currentMonth > 11) {
        currentMonth = 0;
        currentYear++;
    }

    await loadTasks(); // Re-fetch and render tasks for the new month
}

// Initial render
loadTasks();

