<template>
  <div class="task-edit-container">
    <h1 class="title">ToDo List</h1>
    <div class="task-edit-box">
      <TaskForm :task="selectedTask" @save-task="saveTask" />
      <div class="row q-col-gutter-x-md">
        <q-select
          v-model="selectedStatus"
          :options="['Todas', 'Em andamento', 'Concluído', 'Pendente']"
          label="Filtrar por Status"
          outlined
          class="q-mt-md col-6"
        />
        <q-select
          v-model="selectedOrder"
          :options="['Mais recente', 'Mais antiga']"
          label="Ordenar por Data"
          outlined
          class="q-mt-md col-6"
        />
      </div>
      <TaskList :tasks="filteredTasks" @delete-task="deleteTask" class="q-mt-md task-list-box" />
    </div>
  </div>
</template>

<script>
import TaskForm from '../components/TaskForm.vue'
import TaskList from '../components/TaskList.vue'
import api from '../services/api.js'

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
      selectedOrder: 'Mais recente', // Ordem selecionada para exibição (padrão: mais recente)
    }
  },
  created() {
    this.loadTasks() // Carrega as tarefas ao criar o componente
  },
  computed: {
    filteredTasks() {
      let filtered = this.tasks

      // Filtra pelo status (opcional, caso esteja usando o filtro de status)
      if (this.selectedStatus !== 'Todas') {
        filtered = filtered.filter((task) => task.status === this.selectedStatus)
      }

      // Ordena as tarefas pela data de criação com base na escolha do usuário
      if (this.selectedOrder === 'Mais recente') {
        return filtered.sort((a, b) => new Date(b.created_at) - new Date(a.created_at))
      } else if (this.selectedOrder === 'Mais antiga') {
        return filtered.sort((a, b) => new Date(a.created_at) - new Date(b.created_at))
      }

      return filtered
    },
  },
  methods: {
    async saveTask(task) {
      if (task.id) {
        // Atualiza tarefa existente
        await this.updateTask(task)
      } else {
        // Cria nova tarefa
        await this.createTask(task)
      }
      this.selectedTask = {
        titulo: '',
        status: '',
        descricao: '',
        categoria: '',
      }
    },
    async loadTasks() {
      try {
        const response = await api.getTasks() // Obtém todas as tarefas da API
        this.tasks = response.data // Armazena as tarefas recebidas na variável `tasks`
      } catch (error) {
        console.error('Erro ao carregar tarefas:', error)
      }
    },
    async createTask(task) {
      try {
        await api.createTask(task) // Cria uma nova tarefa
        this.loadTasks() // Atualiza a lista de tarefas
      } catch (error) {
        console.error('Erro ao criar tarefa:', error)
      }
    },
    async updateTask(task) {
      try {
        await api.updateTask(task.id, task) // Atualiza a tarefa existente
        this.loadTasks() // Atualiza a lista de tarefas
      } catch (error) {
        console.error('Erro ao atualizar tarefa:', error)
      }
    },
    async deleteTask(taskId) {
      try {
        await api.deleteTask(taskId)
        this.loadTasks() // Atualiza a lista de tarefas
        this.$q.notify({ type: 'positive', message: 'Tarefa excluída com sucesso!' })
      } catch (error) {
        console.error('Erro ao excluir a tarefa:', error)
        this.$q.notify({ type: 'negative', message: 'Erro ao excluir a tarefa.' })
      }
    },
  },
}
</script>

<style lang="scss" scoped>
.title {
  font-size: 72px; /* Tamanho da fonte */
  font-weight: bold; /* Peso da fonte */
  color: black; /* Cor do texto */
  text-align: center; /* Centraliza horizontalmente */
  text-transform: uppercase; /* Transforma o texto para maiúsculas */
  letter-spacing: 2px; /* Espaçamento entre letras */
  margin-bottom: 20px; /* Espaçamento inferior */
  margin-top: 0; /* Remove qualquer margem superior */
  text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
}
.task-edit-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: flex-start;
  width: 100%;
  min-height: 100vh;
  /* Para telas menores */
  background-color: $primary;
  /* Fundo azul */
  box-sizing: border-box;
}

.task-edit-box {
  background: white;
  border-radius: 12px;
  box-shadow: 10px 10px 10px rgba(0, 0, 0, 0.2);
  width: 90%;
  max-width: 600px;
  padding: 20px;
}
.task-list-box {
  background-color: #ffffff;
  /* Fundo branco */
  border-radius: 12px;
  /* Bordas arredondadas */
  box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
  /* Sombra leve */
  padding: 16px;
  /* Espaçamento interno */
  overflow-y: auto;
  /* Rolagem vertical */
  max-height: 60vh;
  /* Limita a altura máxima para 60% da tela */
  border: 1px solid #ccc;

  &::-webkit-scrollbar {
    width: 6px;
  }

  &::-webkit-scrollbar-track {
    background: transparent;
    /* Fundo invisível */
  }

  &::-webkit-scrollbar-thumb {
    background: #b0b0b0;
    /* Cor suave para a barra */
    border-radius: 3px;
    /* Bordas arredondadas */
  }

  &::-webkit-scrollbar-thumb:hover {
    background: #909090;
  }
}
</style>
