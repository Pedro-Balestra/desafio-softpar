<template>
  <q-form @submit="submitForm" class="q-mb-md">
    <div class="row q-col-gutter-x-md">
      <q-input v-model="localTask.title" label="Título" class="col-8" outlined />
      <q-select
        v-model="localTask.category"
        :options="['Casa', 'Estudos', 'Lazer', 'Mercado', 'Contas', 'Outros']"
        label="Categoria"
        class="col-4"
        outlined
      />
    </div>
    <div class="q-mt-md">
      <q-input v-model="localTask.description" label="Descrição" outlined />
      <q-btn
        type="submit"
        label="Salvar"
        color="positive"
        class="full-width"
        icon="check"
        @click.prevent="submitForm"
      />
    </div>
  </q-form>
</template>

<script>
import axios from 'axios'

export default {
  props: ['task'],
  data() {
    return {
      localTask: { ...this.task },
    }
  },
  methods: {
    async submitForm() {
      try {
        if (!this.localTask.title) {
          this.$q.notify({ type: 'negative', message: 'O campo "Título" é obrigatório.' })
          return
        }

        if (this.localTask.title.length < 3 || this.localTask.title.length > 255) {
          this.$q.notify({
            type: 'negative',
            message: 'O título deve ter entre 3 e 255 caracteres.',
          })
          return
        }

        this.localTask.status = this.localTask.status || 'Em andamento'
        this.localTask.category = this.localTask.category || 'Padrão'

        let response

        if (this.localTask.id) {
          // Atualiza a tarefa existente
          response = await axios.put(`http://localhost:8000/api/tarefas/${this.localTask.id}`, {
            titulo: this.localTask.title,
            descricao: this.localTask.description,
            status: this.localTask.status,
            categoria: this.localTask.category,
          })
          console.log('Tarefa atualizada com sucesso:', response.data)
        } else {
          // Cria uma nova tarefa
          response = await axios.post('http://localhost:8000/api/tarefas', {
            titulo: this.localTask.title,
            descricao: this.localTask.description,
            status: this.localTask.status,
            categoria: this.localTask.category,
          })
          console.log('Tarefa criada com sucesso:', response.data)
          this.$q.notify({ type: 'positive', message: 'Tarefa criada com sucesso!' })
        }

        // Emite o evento para o componente pai
        this.$emit('save-task', response.data)
      } catch (error) {
        console.error('Erro ao salvar a tarefa:', error.response?.data || error.message)
        this.$q.notify({ type: 'negative', message: 'Erro ao salvar a tarefa. Tente novamente.' })
      }
    },
  },
  watch: {
    task(newTask) {
      this.localTask = { ...newTask }
    },
  },
}
</script>

<style lang="scss" scoped>
.q-form {
  background-color: #f5f5f5;
  border-radius: 8px;
  padding: 20px;
}

.q-btn {
  margin-top: 20px;
}
</style>
