<template>
  <div class="app-shell">
    <header class="app-header">
      <img src="/public/plugue.svg" alt="Plugue+" />
      <h1>Plugue+</h1>
      <nav>
        <RouterLink to="/">Feed</RouterLink>
        <RouterLink to="/mapa">Mapa</RouterLink>
        <RouterLink to="/servicos">Serviços</RouterLink>
        <RouterLink to="/perfil">Perfil</RouterLink>
        <RouterLink to="/login">Entrar</RouterLink>
      </nav>
    </header>
    <main class="app-main">
      <RouterView />
    </main>
    <footer class="app-footer">Plugue+ &copy; {{ new Date().getFullYear() }}</footer>
  </div>
</template>

<script setup>
import { RouterLink, RouterView } from 'vue-router';
</script>

<style scoped>
.app-shell {
  display: flex;
  min-height: 100vh;
  flex-direction: column;
}

.app-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 1rem 2rem;
  background: linear-gradient(90deg, #1b4965, #62b6cb);
  color: #fff;
}

.app-header img {
  width: 48px;
  height: 48px;
}

nav {
  display: flex;
  gap: 1rem;
}

nav a {
  color: #fff;
  font-weight: 600;
}

.app-main {
  flex: 1;
  padding: 2rem;
}

.app-footer {
  text-align: center;
  padding: 1rem;
  background: #0f172a;
  color: #e2e8f0;
}
</style>
