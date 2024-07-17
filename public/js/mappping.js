document.addEventListener("DOMContentLoaded", function () {
    const map = L.map('myMap2').setView([0, 0], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18
    }).addTo(map);

    async function fetchAndUpdateData() {
        const motorID = document.getElementById("boxID").textContent.trim();
        const selectedDate = document.getElementById('datePicker').value;
        try {
            const response = await fetch(`/api/dataterakhir?motors_id=${motorID}&date=${selectedDate}`);
            const data = await response.json();

            map.eachLayer(layer => {
                if (layer instanceof L.Polyline || layer instanceof L.CircleMarker || layer instanceof L.LayerGroup) {
                    map.removeLayer(layer);
                }
            });
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 18
            }).addTo(map);

            if (data.tracking && data.tracking.length > 0) {
                updateTracking(data.tracking);
            } else {
                alert('The history data is not available.');
            }
        } catch (error) {
            console.error('Error fetching latest data:', error);
        }
    }

    async function updateTracking(trackingData) {
        const polylineCoords = [];
        const markers = [];

        for (const tracking of trackingData) {
            const { latitude, longitude } = tracking;

            if (latitude !== undefined && longitude !== undefined) {
                const latLng = [latitude, longitude];
                const address = await getAddress(latitude, longitude);

                L.circleMarker(latLng, { color: 'blue', radius: 5 }).addTo(map)
                    .bindPopup(address);

                polylineCoords.push(latLng);
                markers.push({ latitude, longitude, address });
            }
        }

        if (polylineCoords.length > 0) {
            const polyline = L.polyline(polylineCoords, { color: 'blue' }).addTo(map);
            map.fitBounds(polyline.getBounds());
        }

        const totalDistance = trackingData.reduce((total, t) => total + (parseFloat(t.distance) || 0), 0);
        document.getElementById("totalDistance").textContent = totalDistance.toFixed(2) + " km";
    }

    function getAddress(latitude, longitude) {
        return fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${latitude}&lon=${longitude}`)
            .then(response => response.json())
            .then(data => data.display_name || 'Unknown Location')
            .catch(error => {
                console.error('Error getting address:', error);
                return 'Unknown location';
            });
    }

    document.getElementById('datePicker').addEventListener('change', function() {
        fetchAndUpdateData();
    });

    fetchAndUpdateData();
    setInterval(fetchAndUpdateData, 30000);
});
