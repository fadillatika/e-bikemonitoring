const navbarNav = document.querySelector(".navbar-nav");
document.querySelector("#hamburger-menu").onclick = () => {
    navbarNav.classList.toggle("active");
};

// klik diluar sidebar
const hamburger = document.querySelector("#hamburger-menu");

document.addEventListener("click", function (e) {
    if (!hamburger.contains(e.target) && !navbarNav.contains(e.target)) {
        navbarNav.classList.remove("active");
    }
});

document.addEventListener("DOMContentLoaded", function () {

    const hamburger = document.getElementById("hamburger-menu");
    const sidebar = document.querySelector(".sidebar");

    hamburger.addEventListener("click", function () {
        // Toggle class untuk menunjukkan atau menyembunyikan sidebar
        sidebar.classList.toggle("active");
    });
});

// Fungsi untuk mengupdate waktu secara real-time
function updateDateTime() {
    const now = new Date();
    const formattedTime = now.toLocaleTimeString();
    const formattedDate = now.toLocaleDateString();
    document.getElementById("time").textContent = formattedTime;
    document.getElementById("date").textContent = formattedDate;
}

// Memanggil fungsi updateDateTime setiap detik
setInterval(updateDateTime, 1000);

// Placeholder untuk fungsi yang akan mengupdate informasi lokasi dan status lainnya
function updateStatusInfo() {
    // Update informasi di sini
}

// Memanggil fungsi updateStatusInfo setiap interval waktu tertentu
setInterval(updateStatusInfo, 30000); // Contoh: setiap 30 detik

// Example function to update speedometer
function updateSpeedometer(speed, range) {
    const speedValueElement = document.querySelector(".speed-value");
    const progressBarElement = document.querySelector(".progress");

    speedValueElement.textContent = speed; // Set speed
    progressBarElement.style.width = `${(range / 100) * 100}%`; // Set progress bar width
}

// Call this function with new values whenever you need to update the speedometer
updateSpeedometer(60, 52);

document.addEventListener("DOMContentLoaded", function() {
    feather.replace();
  });
