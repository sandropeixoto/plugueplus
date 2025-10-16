import { createRouter, createWebHistory } from 'vue-router';
import Home from '../pages/Home.vue';
import Mapa from '../pages/Mapa.vue';
import Servicos from '../pages/Servicos.vue';
import Login from '../pages/Login.vue';
import Perfil from '../pages/Perfil.vue';

const router = createRouter({
  history: createWebHistory(),
  routes: [
    { path: '/', component: Home },
    { path: '/mapa', component: Mapa },
    { path: '/servicos', component: Servicos },
    { path: '/login', component: Login },
    { path: '/perfil', component: Perfil }
  ]
});

export default router;
