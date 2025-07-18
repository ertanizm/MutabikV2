// Sidebar toggle for mobile
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('show');
}

// Close sidebar when clicking outside on mobile
document.addEventListener('click', function(event) {
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.querySelector('.sidebar-toggle');
    
    if (window.innerWidth <= 768) {
        if (!sidebar.contains(event.target) && !sidebarToggle.contains(event.target)) {
            sidebar.classList.remove('show');
        }
    }
});

// Menu item click handler
document.querySelectorAll('.menu-item').forEach(item => {
    item.addEventListener('click', function(e) {
        e.preventDefault();
        
        // Remove active class from all menu items
        document.querySelectorAll('.menu-item').forEach(menuItem => {
            menuItem.classList.remove('active');
        });
        
        // Add active class to clicked item
        this.classList.add('active');
        
        // Close sidebar on mobile after menu selection
        if (window.innerWidth <= 768) {
            document.getElementById('sidebar').classList.remove('show');
        }
    });
});

// Submenu toggle handler
document.querySelectorAll('.menu-toggle').forEach(toggle => {
    toggle.addEventListener('click', function(e) {
        e.preventDefault();
        const submenu = this.nextElementSibling;
        const toggleIcon = this.querySelector('.toggle-icon');

        // Toggle submenu visibility
        submenu.style.display = submenu.style.display === 'block' ? 'none' : 'block';

        // Toggle arrow icon
        toggleIcon.classList.toggle('fa-chevron-down');
        toggleIcon.classList.toggle('fa-chevron-up');
    });
});

// Animate progress rings on page load
window.addEventListener('load', function() {
    const progressRings = document.querySelectorAll('.progress-ring .progress');
    progressRings.forEach(ring => {
        const currentOffset = ring.style.strokeDashoffset;
        ring.style.strokeDashoffset = '251.2'; // Start at full offset for animation
        
        setTimeout(() => {
            ring.style.strokeDashoffset = currentOffset; // Animate to desired offset
        }, 500);
    });
});
