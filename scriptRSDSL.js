
    function showSection(sectionId) {
    // Hide the other tabs
    const sections = document.querySelectorAll('.section');
    sections.forEach(section => {
    section.style.display = 'none';
});

    // Show the selected tab
    document.getElementById(sectionId).style.display = 'block';
}
