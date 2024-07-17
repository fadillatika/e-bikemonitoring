document.addEventListener("DOMContentLoaded", function () {
    const batteryPercentageElement = document.querySelector(".battery-percentage");
    const batteryKilometersElement = document.querySelector(".battery-kilometers");
    const lockStatusText = document.querySelector("#lockStatusText");
    const lockIcon = document.querySelector("#lockIcon");
    const lockButton = document.querySelector("#lockButton");

    async function fetchAndUpdateData() {
        const motorID = document.getElementById("boxID").textContent.trim();
        try {
            const response = await fetch(`https://e-bikemonitoring.com/api/dataterakhir?motors_id=${motorID}`)
            const data = await response.json();

            console.log(data);

            if (data.battery) {
                updateBatteryDisplay(data.battery.percentage, data.battery.kilometers);
                checkBatteryConditions(data.battery.percentage);
            } else {
                toggleBatteryData(false, 'N/A', 'N/A');
            }

            if (data.lock) {
                updateLockStatus(data.lock.status);
                checkLockStatus(data.lock.status);
            } else {
                toggleLockData(false);
            }

        } catch (error) {
            console.error('Error fetching latest data:', error);
        }
    }

    function checkBatteryConditions (percentage) {
        if (percentage === 100) {
            showNotification ("Battery Full", "Battery is fully charged.")
        } else if (percentage === 20) {
            showNotification ("Battery Remaining 20%", "Battery remaining 20%. Please recharge soon.")
        } else if (percentage === 0) {
            showNotification ("Battery Empty", "Battery empty. Please recharge.")
        }
    }

    let lastLockStatus = null;
    function checkLockStatus(status) {
        const previousStatus = localStorage.getItem('lockStatus');
        if (previousStatus !== null && previousStatus !== status.toString()) {
            const message = status ? "Motor Unlocked." : "Motor Locked.";
            showNotification("Lock Status Change", message);
        }
        localStorage.setItem('lockStatus', status.toString());
    }

    let lastNotifiedDistance = 0;

    // async function checkLocationChange(trackingData, lockStatus) {
    //     const motorID = document.getElementById("boxID").textContent.trim();
    //     try {
    //         const response = await fetch(`/api/dataterakhir?motors_id=${motorID}`);
            
    //         if (!response.ok) {
    //             throw new Error(`HTTP error! Status: ${response.status}`);
    //         }
            
    //         const contentType = response.headers.get('content-type');
    //         if (!contentType || !contentType.includes('application/json')) {
    //             throw new TypeError('Expected JSON response from server');
    //         }
    
    //         const responseData = await response.json();
    
    //         const lastDistance = responseData.last_distance;
    
    //         if (lastDistance !== null && typeof lastDistance !== 'undefined') {
    //             const distance = parseFloat(lastDistance);
    
    //             if (!isNaN(distance) && distance >= 1 && distance > lastNotifiedDistance && !lockStatus) {
    //                 showNotification("Perubahan Lokasi", `Lokasi berubah sejauh ${distance.toFixed(2)} km.`);
    //                 lastNotifiedDistance = distance;
    //             }
    //         } else {
    //             throw new Error('Invalid or missing distance data in response');
    //         }
    
    //     } catch (error) {
    //         console.error('Error fetching last distance or processing location change:', error);
    //     }
    // }       

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

    function getAddress(latitude, longitude) {
        return fetch (`https://nominatim.openstreetmap.org/reverse?format=json&lat=${latitude}&lon=${longitude}`)
        .then(response => response.json())
        .then(data => data.display_name || 'Unknown Location')
        .catch(error => {
            console.error('Error getting address:', error);
            return 'Unknown location';
        });
    }

    askNotificationPermission();
    fetchAndUpdateData();
    setInterval(fetchAndUpdateData, 30000);
});

function askNotificationPermission() {
    if (!("Notification" in window)) {
        alert("Not Avilable For Notification.");
    } else if (Notification.permission !== "granted") {
        Notification.requestPermission().then(function(permission) {
            if (permission === "granted") {
                console.log("Notification permission granted.");
            }
        });
    }
}

function showNotification(title, body) {
    if (Notification.permission === "granted") {
        var notification = new Notification(title, {
            body: body,
            icon: "img/ebike-02.png"
        });

        notification.onclick = function(event) {
            event.preventDefault();
            window.open(window.location.href, '_blank');
        };
    }
}
