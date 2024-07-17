document.addEventListener("DOMContentLoaded", function () {
    const batteryPercentageElement = document.querySelector(".battery-percentage");
    const batteryKilometersElement = document.querySelector(".battery-kilometers");
    const lockStatusText = document.querySelector("#lockStatusText");
    const lockIcon = document.querySelector("#lockIcon");
    const lockButton = document.querySelector("#lockButton");

    async function fetchAndUpdateData() {
        const motorID = document.getElementById("boxID").textContent.trim();
        try {
            const response = await fetch(`/api/dataterakhir?motors_id=${motorID}`)
            const data = await response.json();

            console.log(data);

            if (data.battery) {
                updateBatteryDisplay(data.battery.percentage, data.battery.kilometers);
            } else {
                toggleBatteryData(false, 'N/A', 'N/A');
            }

            if (data.lock) {
                updateLockStatus(data.lock.status);
            } else {
                toggleBatteryData(false);
            }

        } catch (error) {
            console.error('Error fetching latest data:', error);
        }
    }

    function updateBatteryDisplay(percentage, kilometers) {
        const batteryDisplays = document.querySelectorAll(".battery-display");
        const batteryErrors = document.querySelectorAll(".battery-error");

        batteryDisplays.forEach((display, index) => {
            if (!isNaN(percentage) && percentage !== 'N/A') {
                display.style.display = "block";
                updateBatteryIndicator(display.querySelector(".battery-indicator"), percentage);
                if (batteryErrors[index]) batteryErrors[index].style.display = "none";
            } else {
                display.style.display = "none";
                if (batteryErrors[index]) batteryErrors[index].style.display = "block";
            }
        });

        if (batteryPercentageElement) {
            batteryPercentageElement.textContent = percentage !== 'N/A' ? `${percentage}%` : '-';
        }

        if (batteryKilometersElement) {
            batteryKilometersElement.textContent = kilometers !== 'N/A' ? `${kilometers}km` : '-';
        }
    }

    function updateBatteryIndicator(indicator, percentage) {
        indicator.style.height = percentage + "%";
        if (percentage <= 20) {
            indicator.style.background = "linear-gradient(to right, red, orange)";
        } else if (percentage <= 49) {
            indicator.style.background = "linear-gradient(to right, orange, yellow)";
        } else {
            indicator.style.background = "linear-gradient(to right, green, lime)";
        }
    }

    function toggleBatteryData(isAvailable, percentage, kilometers) {
        const batteryDisplays = document.querySelectorAll(".battery-display");
        const batteryErrors = document.querySelectorAll(".battery-error");

        batteryDisplays.forEach((display, index) => {
            display.style.display = isAvailable ? "block" : "none";
            if (!isAvailable && batteryErrors[index]) batteryErrors[index].style.display = "block";
        });

        if (batteryPercentageElement) {
            batteryPercentageElement.textContent = isAvailable ? `${percentage}%` : '-';
        }
        if (batteryKilometersElement) {
            batteryKilometersElement.textContent = isAvailable ? `${kilometers}km` : '-';
        }
    }

    function updateLockStatus(status) {
        if (lockStatusText) {
            lockStatusText.textContent = status ? 'Unlocked' : 'Locked';
        }

        if (lockIcon) {
            lockIcon.setAttribute('data-feather', status ? 'unlock' : 'lock');
            feather.replace();
        }

        if (lockButton) {
            lockButton.textContent = status ? 'ON' : 'OFF';
            lockButton.classList.toggle('on', status);
            lockButton.classList.toggle('off', !status);
            lockButton.setAttribute('data-status', status ? 'on' : 'off');
        }
    }

    fetchAndUpdateData();
    setInterval(fetchAndUpdateData, 30000);
});
