<template>
  <section class="auth">
    <h2>Entrar no Plugue+</h2>
    <form @submit.prevent="login">
      <input v-model="loginForm.email" type="email" placeholder="Email" required />
      <input v-model="loginForm.senha" type="password" placeholder="Senha" required />
      <button type="submit">Entrar</button>
    </form>

    <h3>ou cadastre-se</h3>
    <form @submit.prevent="register">
      <input v-model="registerForm.nome" type="text" placeholder="Nome" required />
      <input v-model="registerForm.email" type="email" placeholder="Email" required />
      <input v-model="registerForm.senha" type="password" placeholder="Senha" required />
      <button type="submit">Criar conta</button>
    </form>
  </section>
</template>

<script setup>
import { reactive } from 'vue';
import { useRouter } from 'vue-router';
import api from '../services/api';

const router = useRouter();

const loginForm = reactive({ email: '', senha: '' });
const registerForm = reactive({ nome: '', email: '', senha: '' });

function persistAuth(data) {
  localStorage.setItem('plugueplus_token', data.token);
  localStorage.setItem('plugueplus_user', JSON.stringify(data.user));
}

async function login() {
  try {
    const { data } = await api.post('/auth/login', loginForm);
    persistAuth(data);
    router.push('/');
  } catch (error) {
    alert('Falha no login. Verifique as credenciais.');
  }
}

async function register() {
  try {
    const { data } = await api.post('/auth/register', registerForm);
    persistAuth(data);
    router.push('/');
  } catch (error) {
    alert('Não foi possível cadastrar.');
  }
}
</script>

<style scoped>
.auth {
  max-width: 420px;
  margin: 0 auto;
  display: grid;
  gap: 1.5rem;
}

form {
  display: grid;
  gap: 0.75rem;
  background: #fff;
  padding: 1.5rem;
  border-radius: 12px;
  box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
}

input {
  padding: 0.75rem;
  border-radius: 8px;
  border: 1px solid #cbd5e1;
}

button {
  padding: 0.75rem;
  border: none;
  background: #1b4965;
  color: #fff;
  border-radius: 999px;
  font-weight: 600;
}
</style>
