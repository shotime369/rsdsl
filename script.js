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

// Render the calendar for the current month and year
function renderCalendar(month, year) {
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

                // Add event marker if needed (example logic)
                if (Math.random() > 0.8) { // Random marker for testing
                    const marker = document.createElement('div');
                    marker.classList.add('event-marker');
                    marker.title = `Event on ${date}`;
                    cell.appendChild(marker);
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
function changeMonth(delta) {
    currentMonth += delta;

    if (currentMonth < 0) {
        currentMonth = 11;
        currentYear--;
    } else if (currentMonth > 11) {
        currentMonth = 0;
        currentYear++;
    }

    renderCalendar(currentMonth, currentYear);
}

// Initial render
renderCalendar(currentMonth, currentYear);
