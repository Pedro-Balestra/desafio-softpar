<template>
    <q-form @submit="submitForm" class="q-pa-md">
        <q-input v-model="localTask.title" label="Título" required class="q-mb-md" />
        <q-input v-model="localTask.description" label="Descrição" type="textarea" class="q-mb-md" />
        <!---<q-select v-model="localTask.status" :options="['Pendente', 'Em andamento', 'Concluída']" label="Status"
            class="q-mb-md" />-->
        <q-btn type="submit" label="Salvar" color="primary" class="full-width" />
    </q-form>
</template>

<script>
import axios from 'axios';

export default {
    props: ['task'],
    data() {
        return {
            localTask: { ...this.task },
        };
    },
    methods: {
        async submitForm() {
            try {
                this.localTask.status = 'Em andamento';
                // Envia os dados para a API com axios
                const response = await axios.post('http://localhost:8000/api/tarefas', {
                    titulo: this.localTask.title,
                    descricao: this.localTask.description,
                    status: this.localTask.status,
                });
                console.log('Tarefa criada com sucesso:', response.data);

                // Emitir o evento para o componente pai, caso precise manipular a tarefa criada
                this.$emit('save-task', response.data);
            } catch (error) {
                console.error('Erro ao salvar a tarefa:', error.response.data);
                // Tratar erro (exibir mensagem para o usuário)
            }
        },
    },
    watch: {
        task(newTask) {
            this.localTask = { ...newTask };
        },
    },
};
</script>

<style scoped>
.q-form {
    background-color: #f5f5f5;
    border-radius: 8px;
    padding: 20px;
}

.q-input {
    max-width: 400px;
    margin: 0 auto;
}

.q-btn {
    margin-top: 20px;
}
</style>
