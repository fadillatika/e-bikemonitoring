document.addEventListener('DOMContentLoaded', function() {
    var map = L.map('myMap');

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    function getLocation(latitude, longitude, callback) {
        let key = `${latitude},${longitude}`;
        let cachedLocation = sessionStorage.getItem(key);

        if (cachedLocation) {
            callback(JSON.parse(cachedLocation));
        } else {
            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${latitude}&lon=${longitude}`)
                .then(response => response.json())
                .then(data => {
                    sessionStorage.setItem(key, JSON.stringify(data));
                    callback(data);
                })
                .catch(error => console.error('Error fetching location name:', error));
        }
    }

    function loadTrackings() {
        fetch('/api/trackings')
            .then(response => response.json())
            .then(trackings => {
                let bounds = [];

                trackings.forEach(tracking => {
                    var motorIcon = L.icon({
                        iconUrl: 'img/motor-icon.png',
                        iconSize: [50, 40],
                        iconAnchor: [25, 40],
                        popupAnchor: [0, -40]
                    });

                    var marker = L.marker([tracking.latitude, tracking.longitude], {icon: motorIcon}).addTo(map);
                    bounds.push(marker.getLatLng());

                    marker.on('click', function() {
                        getLocation(tracking.latitude, tracking.longitude, function(data) {
                            marker.bindPopup(`<b>Motor ID:</b> ${tracking.motors_id}<br><b>Location:</b> ${data.display_name}`).openPopup();
                        });
                    });
                });

                if (bounds.length > 0) {
                    map.fitBounds(bounds);
                }
            })
            .catch(error => console.error('Error loading the trackings:', error));
    }

    loadTrackings();
});