<template>
  <div>
    <!-- <q-select
      v-model="selectedStatus"
      :options="['Todas', 'Em andamento', 'Concluído', 'Pendente']"
      label="Filtrar por Status"
      outlined
      class="q-mb-md"
    /> -->

    <q-list bordered padding>
      <q-item
        v-for="task in filteredTasks"
        :key="task.id"
        clickable
        @click="goToDetails(task.id)"
      >
        <q-item-section>
          <q-item-label>{{ task.titulo }}</q-item-label>
          <q-item-label caption>{{ task.status }}</q-item-label>
        </q-item-section>
      </q-item>
    </q-list>
  </div>
</template>

<script>
export default {
  props: ['tasks'],
  data() {
    return {
      selectedStatus: 'Todas',
    };
  },
  computed: {
    filteredTasks() {
      if (this.selectedStatus === 'Todas') {
        return this.tasks;
      }
      return this.tasks.filter(task => task.status === this.selectedStatus);
    },
  },
  methods: {
    goToDetails(id) {
      this.$router.push({ name: 'TaskDetails', params: { id } });
    },
  },
};
</script>
