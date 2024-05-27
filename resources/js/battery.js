import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
import './bootstrap';
import 'leaflet/dist/leaflet.css';
import L from 'leaflet';

document.addEventListener("DOMContentLoaded", function () {
    const rawBatteryPercentage = document.getElementById('batteryPercentage').textContent;
    const batteryDisplays = document.querySelectorAll(".battery-display");
    const batteryErrors = document.querySelectorAll(".battery-error");

    function updateBatteryDisplay(batteryDisplay, percentage) {
        const batteryIndicator = batteryDisplay.querySelector(".battery-indicator");
        if (!isNaN(percentage) && percentage !== 'N/A') {
            batteryIndicator.style.height = percentage + "%";
            if (percentage <= 20) {
                batteryIndicator.style.background = "linear-gradient(to right, red, orange)";
            } else if (percentage <= 49) {
                batteryIndicator.style.background = "linear-gradient(to right, orange, yellow)";
            } else {
                batteryIndicator.style.background = "linear-gradient(to right, green, lime)";
            }
        }
    }

    function toggleBatteryData(isAvailable) {
        batteryDisplays.forEach((display, index) => {
            if (isAvailable && rawBatteryPercentage !== 'N/A') {
                display.style.display = "block";
                updateBatteryDisplay(display, parseFloat(rawBatteryPercentage));
                if (batteryErrors[index]) batteryErrors[index].style.display = "none";
            } else {
                display.style.display = "none";
                if (batteryErrors[index]) batteryErrors[index].style.display = "block";
            }
        });
    }

    if(rawBatteryPercentage !== 'N/A' && !isNaN(parseFloat(rawBatteryPercentage))) {
        toggleBatteryData(true);
    } else {
        toggleBatteryData(false);
    }
});