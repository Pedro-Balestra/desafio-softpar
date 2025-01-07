<template>
  <div>
    <h1>Lista de Tarefas</h1>
    <TaskForm :task="selectedTask" @save-task="saveTask" />
    <TaskList :tasks="tasks" />
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
      tasks: [],
      selectedTask: null, // Tarefa selecionada para edição
    };
  },
  // HomePage.vue
  // HomePage.vue
  created() {
    const taskId = this.$route.params.id;
    if (taskId) {
      // Se houver um ID, carrega a tarefa específica
      this.loadTaskDetails(taskId);
    } else {
      // Se não houver ID, carrega todas as tarefas
      this.loadTasks();
    }
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
    },
    async loadTasks() {
      try {
        const response = await api.getTasks(); // Obtém todas as tarefas da API
        this.tasks = response.data; // Armazena as tarefas recebidas na variável `tasks`
      } catch (error) {
        console.error('Erro ao carregar tarefas:', error);
      }
    },
    async loadTaskDetails(id) {
      try {
        const response = await api.fetchTaskById(id); // Obtém a tarefa específica
        this.selectedTask = response.data; // Armazena a tarefa selecionada para edição
      } catch (error) {
        console.error('Erro ao carregar tarefa:', error);
      }
    },
    async createTask(task) {
      try {
        await api.createTask(task);  // Cria uma nova tarefa
        this.loadTasks();  // Atualiza a lista de tarefas
      } catch (error) {
        console.error('Erro ao criar tarefa:', error);
      }
    },
    async updateTask(task) {
      try {
        await api.updateTask(task.id, task);  // Atualiza a tarefa existente
        this.loadTasks();  // Atualiza a lista de tarefas
      } catch (error) {
        console.error('Erro ao atualizar tarefa:', error);
      }
    },
  },

};
</script>
