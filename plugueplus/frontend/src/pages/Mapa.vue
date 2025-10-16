<template>
  <section class="map-wrapper">
    <h2>Mapa de Carregadores</h2>
    <div ref="mapRef" class="map"></div>
  </section>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import L from 'leaflet';
import api from '../services/api';

const mapRef = ref(null);
let mapInstance;

async function loadMarkers() {
  const { data } = await api.get('/carregadores');
  data.forEach((carregador) => {
    const lat = carregador.latitude ?? -23.5505;
    const lng = carregador.longitude ?? -46.6333;
    L.marker([lat, lng])
      .addTo(mapInstance)
      .bindPopup(`
        <strong>${carregador.nome}</strong><br />
        ${carregador.endereco || 'Endereço não informado'}<br />
        Potência: ${carregador.potencia_kw || 'N/A'} kW
      `);
  });
}

onMounted(async () => {
  mapInstance = L.map(mapRef.value).setView([-23.5505, -46.6333], 11);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
  }).addTo(mapInstance);

  await loadMarkers();
});
</script>

<style scoped>
.map-wrapper {
  display: grid;
  gap: 1rem;
}

.map {
  height: 500px;
  border-radius: 16px;
  overflow: hidden;
  box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
}
</style>
