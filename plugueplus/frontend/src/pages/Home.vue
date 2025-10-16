<template>
  <section>
    <h2>Feed da Comunidade</h2>
    <form class="post-form" @submit.prevent="createPost">
      <input v-model="form.titulo" type="text" placeholder="Título" required />
      <textarea v-model="form.conteudo" placeholder="Compartilhe novidades..." required></textarea>
      <button type="submit">Publicar</button>
    </form>

    <article v-for="post in posts" :key="post.id" class="post">
      <header>
        <h3>{{ post.titulo }}</h3>
        <small>por {{ post.autor }} em {{ formatDate(post.criado_em) }}</small>
      </header>
      <p>{{ post.conteudo }}</p>

      <section class="comments">
        <h4>Comentários</h4>
        <p v-if="postComments(post.id).length === 0">Seja o primeiro a comentar!</p>
        <article v-for="comentario in postComments(post.id)" :key="comentario.id">
          <strong>{{ comentario.autor }}</strong>
          <span>{{ formatDate(comentario.criado_em) }}</span>
          <p>{{ comentario.conteudo }}</p>
        </article>
        <form @submit.prevent="createComment(post.id)">
          <textarea v-model="commentForm[post.id]" placeholder="Responder"></textarea>
          <button type="submit">Enviar</button>
        </form>
      </section>
    </article>
  </section>
</template>

<script setup>
import { onMounted, reactive, ref } from 'vue';
import api from '../services/api';

const posts = ref([]);
const comentarios = ref([]);
const form = reactive({ titulo: '', conteudo: '' });
const commentForm = reactive({});

function formatDate(value) {
  return new Date(value).toLocaleString('pt-BR');
}

function postComments(postId) {
  return comentarios.value.filter((c) => c.post_id === postId);
}

async function loadFeed() {
  const [postsRes, commentsRes] = await Promise.all([
    api.get('/posts'),
    api.get('/comentarios')
  ]);
  posts.value = postsRes.data;
  comentarios.value = commentsRes.data;
}

async function createPost() {
  try {
    await api.post('/posts', form);
    form.titulo = '';
    form.conteudo = '';
    await loadFeed();
  } catch (error) {
    alert('Faça login para publicar.');
  }
}

async function createComment(postId) {
  try {
    const conteudo = commentForm[postId];
    if (!conteudo) return;
    await api.post('/comentarios', { post_id: postId, conteudo });
    commentForm[postId] = '';
    await loadFeed();
  } catch (error) {
    alert('Faça login para comentar.');
  }
}

onMounted(loadFeed);
</script>

<style scoped>
section {
  display: grid;
  gap: 2rem;
}

.post-form {
  display: grid;
  gap: 0.75rem;
  background: #ffffff;
  padding: 1.5rem;
  border-radius: 12px;
  box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
}

.post-form input,
.post-form textarea {
  padding: 0.75rem;
  border: 1px solid #cbd5e1;
  border-radius: 8px;
  font: inherit;
}

.post-form button {
  align-self: flex-end;
  padding: 0.6rem 1.4rem;
  border-radius: 999px;
  background: #1b4965;
  color: #fff;
  border: none;
  font-weight: 600;
}

.post {
  background: #ffffff;
  padding: 1.5rem;
  border-radius: 12px;
  box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
}

.post header {
  margin-bottom: 1rem;
}

.comments {
  margin-top: 1.5rem;
  border-top: 1px solid #e2e8f0;
  padding-top: 1rem;
}

.comments form {
  display: grid;
  gap: 0.5rem;
}

.comments textarea {
  border-radius: 8px;
  border: 1px solid #cbd5e1;
  padding: 0.5rem;
}

.comments button {
  justify-self: flex-end;
  padding: 0.4rem 1rem;
  background: #62b6cb;
  border: none;
  border-radius: 999px;
  color: #0f172a;
  font-weight: 600;
}
</style>
