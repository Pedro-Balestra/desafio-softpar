<template>
  <div>
    <q-form @submit="updateTask" class="q-mb-md">
      <q-input v-model="task.titulo" label="Título" outlined />
      <q-select v-model="task.status" :options="['Em andamento', 'Concluído', 'Pendente']" label="Status" outlined />
      <q-input v-model="task.descricao" label="Descrição" outlined />
      <q-btn type="submit" label="Salvar" color="primary"/>
    </q-form>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  props: ['id'],
  data() {
    return {
      task: {
        titulo: '',
        status: '',
        descricao: '',
      },
    };
  },
  created() {
    this.fetchTask();
  },
  methods: {
    async fetchTask() {
      try {
        const response = await axios.get(`http://127.0.0.1:8000/api/tarefas/${this.id}`);
        this.task = response.data;
      } catch (error) {
        console.error('Erro ao buscar a tarefa:', error);
      }
    },
    async updateTask() {
      try {
        // Definir valores padrão para evitar campos vazios
        this.task.titulo = this.task.titulo || 'Sem título';
        this.task.status = this.task.status || 'Pendente';
        this.task.descricao = this.task.descricao || 'Sem descrição';

        await axios.put(`http://127.0.0.1:8000/api/tarefas/${this.id}`, this.task);
        this.$q.notify({ type: 'positive', message: 'Tarefa atualizada com sucesso!' });
        this.$router.push('/');
      } catch (error) {
        console.error('Erro ao atualizar a tarefa:', error);
        this.$q.notify({ type: 'negative', message: 'Erro ao atualizar a tarefa.' });
      }
    }
  },
};
</script>
