<template>
    <div>
        <q-select v-model="filterStatus" :options="['Pendente', 'Em andamento', 'Concluída', 'Todas']"
            label="Filtrar por Status" />
        <q-list>
            <q-item-label v-for="task in filteredTasks" :key="task.id" @click="viewTaskDetails(task)">
                <q-item>
                    <q-item-section>{{ task.title }}</q-item-section>
                    <q-item-section>{{ task.status }}</q-item-section>
                </q-item>
            </q-item-label>
        </q-list>
    </div>
</template>

<script>
export default {
    props: ['tasks'],
    data() {
        return {
            filterStatus: 'Todas',
        };
    },
    computed: {
        filteredTasks() {
            if (!Array.isArray(this.tasks)) {
                return []; // Se não for um array, retorne um array vazio
            }
            if (this.filterStatus === 'Todas') {
                return this.tasks;
            }
            return this.tasks.filter(task => task.status === this.filterStatus);
        },
    },
    methods: {
        viewTaskDetails(task) {
            console.log(task); // Verifique o conteúdo da tarefa
            this.$router.push({ name: 'task-details', params: { id: task.id } });
        },
    },
};
</script>
