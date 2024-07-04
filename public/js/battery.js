document.addEventListener("DOMContentLoaded", function () {
    const batteryPercentageElement = document.querySelector(".battery-percentage");
    const batteryKilometersElement = document.querySelector(".battery-kilometers");

    function fetchDataAndUpdateBattery() {
        fetch('/api/dataterakhir')
            .then(response => response.json())
            .then(data => {
                if (data) {
                    updateBatteryDisplay(data.percentage, data.kilometers);
                } else {
                    toggleBatteryData(false, 'N/A', 'N/A');
                }
            })
            .catch(error => {
                console.error('Error fetching latest battery data:', error);
            });
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
    setInterval(fetchDataAndUpdateBattery, 30000);
    fetchDataAndUpdateBattery();
});
