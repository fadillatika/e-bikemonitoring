import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
import './bootstrap';
import 'leaflet/dist/leaflet.css';
import L from 'leaflet';

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

document.addEventListener('DOMContentLoaded', function() {
    var map = L.map('myMap').setView([-6.2, 106.8], 12);

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

    function getRoute(points, callback) {
        const coordinates = points.map(p => `${p[1]},${p[0]}`).join(';');
        fetch(`https://router.project-osrm.org/route/v1/driving/${coordinates}?overview=full&geometries=geojson`)
            .then(response => response.json())
            .then(data => {
                if (data.routes && data.routes.length > 0) {
                    callback(data.routes[0].geometry);
                } else {
                    console.error('No route found');
                }
            })
            .catch(error => console.error('Error fetching route:', error));
    }

    function loadTrackings() {
        fetch('/api/countTrackings')
            .then(response => response.json())
            .then(trackings => {
                console.log('Count Trackings:', trackings);

                for (let motorId in trackings) {
                    (function(motorId) {
                        let routePoints = trackings[motorId].map(tracking => [tracking.latitude, tracking.longitude, tracking.status, tracking.created_at]);

                        // Urutkan titik berdasarkan waktu created_at
                        // routePoints.sort((a, b) => new Date(a.created_at) - new Date(b.created_at));

                        let totalDistanceKilometers = trackings[motorId][0]?.total_distance || 0;

                        console.log(`Total Distance for Motor ${motorId}:`, totalDistanceKilometers.toFixed(2), 'kilometers');

                        let bounds = routePoints.map(point => L.latLng(point[0], point[1]));

                        if (bounds.length > 0) {
                            map.fitBounds(bounds);
                        }

                        if (routePoints.length > 1) {
                            getRoute(routePoints.map(p => [p[0], p[1]]), function(geometry) {
                                var route = L.geoJSON(geometry, {
                                    style: { color: 'blue' }
                                }).addTo(map);
                                map.fitBounds(route.getBounds());
                            });
                        }

                        let startPoint = null;
                        let endPoint = null;
                        for (let i = 0; i < routePoints.length; i++) {
                            let point = routePoints[i];
                            if (point[2] === 'on') {
                                startPoint = [point[0], point[1]];
                            }
                            if (point[2] === 'off' || i === routePoints.length - 1) {
                                endPoint = [point[0], point[1]];
                            }
                        }

                        if (startPoint) {
                            var startMarker = L.marker(startPoint).addTo(map);
                            startMarker.bindPopup("Loading...");

                            startMarker.on('click', function() {
                                getLocation(startPoint[0], startPoint[1], function(data) {
                                    startMarker.getPopup().setContent(`<b>Motor ID:</b> ${motorId}<br><b>Location:</b> ${data.display_name}<br>`).openPopup();
                                });
                            });
                        }

                        if (endPoint) {
                            var endIcon = L.icon({
                                iconUrl: 'img/electric-motorcycle.png',
                                iconSize: [50, 50],
                                iconAnchor: [25, 50],
                                popupAnchor: [0, -50]
                            });
                            var endMarker = L.marker(endPoint, {icon: endIcon}).addTo(map);
                            endMarker.bindPopup("Loading...");

                            endMarker.on('click', function() {
                                getLocation(endPoint[0], endPoint[1], function(data) {
                                    endMarker.getPopup().setContent(`<b>Motor ID:</b> ${motorId}<br><b>Location:</b> ${data.display_name}<br><b>Total Distance:</b> ${totalDistanceKilometers.toFixed(2)} kilometers`).openPopup();
                                });
                            });
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
                    iconUrl: 'img/electric-motorcycle.png',
                    iconSize: [50, 50],
                    iconAnchor: [25, 50],
                    popupAnchor: [0, -50]
                })
            }).addTo(map);
            getLocation(tracking.latitude, tracking.longitude, function(data) {
                let locationName = data.display_name || 'undefined';
                newMarker.bindPopup(`<b>Motor ID:</b> ${tracking.motor_id}<br><b>Location:</b> ${locationName}<br><b>Time:</b> ${new Date(tracking.created_at).toLocaleString()}`).openPopup();
            });
        });
    }

    let currentRoute;

    window.Echo.channel('motors')
        .listen('MonitorUpdated', (e) => {
            console.log('Event data:', e);
            console.log('Motor Updated:', e.motor);

            if (currentRoute){
                map.removeLayer(currentRoute);
            }

            if (e.status === 'on') {
                startPoint = [e.motor.latitude, e.motor.longitude];
                if (startMarker) {
                    map.removeLayer(startMarker);
                }
                startMarker = L.marker(startPoint).addTo(map);
                startMarker.bindPopup("Start Point").openPopup();
            }
            updateTrackingOnView(e.trackings);

            if (e.trackings.length > 1){
                let routePoints = e.trackings.map(tracking=>[tracking.latitude, tracking.longitude]);
                getRoute(routePoints, function(geometry) {
                    currentRoute = L.geoJSON(geometry, {
                        style:{color: 'blue'}
                    }).addTo(map);
                    map.fitBounds(currentRoute.getBounds());
                });
            }
    });
});