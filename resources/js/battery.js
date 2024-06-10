import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
import './bootstrap';
import 'leaflet/dist/leaflet.css';

Pusher.logToConsole = true;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true,
    wsHost: import.meta.env.VITE_PUSHER_HOST || `ws-${import.meta.env.VITE_PUSHER_APP_CLUSTER}.pusher.com`,
    wsPort: import.meta.env.VITE_PUSHER_PORT || 80,
    wssPort: import.meta.env.VITE_PUSHER_PORT || 443,
    enabledTransports: ['ws', 'wss'],
});

document.addEventListener("DOMContentLoaded", function () {
    const rawBatteryPercentage = document.getElementById('batteryPercentage').textContent;
    const rawBatteryKilometers = document.getElementById('batteryKilometers').textContent;
    const batteryDisplays = document.querySelectorAll(".battery-display");
    const batteryErrors = document.querySelectorAll(".battery-error");
    const batteryPercentageElement = document.querySelector(".battery-percentage");
    const batteryKilometersElement = document.querySelector(".battery-kilometers");

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

    function toggleBatteryData(isAvailable, percentage, kilometers) {
        batteryDisplays.forEach((display, index) => {
            if (isAvailable && percentage !== 'N/A') {
                display.style.display = "block";
                updateBatteryDisplay(display, parseFloat(percentage));
                if (batteryErrors[index]) batteryErrors[index].style.display = "none";
            } else {
                display.style.display = "none";
                if (batteryErrors[index]) batteryErrors[index].style.display = "block";
            }
        });

        if (batteryPercentageElement){
            batteryPercentageElement.textContent = percentage !== 'N/A' ? `${percentage}%` : '-';
        }

        if (batteryKilometersElement){
            batteryKilometersElement.textContent = kilometers !== 'N/A' ? `${kilometers}km` : '-';
        }
    }

    if(rawBatteryPercentage !== 'N/A' && !isNaN(parseFloat(rawBatteryPercentage))) {
        toggleBatteryData(true, rawBatteryPercentage, rawBatteryKilometers);
    } else {
        toggleBatteryData(false, 'N/A', 'N/A');
    }

    window.Echo.channel('motors')
        .listen('MonitorUpdated', (e) => {
            const motor = e.motor;
            const latestBattery = motor.batteries.slice(-1)[0]; 
            if (latestBattery) {
                toggleBatteryData(true, latestBattery.percentage, latestBattery.kilometers);
            } else {
                toggleBatteryData(false, 'N/A', 'N/A');
            }
        });
});