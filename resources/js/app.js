import './bootstrap.js';
import { createApp } from 'vue';
import dashboard from './components/dashboard.vue';

// import Echo from 'laravel-echo';
// import Pusher from 'pusher-js';

// window.Pusher = Pusher;

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: import.meta.env.VITE_PUSHER_APP_KEY,
//     cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
//     forceTLS: true
// });

const app = createApp({});
app.component('dashboard', dashboard);
app.mount('#app');
