<template>
  <div class="task-list">
    <q-list bordered padding>
      <q-item
        v-for="task in filteredTasks"
        :key="task.id"
        clickable
        @click="goToDetails(task.id)"
        class="task-item"
      >
        <q-item-section>
          <q-item-label class="task-title">{{ task.titulo }}</q-item-label>
          <q-item-label caption class="task-status">Status: {{ task.status }}</q-item-label>
          <q-item-label caption class="task-category">Categoria: {{ task.categoria }}</q-item-label>
          <q-item-label caption class="task-time">
            Criado em: {{ formatDate(task.created_at) }}
          </q-item-label>
        </q-item-section>
        <q-item-section side>
          <q-btn flat round dense icon="delete" color="gray" @click.stop="deleteTask(task.id)" />
        </q-item-section>
      </q-item>
    </q-list>
  </div>
</template>

<script>
import dayjs from 'dayjs'

export default {
  props: ['tasks', 'reloadTasks'],
  data() {
    return {
      selectedStatus: 'Todas',
    }
  },
  computed: {
    filteredTasks() {
      if (this.selectedStatus === 'Todas') {
        return this.tasks
      }
      return this.tasks.filter((task) => task.status === this.selectedStatus)
    },
  },
  methods: {
    formatDate(date) {
      return dayjs(date).format('DD/MM/YYYY HH:mm:ss') // Formata a data em DD/MM/YYYY HH:mm:ss
    },
    goToDetails(id) {
      this.$router.push({ name: 'TaskDetails', params: { id } })
    },
    deleteTask(id) {
      this.$emit('delete-task', id) // Emite um evento para o componente pai realizar a exclusão
    },
  },
}
</script>

<style scoped>
.task-list {
  max-height: 40vh;
  overflow-y: auto;
  border-radius: 12px;
  border: 1px solid #ccc;
  padding: 8px;
  background-color: #ffffff;
}

.task-item {
  background-color: white;
  border-radius: 8px;
  box-shadow: 1px 4px 10px rgba(0, 0, 0, 0.15);
  margin-bottom: 8px;
  transition:
    background-color 0.3s ease,
    transform 0.3s ease;
}

.task-item:hover {
  background-color: #f9f9f9;
  transform: translateY(-2px);
}

.task-title {
  font-weight: bold;
  font-size: 16px;
  color: #333;
  text-transform: capitalize;
}

.task-status {
  font-size: 14px;
  color: #666;
  text-transform: capitalize;
}
</style>
