/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';
// import Pusher from 'pusher-js';

// Pusher.logToConsole = true;

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: import.meta.env.VITE_PUSHER_APP_KEY,
//     cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
//     forceTLS: true,
//     wsHost: import.meta.env.VITE_PUSHER_HOST || `ws-${import.meta.env.VITE_PUSHER_APP_CLUSTER}.pusher.com`,
//     wsPort: import.meta.env.VITE_PUSHER_PORT || 80,
//     wssPort: import.meta.env.VITE_PUSHER_PORT || 443,
//     enabledTransports: ['ws', 'wss'],
// });

// window.Echo.channel('motors')
//     .listen('MonitorUpdate', (e) => {
//         console.log('Motor Updated:', e.motor);
//         updateMotorOnView(e.motor);
//         updateTrackingOnView(e.trackings);
//     });
// function updateMotorOnView(motor) {
//     // Logic to update view with new motor data
//     console.log(`Motor ID: ${motor.id} updated on view`);
// }
    
// function updateTrackingOnView(trackings) {
//     // Logic to update view with new tracking data
//     trackings.forEach(tracking => {
//         let newPoint = [tracking.latitude, tracking.longitude];
//         let newMarker = L.marker(newPoint, {
//             icon: L.icon({
//                 iconUrl: 'img/electric-motorcycle.png',
//                 iconSize: [50, 50],
//                 iconAnchor: [25, 50],
//                 popupAnchor: [0, -50]
//             })
//         }).addTo(map);
//         newMarker.bindPopup(`<b>Motor ID:</b> ${tracking.motor_id}<br><b>Location:</b> ${tracking.location_name}<br><b>Time:</b> ${tracking.created_at}`).openOn(map);
//     });
// }