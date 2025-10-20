<template>
  <div class="app-shell" :class="{ 'menu-open': isMenuOpen }">
    <header class="app-header">
      <button class="menu-toggle" type="button" @click="toggleMenu" aria-label="Abrir menu de navegação">
        <span></span>
        <span></span>
        <span></span>
      </button>
      <div class="brand">
        <img src="/public/plugue.svg" alt="Plugue+" />
        <div>
          <h1>Plugue+</h1>
          <p class="tagline">Mobilidade elétrica na palma da mão</p>
        </div>
      </div>
      <nav :class="{ open: isMenuOpen }">
        <RouterLink to="/" @click="closeMenu">Feed</RouterLink>
        <RouterLink to="/mapa" @click="closeMenu">Mapa</RouterLink>
        <RouterLink to="/servicos" @click="closeMenu">Serviços</RouterLink>
        <RouterLink to="/perfil" @click="closeMenu">Perfil</RouterLink>
        <RouterLink to="/login" class="cta" @click="closeMenu">Entrar</RouterLink>
      </nav>
      <button v-if="isMenuOpen" class="menu-close" type="button" @click="closeMenu" aria-label="Fechar menu"></button>
    </header>
    <main class="app-main">
      <RouterView />
    </main>
    <footer class="app-footer">Plugue+ &copy; {{ new Date().getFullYear() }}</footer>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { RouterLink, RouterView } from 'vue-router';

const isMenuOpen = ref(false);

function toggleMenu() {
  isMenuOpen.value = !isMenuOpen.value;
}

function closeMenu() {
  isMenuOpen.value = false;
}
</script>

<style scoped>
.app-shell {
  display: flex;
  min-height: 100vh;
  flex-direction: column;
  background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 40%, #ffffff 100%);
}

.app-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 1rem 2rem;
  background: linear-gradient(90deg, #1b4965, #62b6cb);
  color: #fff;
  position: sticky;
  top: 0;
  z-index: 20;
}

.brand {
  display: flex;
  align-items: center;
  gap: 0.85rem;
}

.brand img {
  width: clamp(40px, 10vw, 52px);
  height: clamp(40px, 10vw, 52px);
}

.brand h1 {
  margin: 0;
  font-size: clamp(1.25rem, 2.6vw, 1.8rem);
}

.tagline {
  margin: 0;
  font-size: clamp(0.75rem, 2.4vw, 0.95rem);
  opacity: 0.85;
}

.menu-toggle {
  display: none;
  flex-direction: column;
  justify-content: center;
  gap: 6px;
  width: 44px;
  height: 44px;
  border: 1px solid rgba(255, 255, 255, 0.4);
  border-radius: 12px;
  background: rgba(15, 23, 42, 0.2);
  color: inherit;
  padding: 0;
}

.menu-toggle span {
  display: block;
  width: 22px;
  height: 2px;
  background: currentColor;
  border-radius: 1px;
  margin: 0 auto;
  transition: transform 0.3s ease;
}

nav {
  display: flex;
  gap: 1rem;
  align-items: center;
}

nav a {
  color: #fff;
  font-weight: 600;
  padding: 0.35rem 0.65rem;
  border-radius: 999px;
  transition: background 0.25s ease, color 0.25s ease;
}

nav a:hover,
nav a.router-link-active {
  background: rgba(15, 23, 42, 0.2);
}

nav a.cta {
  background: #facc15;
  color: #1b1b2f;
}

nav a.cta:hover,
nav a.cta.router-link-active {
  background: #fde047;
}

.app-main {
  flex: 1;
  padding: clamp(1rem, 5vw, 2.5rem);
}

.app-footer {
  text-align: center;
  padding: 1rem;
  background: #0f172a;
  color: #e2e8f0;
}

.menu-close {
  position: fixed;
  inset: 0;
  background: rgba(15, 23, 42, 0.55);
  border: none;
  cursor: pointer;
  z-index: 10;
}

@media (max-width: 960px) {
  .menu-toggle {
    display: flex;
  }

  .app-header {
    padding: 0.75rem 1rem;
    position: sticky;
  }

  nav {
    position: fixed;
    top: 0;
    right: 0;
    bottom: 0;
    width: min(280px, 80vw);
    padding: 5.5rem 1.75rem 2rem;
    flex-direction: column;
    justify-content: flex-start;
    align-items: flex-start;
    gap: 1.2rem;
    background: rgba(15, 23, 42, 0.96);
    transform: translateX(100%);
    transition: transform 0.3s ease;
    z-index: 20;
  }

  nav.open {
    transform: translateX(0);
  }

  nav a,
  nav a.cta {
    width: 100%;
    padding: 0.75rem 1rem;
    background: rgba(255, 255, 255, 0.08);
    color: #f8fafc;
  }

  nav a:hover,
  nav a.router-link-active {
    background: rgba(255, 255, 255, 0.18);
  }

  nav a.cta,
  nav a.cta.router-link-active {
    background: #facc15;
    color: #1b1b2f;
  }

  .brand {
    flex: 1;
  }

  .app-shell.menu-open {
    overflow: hidden;
  }
}
</style>
