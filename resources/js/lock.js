import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
import './bootstrap';
import 'leaflet/dist/leaflet.css';

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

window.Echo.channel('motors')
    .listen('MonitorUpdated', (e) => {
        console.log('Event data:', e); // Log data event yang diterima

        const lockStatusElement = document.querySelector('.wheel-lock h3');
        const lockButton = document.querySelector('#lockButton');
        const lockIcon = document.querySelector('.lock-icon');

        lockStatusElement.textContent = `Status: ${e.status ? 'Unlocked' : 'Locked'}`;
        lockButton.textContent = e.status ? 'ON' : 'OFF';
        lockButton.classList.toggle('on', e.status);
        lockButton.classList.toggle('off', !e.status);
        lockButton.setAttribute('data-status', e.status ? 'on' : 'off');

        lockIcon.innerHTML = e.status ? feather.icons.unlock.toSvg() : feather.icons.lock.toSvg();
        feather.replace();
    });
        
document.addEventListener('DOMContentLoaded', () => {
    feather.replace();
});