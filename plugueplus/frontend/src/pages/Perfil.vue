<template>
  <section class="profile">
    <h2>Meu Perfil</h2>
    <div v-if="usuario" class="card">
      <p><strong>Nome:</strong> {{ usuario.nome }}</p>
      <p><strong>Email:</strong> {{ usuario.email }}</p>
      <p><strong>Membro desde:</strong> {{ formatDate(usuario.criado_em) }}</p>
      <button @click="logout">Sair</button>
    </div>
    <p v-else>Você precisa fazer login para ver o perfil.</p>
  </section>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import api from '../services/api';

const usuario = ref(null);

function formatDate(date) {
  return date ? new Date(date).toLocaleDateString('pt-BR') : '-';
}

async function carregarPerfil() {
  try {
    const { data } = await api.get('/auth/me');
    usuario.value = data;
    localStorage.setItem('plugueplus_user', JSON.stringify(data));
  } catch (error) {
    const local = localStorage.getItem('plugueplus_user');
    if (local) {
      usuario.value = JSON.parse(local);
    }
  }
}

function logout() {
  localStorage.removeItem('plugueplus_user');
  localStorage.removeItem('plugueplus_token');
  usuario.value = null;
}

onMounted(carregarPerfil);
</script>

<style scoped>
.profile {
  max-width: 520px;
  margin: 0 auto;
  display: grid;
  gap: 1.5rem;
}

.card {
  background: #fff;
  padding: 1.5rem;
  border-radius: 12px;
  box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
}

button {
  margin-top: 1rem;
  padding: 0.75rem 1.5rem;
  border: none;
  border-radius: 999px;
  background: #ef4444;
  color: #fff;
  font-weight: 600;
}
</style>
