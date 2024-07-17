document.addEventListener('DOMContentLoaded', function() {
    var map = L.map('myMap').setView([0, 0], 18);

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
        fetch(`/api/countTrackings`)
            .then(response => response.json())
            .then(trackings => {
                console.log('Count Trackings:', trackings);

                for (let motorId in trackings) {
                    (function(motorId) {
                        let routePoints = trackings[motorId].map(tracking => [tracking.latitude, tracking.longitude, tracking.status, tracking.created_at]);

                        let totalDistanceKilometers = trackings[motorId][0]?.total_distance || 0;

                        console.log(`Total Distance for Motor ${motorId}:`, totalDistanceKilometers.toFixed(2), 'kilometers');

                        let bounds = routePoints.map(point => L.latLng(point[0], point[1]));

                        if (bounds.length > 0) {
                            map.fitBounds(bounds);
                        }

                        let endPoint = routePoints[routePoints.length - 1];

                        if (endPoint) {
                            var endIcon = L.icon({
                                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
                                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                                iconSize: [25, 41],
                                iconAnchor: [12, 41],
                                popupAnchor: [1, -34],
                                shadowSize: [41, 41]
                            });
                            var endMarker = L.marker([endPoint[0], endPoint[1]], {icon: endIcon}).addTo(map);
                            endMarker.bindPopup("Loading...");

                            endMarker.on('click', function() {
                                getLocation(endPoint[0], endPoint[1], function(data) {
                                    endMarker.getPopup().setContent(`<b>Motor ID:</b> ${motorId}<br><b>Location:</b> ${data.display_name}<br><b>Total Distance:</b> ${totalDistanceKilometers.toFixed(2)} kilometers`).openPopup();
                                });
                            });

                            // Zoom langsung ke titik ikon terakhir
                            map.setView([endPoint[0], endPoint[1]], 18);
                        }
                    })(motorId); // IIFE untuk mengisolasi scope setiap motorId
                }
            })
            .catch(error => console.error('Error loading the trackings:', error));
    }

    loadTrackings();

    function updateMotorOnView(motor) {
        console.log(`Motor ID: ${motor.id} updated on view`);
        motor.trackings.forEach(tracking => {
            getLocation(tracking.latitude, tracking.longitude, function(data) {
                let locationName = data.display_name || 'undefined';
            });
        });
    }    

    function updateTrackingOnView(trackings) {
        trackings.forEach(tracking => {
            let newPoint = [tracking.latitude, tracking.longitude];
            let newMarker = L.marker(newPoint, {
                icon: L.icon({
                    iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
                    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                    iconSize: [25, 41],
                    iconAnchor: [12, 41],
                    popupAnchor: [1, -34],
                    shadowSize: [41, 41]
                })
            }).addTo(map);
            getLocation(tracking.latitude, tracking.longitude, function(data) {
                let locationName = data.display_name || 'undefined';
                newMarker.bindPopup(`<b>Motor ID:</b> ${tracking.motor_id}<br><b>Location:</b> ${locationName}<br><b>Time:</b> ${new Date(tracking.created_at).toLocaleString()}`).openPopup();
            });
        });
    }
});
