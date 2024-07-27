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

            map.eachLayer(layer => {
                if (layer instanceof L.Polyline || layer instanceof L.CircleMarker || layer instanceof L.LayerGroup) {
                    map.removeLayer(layer);
                }
            });

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 18
            }).addTo(map);

            const last100Data = data.tracking.slice(-50);

            if (last100Data.length > 0) {
                updateTracking(last100Data);
            } else {
                alert('Road History Not Available.');
            }
        } catch (error) {
            console.error('Error fetching historical data:', error);
        }
    }


    // Fungsi untuk memperbarui data tracking pada peta
    async function updateTracking(trackingData) {
        const polylineCoords = [];
        const markers = [];

        for (let i = 0; i < trackingData.length; i++) {
            const tracking = trackingData[i];
            const { latitude, longitude, created_at, total_distance } = tracking;

            if (latitude !== undefined && longitude !== undefined) {
                const latLng = [latitude, longitude];
                const address = await getAddress(latitude, longitude);
                const date = new Date(created_at);
                const options = { year: 'numeric', month: '2-digit', day: '2-digit', hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false };
                const Time = date.toLocaleString('en-GB', options).replace(',', '');
                const popupContent = `Address: ${address}<br> Total trip: ${total_distance} km<br> Time: ${Time} WIB`;

                if (i === 0) { 
                    L.circleMarker(latLng, { color: 'green', radius: 5 }).addTo(map)
                        .bindPopup(popupContent);
                } else { 
                    L.circleMarker(latLng, { color: 'red', radius: 5 }).addTo(map)
                        .bindPopup(popupContent);
                }

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

    async function fetchAndUpdateData() {
        const motorID = document.getElementById("boxID").textContent.trim();
        try {
            const response = await fetch(`/api/dataterakhir?motors_id=${motorID}`)
            const data = await response.json();

            console.log(data);
            
            if (data && Array.isArray(data.tracking)) {
                updateTracking(data.tracking);
            }
        } catch (error) {
            console.error('Error fetching latest data:', error);
        }
    }
    
    setInterval(fetchAndUpdateData, 30000);

    fetchAndUpdateData();

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