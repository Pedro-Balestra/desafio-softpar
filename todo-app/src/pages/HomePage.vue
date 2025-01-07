<template>
  <div>
    <h1>Lista de Tarefas</h1>
    <!-- Formulário para criar ou editar tarefas -->
    <TaskForm :task="selectedTask" @save-task="saveTask" />

    <!-- Filtro de tarefas -->
    <q-select
      v-model="selectedStatus"
      :options="['Todas', 'Em andamento', 'Concluído', 'Pendente']"
      label="Filtrar por Status"
      outlined
      class="q-mb-md"
    />

    <!-- Lista de tarefas filtradas -->
    <TaskList :tasks="filteredTasks" />
  </div>
</template>

<script>
import TaskForm from '../components/TaskForm.vue';
import TaskList from '../components/TaskList.vue';
import api from '../services/api.js';

export default {
  name: 'HomePage',
  components: {
    TaskForm,
    TaskList,
  },
  data() {
    return {
      tasks: [], // Lista de todas as tarefas
      selectedTask: null, // Tarefa selecionada para edição
      selectedStatus: 'Todas', // Status selecionado para o filtro
    };
  },
  created() {
    this.loadTasks(); // Carrega as tarefas ao criar o componente
  },
  computed: {
    // Computed para filtrar as tarefas com base no status selecionado
    filteredTasks() {
      if (this.selectedStatus === 'Todas') {
        return this.tasks; // Retorna todas as tarefas
      }
      return this.tasks.filter(task => task.status === this.selectedStatus); // Filtra pelo status
    },
  },
  methods: {
    async saveTask(task) {
      if (task.id) {
        // Atualiza tarefa existente
        await this.updateTask(task);
      } else {
        // Cria nova tarefa
        await this.createTask(task);
      }
      this.selectedTask = null;
    },
    async loadTasks() {
      try {
        const response = await api.getTasks(); // Obtém todas as tarefas da API
        this.tasks = response.data; // Armazena as tarefas recebidas na variável `tasks`
      } catch (error) {
        console.error('Erro ao carregar tarefas:', error);
      }
    },
    async createTask(task) {
      try {
        await api.createTask(task); // Cria uma nova tarefa
        this.loadTasks(); // Atualiza a lista de tarefas
      } catch (error) {
        console.error('Erro ao criar tarefa:', error);
      }
    },
    async updateTask(task) {
      try {
        await api.updateTask(task.id, task); // Atualiza a tarefa existente
        this.loadTasks(); // Atualiza a lista de tarefas
      } catch (error) {
        console.error('Erro ao atualizar tarefa:', error);
      }
    },
  },
};
</script>
