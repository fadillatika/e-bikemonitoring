document.addEventListener("DOMContentLoaded", function () {
    const map = L.map('myMap2').setView([0, 0], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18
    }).addTo(map);

    async function fetchAndUpdateHistoricalData() {
        const motorID = document.getElementById("boxID").textContent.trim();
        const selectedDate = document.getElementById('datePicker').value;
        try {
            const response = await fetch(`/api/dataterakhir?motors_id=${motorID}&date=${selectedDate}`);
            const data = await response.json();

            // Menghapus layer sebelumnya dari peta
            map.eachLayer(layer => {
                if (layer instanceof L.Polyline || layer instanceof L.CircleMarker || layer instanceof L.LayerGroup) {
                    map.removeLayer(layer);
                }
            });

            // Memuat ulang tileLayer
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 18
            }).addTo(map);

            // Mengambil hanya 100 data terakhir
            const last100Data = data.tracking.slice(-100);

            if (last100Data.length > 0) {
                updateTracking(last100Data);
            } else {
                alert('Data historis tidak tersedia.');
            }
        } catch (error) {
            console.error('Error fetching historical data:', error);
        }
    }


    // Fungsi untuk memperbarui data tracking pada peta
    async function updateTracking(trackingData) {
        const polylineCoords = [];
        const markers = [];

        for (const tracking of trackingData) {
            const { latitude, longitude } = tracking;

            if (latitude !== undefined && longitude !== undefined) {
                const latLng = [latitude, longitude];
                const address = await getAddress(latitude, longitude);

                L.circleMarker(latLng, { color: 'green', radius: 5 }).addTo(map)
                    .bindPopup(address);

                polylineCoords.push(latLng);
                markers.push({ latitude, longitude, address });
            }
        }

        if (polylineCoords.length > 0) {
            const polyline = L.polyline(polylineCoords, { color: 'green' }).addTo(map);
            map.fitBounds(polyline.getBounds());
        }

        const totalDistance = trackingData.reduce((total, t) => total + (parseFloat(t.distance) || 0), 0);
        document.getElementById("totalDistance").textContent = totalDistance.toFixed(2) + " km";
    }

    document.getElementById('datePicker').addEventListener('change', function() {
        fetchAndUpdateHistoricalData();
    });

    fetchAndUpdateHistoricalData();

    setInterval(fetchAndUpdateData, 30000);

    async function fetchAndUpdateData() {
        const motorID = document.getElementById("boxID").textContent.trim();
        try {
            const response = await fetch(`/api/dataterakhir-hari-ini?motors_id=${motorID}`);
            const data = await response.json();

        } catch (error) {
            console.error('Error fetching latest data:', error);
        }
    }

    async function getAddress(latitude, longitude) {
        try {
            const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${latitude}&lon=${longitude}`);
            const data = await response.json();
            return data.display_name || 'Unknown Location';
        } catch (error) {
            console.error('Error getting address:', error);
            return 'Unknown Location';
        }
    }
});