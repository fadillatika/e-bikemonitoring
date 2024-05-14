<template>
  <div ref="mapContainer" class="map-container"></div>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import L from 'leaflet';

const mapContainer = ref(null);

onMounted(() => {
  const map = L.map(mapContainer.value).setView([-6.2088, 106.8456], 10);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
  }).addTo(map);
});
</script>

<style scoped>
.map-container {
  height: 500px; /* Atur sesuai kebutuhan */
  width: 100%;
}
</style>