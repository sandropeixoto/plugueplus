<template>
  <section class="services">
    <h2>Guia de Serviços</h2>
    <div class="filters">
      <label>
        Categoria
        <select v-model="categoriaSelecionada">
          <option value="">Todas</option>
          <option v-for="categoria in categorias" :key="categoria.id" :value="categoria.nome">
            {{ categoria.nome }}
          </option>
        </select>
      </label>
    </div>

    <div class="grid">
      <article v-for="servico in servicosFiltrados" :key="servico.id">
        <h3>{{ servico.nome }}</h3>
        <p>{{ servico.descricao || 'Descrição não informada' }}</p>
        <ul>
          <li><strong>Categoria:</strong> {{ servico.categoria }}</li>
          <li v-if="servico.telefone"><strong>Telefone:</strong> {{ servico.telefone }}</li>
          <li v-if="servico.site"><a :href="servico.site" target="_blank">Site Oficial</a></li>
          <li v-if="servico.endereco"><strong>Endereço:</strong> {{ servico.endereco }}</li>
        </ul>
      </article>
    </div>
  </section>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue';
import api from '../services/api';

const categorias = ref([]);
const servicos = ref([]);
const categoriaSelecionada = ref('');

const servicosFiltrados = computed(() => {
  if (!categoriaSelecionada.value) return servicos.value;
  return servicos.value.filter((servico) => servico.categoria === categoriaSelecionada.value);
});

async function carregar() {
  const [categoriasRes, servicosRes] = await Promise.all([
    api.get('/categorias'),
    api.get('/servicos')
  ]);
  categorias.value = categoriasRes.data;
  servicos.value = servicosRes.data;
}

onMounted(carregar);
</script>

<style scoped>
.services {
  display: grid;
  gap: 2rem;
}

.filters {
  display: flex;
  gap: 1rem;
}

select {
  margin-left: 0.5rem;
  padding: 0.4rem 0.75rem;
  border-radius: 999px;
  border: 1px solid #cbd5e1;
}

.grid {
  display: grid;
  gap: 1.5rem;
}

article {
  background: #fff;
  padding: 1.5rem;
  border-radius: 12px;
  box-shadow: 0 8px 20px rgba(15, 23, 42, 0.08);
}

ul {
  list-style: none;
  padding: 0;
  margin: 0;
  display: grid;
  gap: 0.25rem;
}
</style>
