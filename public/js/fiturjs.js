document.addEventListener("DOMContentLoaded", function() {
    const navbarNav = document.querySelector(".navbar-nav");
    const hamburgerMenu = document.querySelector("#hamburger-menu");
    const sidebar = document.querySelector(".sidebar");

    hamburgerMenu.addEventListener("click", function() {
        navbarNav.classList.toggle("active");
        sidebar.classList.toggle("active");
    });

    document.addEventListener("click", function(e) {
        if (!hamburgerMenu.contains(e.target) && !navbarNav.contains(e.target)) {
            navbarNav.classList.remove("active");
        }
    });

    feather.replace();

    function updateDateTime() {
        const now = new Date();
        document.getElementById("time").textContent = now.toLocaleTimeString();
        document.getElementById("date").textContent = now.toLocaleDateString();
    }
    setInterval(updateDateTime, 1000);

    function updateStatusInfo() {
        // Fungsi ini harus diisi sesuai dengan kebutuhan status yang ingin diperbarui
    }
    setInterval(updateStatusInfo, 30000);
});
