<template>
  <div v-if="task">
    <h1>Detalhes da Tarefa</h1>
    <p><strong>Título:</strong> {{ task.title }}</p>
    <p><strong>Descrição:</strong> {{ task.description }}</p>
    <p><strong>Status:</strong> {{ task.status }}</p>
    <q-btn @click="editTask" label="Editar" />
    <q-btn to="/" label="Voltar" />
  </div>
  <div v-else>
    <p>Carregando...</p>
  </div>
</template>

<script>
import api from '../services/api.js';

export default {
  name: 'TaskDetails',
  data() {
    return {
      task: null,
    };
  },
  methods: {
    async loadTaskDetails() {
      const taskId = this.$route.params.id;
      try {
        this.task = await api.fetchTaskById(taskId); // Carrega os detalhes da tarefa
      } catch (error) {
        console.error('Erro ao carregar detalhes da tarefa:', error);
      }
    },
    editTask() {
      // Ao editar, você pode passar apenas o ID da tarefa
      this.$router.push({ name: 'Home', params: { id: this.task.id } });
    }
  }
};
</script>
