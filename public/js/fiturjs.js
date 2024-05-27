document.addEventListener("DOMContentLoaded", function() {
    const sidebar = document.querySelector('.sidebar');
    const sidebarToggle = document.querySelector('.hamburger');
    const body = document.body;

    function toggleSidebar() {
        body.classList.toggle('sidebar-open');
        body.classList.toggle('sidebar-closed');
        console.log('Sidebar toggled');
    }

    sidebarToggle.addEventListener('click', function(e) {
        e.stopPropagation(); 
        toggleSidebar();
    });

    document.addEventListener('click', function(e) {
        if (body.classList.contains('sidebar-open') && !sidebar.contains(e.target)) {
            toggleSidebar();
        }
    });

    sidebar.addEventListener('click', function(e) {
        e.stopPropagation();
    });

    feather.replace();
});