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

document.addEventListener("DOMContentLoaded", function() {
    var map = L.map('myMap2');

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    var locations = JSON.parse(document.getElementById('locationsForMap').textContent);
    var bounds = [];
    var routePoints = [];

    var startIcon = L.icon({
        iconUrl: 'img/start-point.png',
        iconSize: [80, 80],
        iconAnchor: [40, 55],
        popupAnchor: [0, -55]
    });
    
    var endIcon = L.icon({
        iconUrl: 'img/electric-motorcycle.png',
        iconSize: [50, 50],
        iconAnchor: [25, 50],
        popupAnchor: [0, -40]
    });

    if (locations.length >= 2) {
        locations.forEach(function(location) {
            routePoints.push([location.lat, location.lng]);
        });

        var totalDistanceKilometers = calculateTotalDistance(routePoints);

        var firstLocation = locations[0];
        var lastLocation = locations[locations.length - 1];

        var popupContentFirst = "<br><b>Motor ID:</b> " + firstLocation.motorName + "<br><b>Location:</b> " + firstLocation.name + "<br><b>Total Distance:</b> " + totalDistanceKilometers.toFixed(2) + " km";
        var markerFirst = L.marker([firstLocation.lat, firstLocation.lng], {icon: endIcon}).addTo(map)
            .bindPopup(popupContentFirst);
        bounds.push([firstLocation.lat, firstLocation.lng]);

        var popupContentLast = "<br><b>Motor ID:</b> " + lastLocation.motorName + "<br><b>Location:</b> " + lastLocation.name;
        var markerLast = L.marker([lastLocation.lat, lastLocation.lng], {icon: startIcon}).addTo(map)
            .bindPopup(popupContentLast);
        bounds.push([lastLocation.lat, lastLocation.lng]);
    }

    if (bounds.length > 0) {
        map.fitBounds(bounds);
    }

    if (routePoints.length > 1) {
        getRoute(routePoints, function(geometry) {
            var route = L.geoJSON(geometry, {
                style: { color: 'blue' }
            }).addTo(map);
            map.fitBounds(route.getBounds());
        });
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

    function calculateTotalDistance(points) {
        let totalDistance = 0;
        for (let i = 0; i < points.length - 1; i++) {
            const pointA = points[i];
            const pointB = points[i + 1];
            totalDistance += calculateDistance(pointA, pointB);
        }
        return totalDistance;
    }

    function calculateDistance(pointA, pointB) {
        const R = 6371; 
        const lat1 = pointA[0] * Math.PI / 180;
        const lat2 = pointB[0] * Math.PI / 180;
        const deltaLat = (pointB[0] - pointA[0]) * Math.PI / 180;
        const deltaLon = (pointB[1] - pointA[1]) * Math.PI / 180;

        const a = Math.sin(deltaLat / 2) * Math.sin(deltaLat / 2) +
                  Math.cos(lat1) * Math.cos(lat2) *
                  Math.sin(deltaLon / 2) * Math.sin(deltaLon / 2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

        return R * c;
    }

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

    function updateMotorOnView(motor) {
        console.log(`Motor ID: ${motor.id} updated on view`);
        motor.trackings.forEach(tracking => {
            let newPoint = [tracking.latitude, tracking.longitude];
            let newMarker = L.marker(newPoint, {
                icon: L.icon({
                    iconUrl: 'img/electric-motorcycle.png',
                    iconSize: [50, 50],
                    iconAnchor: [25, 50],
                    popupAnchor: [0, -50]
                })
            }).addTo(map); // Pastikan variabel map sudah didefinisikan sebelumnya
            getLocation(tracking.latitude, tracking.longitude, function(data) {
                let locationName = data.display_name || 'undefined';
                newMarker.bindPopup(`<b>Motor ID:</b> ${tracking.motor_id}<br><b>Location:</b> ${locationName}<br><b>Time:</b> ${new Date(tracking.created_at).toLocaleString()}`).openPopup();
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

        window.Echo.channel('motors')
        .listen('MonitorUpdated', (e) => {
            console.log('Event data:', e);
            console.log('Motor Updated:', e.motor);
            updateMotorOnView(e.motor);
            updateTrackingOnView(e.trackings);
        });
});