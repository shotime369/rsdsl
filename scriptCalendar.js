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
  monthAndYear.textContent = `${new Date(year, month).toLocaleString('default', { month: 'long' })} ${year}`;
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
        cell.textContent = date;
        cell.classList.add('date-picker');
        if (date === currentDate.getDate() && month === currentDate.getMonth() && year === currentDate.getFullYear()) {
          cell.classList.add('today');
        }

        // Add click event to select a date
        const cellDate = new Date(year, month, date);
        cell.addEventListener('click', () => {
          selectedDate = cellDate;
          openModal(selectedDate);
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
        taskItem.innerHTML = `<strong>${task.task}</strong><br>${task.details}`;
        taskListModal.appendChild(taskItem);
      });
    } else {
      taskListModal.innerHTML = '<p>No tasks for this day.</p>';
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
  const response = await fetch(`loadTasks.php?month=${date.getMonth() + 1}&year=${date.getFullYear()}`);
  const tasks = await response.json();
  const filteredTasks = tasks.filter(task => task.dueDate === date.toISOString().split('T')[0]);
  return filteredTasks;
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

// Initial render
renderCalendar(currentMonth, currentYear);
