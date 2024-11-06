document.getElementById('login-form').onsubmit = function(event) {
    event.preventDefault(); // Prevents the default form submission - currently switched off
    window.location.href = 'home.html';
};
