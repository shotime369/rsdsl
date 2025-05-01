const calendarBody = document.getElementById('calendar-body');
const monthAndYear = document.getElementById('monthAndYear');
const monthSelect = document.getElementById('monthSelect');
const previousButton = document.getElementById('previous');
const nextButton = document.getElementById('next');
const taskModal = document.getElementById('taskModal');
const overlay = document.getElementById('overlay');
const modalDate = document.getElementById('modalDate');
const taskForm = document.getElementById('taskForm');
const taskListModal = document.getElementById('taskListModal');

let currentDate = new Date();
let currentMonth = currentDate.getMonth();
let currentYear = currentDate.getFullYear();
let selectedDate = null;




// Event listeners
previousButton.addEventListener('click', () => changeMonth(-1));
nextButton.addEventListener('click', () => changeMonth(1));
monthSelect.addEventListener('change', () => {
  currentMonth = parseInt(monthSelect.value);
  renderCalendar(currentMonth, currentYear);
});
taskForm.addEventListener('submit', saveTask);

// Render the calendar
function renderCalendar(month, year) {
  calendarBody.innerHTML = '';
  monthAndYear.textContent = `${new Date(year, month).toLocaleString('default', {month: 'long'})} ${year}`;
  monthSelect.value = month;

  const firstDay = new Date(year, month, 1).getDay();
  const daysInMonth = new Date(year, month + 1, 0).getDate();

  let date = 1;
  for (let row = 0; row < 6; row++) {
    const weekRow = document.createElement('tr');
    for (let col = 0; col < 7; col++) {
      const cell = document.createElement('td');
      if (row === 0 && col < firstDay) {
        cell.textContent = '';
      } else if (date > daysInMonth) {
        cell.textContent = '';
      } else {
        // Create a span for the date number
        const dateSpan = document.createElement('span');
        dateSpan.classList.add('date'); //date class for styling
        dateSpan.textContent = date;

        // Append the date inside the cell
        cell.appendChild(dateSpan);
        cell.classList.add('date-picker');

        // Highlight today's date
        if (date === currentDate.getDate() && month === currentDate.getMonth() && year === currentDate.getFullYear()) {
          cell.classList.add('today');
        }

        // Add click event to select a date
        const cellDate = new Date(year, month, date);
        cell.addEventListener('click', () => {
          selectedDate = cellDate;
          openModal(selectedDate);
        });

        // Fetch media for this date
        fetchMediaForDate(cellDate).then(media => {
          if (media.length > 0) {
            const mediaList = document.createElement('div');
            mediaList.classList.add('media-list');
            media.forEach(media => {
              const mediaItem = document.createElement('div');
              mediaItem.classList.add('media-item');
              mediaItem.textContent = media.title;
              mediaList.appendChild(mediaItem);
            });
            cell.appendChild(mediaList);
          }
        });


        // Fetch and display tasks for this date
        fetchTasksForDate(cellDate).then(tasks => {
          if (tasks.length > 0) {
            const taskList = document.createElement('div');
            taskList.classList.add('task-list');
            tasks.forEach(task => {
              const taskItem = document.createElement('div');
              taskItem.classList.add('task-item');
              taskItem.textContent = task.task;
              taskList.appendChild(taskItem);
            });
            cell.appendChild(taskList);
          }
        });

        date++;
      }
      weekRow.appendChild(cell);
    }
    calendarBody.appendChild(weekRow);
    if (date > daysInMonth) break;
  }
}

// Open the task modal
function openModal(date) {
  modalDate.textContent = date.toDateString();
  document.getElementById('taskDueDate').value = date.toISOString().split('T')[0];

  // Fetch tasks for the selected date
  fetchTasksForDate(date).then(tasks => {
    taskListModal.innerHTML = '';
    if (tasks.length > 0) {
      tasks.forEach(task => {
        const taskItem = document.createElement('div');
        taskItem.classList.add('task-item-modal');

        taskItem.innerHTML = `
                <strong>${task.task}</strong>
                <p>${task.details}</p>
            `;


        taskListModal.appendChild(taskItem);
      });
    } else {
      taskListModal.innerHTML = '<p class="no-tasks">No tasks for this day.</p>';
    }
  });

  taskModal.classList.add('open');
  overlay.classList.add('open');
}

// Close the task modal
function closeModal() {
  taskModal.classList.remove('open');
  overlay.classList.remove('open');
}

// Fetch tasks for a specific date
async function fetchTasksForDate(date) {
  // Format date as YYYY-MM-DD in local time
  const localDateString = date.toLocaleDateString('en-CA');

  // Fetch tasks for the selected month and year
  const response = await fetch(`loadTasks.php?month=${date.getMonth() + 1}&year=${date.getFullYear()}`);
  const tasks = await response.json();

  // Filter tasks
  return tasks.filter(task => task.dueDate === localDateString);
}


// Save a new task
async function saveTask(event) {
  event.preventDefault();
  const task = document.getElementById('taskInput').value;
  const details = document.getElementById('taskDetails').value;
  const dueDate = document.getElementById('taskDueDate').value;

  const response = await fetch('saveTask.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: `task=${encodeURIComponent(task)}&details=${encodeURIComponent(details)}&dueDate=${encodeURIComponent(dueDate)}`,
  });

  if (response.ok) {
    alert('Task saved successfully!');
    closeModal();
    renderCalendar(currentMonth, currentYear); // Refresh the calendar
  } else {
    alert('Failed to save task.');
  }
}

// Change the month
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

async function fetchMediaForDate(date) {
  // Format date as YYYY-MM-DD in local time
  const localDateString = date.toLocaleDateString('en-CA'); // e.g., 2025-05-01

  // Fetch media for the selected month and year
  const response = await fetch(`loadMedia.php?month=${date.getMonth() + 1}&year=${date.getFullYear()}`);
  const media = await response.json();

  // Filter media
  return media.filter(item => item.release_date === localDateString);
}


// Initial render
renderCalendar(currentMonth, currentYear);
