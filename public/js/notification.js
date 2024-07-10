document.addEventListener("DOMContentLoaded", function () {
    const batteryPercentageElement = document.querySelector(".battery-percentage");
    const batteryKilometersElement = document.querySelector(".battery-kilometers");
    const lockStatusText = document.querySelector("#lockStatusText");
    const lockIcon = document.querySelector("#lockIcon");
    const lockButton = document.querySelector("#lockButton");

    const map = L.map('myMap2').setView([0, 0], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18
    }).addTo(map);

    let polyline = L.polyline([], {color: 'blue'}).addTo(map);
    let startStopButton = document.getElementById("startStopButton");
    let resetButton = document.getElementById("resetButton");
    let tracking = JSON.parse(localStorage.getItem("tracking")) || false;
    let intervalId = null;

    var redIcon = new L.Icon({
        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
    });

    var blueIcon = new L.Icon({
        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
    });

    var greenIcon = new L.Icon({
        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
    });

    let initialMarker, updatedMarker, finishMarker;

    async function fetchAndUpdateData() {
        const motorID = document.getElementById("boxID").textContent.trim();
        try {
            const response = await fetch(`/api/dataterakhir?motors_id=${motorID}`)
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

            if (data.tracking) {
                await updateTracking(data.tracking, data.battery, data.lock);
                checkLocationChange(data.tracking);
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

    function checkLockStatus(status) {
        const previousStatus = localStorage.getItem('lockStatus');
        if (previousStatus !== null && previousStatus !== status.toString()) {
            const message = status ? "Motor Unlocked." : "Motor Locked.";
            showNotification("Lock Status Change", message);
        }
        localStorage.setItem('lockStatus', status.toString());
    }

    let lastNotifiedDistance = 0;
    async function checkLocationChange(trackingData) {
        const motorID = document.getElementById("boxID").textContent.trim();
    try {
        const response = await fetch(`/api/dataterakhir?motors_id=${motorID}`);
        
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            throw new TypeError('Expected JSON response from server');
        }

        const responseData = await response.json();

        const lastDistance = responseData.last_distance;

        if (lastDistance !== null && typeof lastDistance !== 'undefined') {
            const distance = parseFloat(lastDistance);

            if (!isNaN(distance) && distance >= 1 && distance > lastNotifiedDistance) {
                showNotification("Perubahan Lokasi", `Lokasi berubah sejauh ${distance.toFixed(2)} km.`);
                lastNotifiedDistance = distance;
            }
        } else {
            throw new Error('Invalid or missing distance data in response');
        }

    } catch (error) {
        console.error('Error fetching last distance or processing location change:', error);
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

    function toggleLockData(isAvailable) {
        if (!isAvailable) {
            lockStatusText.textContent = '-';
            lockIcon.setAttribute('data-feather', 'lock');
            feather.replace();
            lockButton.textContent = 'OFF';
            lockButton.classList.add('off');
            lockButton.classList.remove('on');
            lockButton.setAttribute('data-status', 'off');
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

    async function updateTracking(tracking, battery, lock) {
        const { latitude, longitude, distance = 0, total_distance = 0 } = tracking;
        const { percentage = 'N/A' } = battery || {};
        const { status = false } = lock || {};

        if (latitude !== undefined && longitude !== undefined) {
            const latLng = [latitude, longitude];
            const address = await getAddress(latitude, longitude);

            if (!initialMarker) {
                initialMarker = L.marker(latLng, {icon: redIcon}).addTo(map);
                initialMarker.bindPopup(`<b>Location:</b> ${address}<br>
                    <b>Battery:</b> ${percentage !== 'N/A' ? percentage + '%' : 'N/A'}<br>
                    <b>Lock Status:</b> ${status ? 'Unlocked' : 'Locked'}`);
            }

            if (updatedMarker) {
                map.removeLayer(updatedMarker);
            }

            updatedMarker = L.marker(latLng, {icon: blueIcon}).addTo(map);
            updatedMarker.bindPopup(`<b>Location:</b> ${address}<br>
                <b>Battery:</b> ${percentage !== 'N/A' ? percentage + '%' : 'N/A'}<br>
                <b>Lock Status:</b> ${status ? 'Unlocked' : 'Locked'}`);    

            polyline.addLatLng(latLng);
            map.fitBounds(polyline.getBounds());

            if (!tracking) { 
                finishMarker = L.marker(latLng, {icon: greenIcon}).addTo(map);
                finishMarker.bindPopup(`<b>Location:</b> ${address}<br>
                <b>Battery:</b> ${percentage !== 'N/A' ? percentage + '%' : 'N/A'}<br>
                <b>Lock Status:</b> ${status ? 'Unlocked' : 'Locked'}`);
            }

            document.getElementById("totalDistance").textContent = total_distance.toFixed(2) + " km";
        }
    }

    function startTracking() {
        if (!tracking) {
            intervalId = setInterval(fetchAndUpdateData, 10000);
            tracking = true;
            localStorage.setItem("tracking", JSON.stringify(tracking));
            startStopButton.textContent = "Stop Tracking";
            startStopButton.classList.add("tracking");
            startStopButton.classList.remove("not-tracking");
            resetButton.style.display = "none";
        }
    }

    function stopTracking() {
        if (tracking) {
            clearInterval(intervalId);
            intervalId = null;
            tracking = false;
            localStorage.setItem("tracking", JSON.stringify(tracking));
            startStopButton.textContent = "Start Tracking";
            startStopButton.classList.remove("tracking");
            startStopButton.classList.add("not-tracking");
            resetButton.style.display = "block";
        }
    }

    function resetTracking() {
        if (!tracking) {
            polyline.setLatLngs([]);
            if (initialMarker) {
                map.removeLayer(initialMarker);
                initialMarker = null;
            }
            if (updatedMarker) {
                map.removeLayer(updatedMarker);
                updatedMarker = null;
            }
            if (finishMarker) {
                map.removeLayer(finishMarker);
                finishMarker = null;
            }
            document.getElementById("totalDistance").textContent = "Total Distance: 0.00 km";
            resetButton.style.display = "none";
            localStorage.removeItem("tracking");
        }
    }

    startStopButton.addEventListener("click", function () {
        if (tracking) {
            stopTracking();
        } else {
            startTracking();
        }
    });

    resetButton.addEventListener("click", function () {
        resetTracking();
    });

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
