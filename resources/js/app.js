import './bootstrap.js';
import { createApp } from 'vue';
import MapComponent from './components/MapComponent.vue';

const app = createApp({});
app.component('map-component', MapComponent);
app.mount('#app');
