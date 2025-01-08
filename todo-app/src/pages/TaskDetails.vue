<template>
  <div class="task-edit-container">
    <div class="task-edit-box">
      <q-form @submit="updateTask" class="q-pa-md q-gutter-md">
        <div class="row q-col-gutter-x-md">
          <q-input v-model="task.titulo" label="Título" outlined class="col-12 col-md-6" />
          <q-select
            v-model="task.status"
            :options="['Em andamento', 'Concluído', 'Pendente']"
            label="Status"
            outlined
            class="col-12 col-md-6"
          />
        </div>

        <div class="row q-col-gutter-md q-mt-md">
          <q-input v-model="task.descricao" label="Descrição" outlined class="col-12" />
        </div>

        <div class="row q-col-gutter-md q-mt-md">
          <q-select
            v-model="task.categoria"
            :options="['Casa', 'Estudos', 'Lazer', 'Mercado', 'Contas', 'Outros']"
            label="Categoria"
            outlined
            class="col-12"
          />
        </div>

        <div class="row q-col-gutter-x-md">
          <q-input
            v-model="formattedCreatedAt"
            label="Data e Hora de Criação"
            outlined
            readonly
            class="col-12 col-md-6"
          />
          <q-input
            v-model="formattedUpdatedAt"
            label="Data e Hora de Conclusão"
            outlined
            readonly
            class="col-12 col-md-6"
          />
        </div>

        <div class="row q-col-gutter-md q-mt-md justify-center">
          <q-btn
            type="submit"
            label="Salvar"
            icon="check"
            color="positive"
            class="col-4"
            @click.prevent="updateTask"
          />
          <q-btn label="Deletar" icon="delete" color="negative" class="col-4" @click="deleteTask" />
        </div>

        <div class="row q-mt-lg justify-center">
          <q-btn
            icon="home"
            color="black"
            size="lg"
            flat
            dense
            round
            @click="this.$router.push('/')"
          >
            <q-tooltip>Ir para Home</q-tooltip>
          </q-btn>
        </div>
      </q-form>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import dayjs from 'dayjs'

export default {
  props: ['id'],
  data() {
    return {
      task: {
        titulo: '',
        status: '',
        descricao: '',
        categoria: '',
        created_at: '',
        updated_at: '',
        completed_at: '',
      },
    }
  },
  created() {
    this.fetchTask()
  },
  computed: {
    formattedCreatedAt() {
      return this.task.created_at
        ? dayjs(this.task.created_at).format('DD/MM/YYYY HH:mm:ss')
        : 'N/A'
    },
    formattedUpdatedAt() {
      return this.task.completed_at
        ? dayjs(this.task.completed_at).format('DD/MM/YYYY HH:mm:ss')
        : 'Não concluído'
    },
  },
  methods: {
    async fetchTask() {
      try {
        const response = await axios.get(`http://127.0.0.1:8000/api/tarefas/${this.id}`)
        this.task = response.data
      } catch (error) {
        console.error('Erro ao buscar a tarefa:', error)
      }
    },
    async updateTask() {
      try {
        // Verifica se o título está vazio ou fora do limite de caracteres antes de atualizar
        if (!this.task.titulo || this.task.titulo.length < 3 || this.task.titulo.length > 255) {
          this.$q.notify({
            type: 'negative',
            message: 'O título deve ter entre 3 e 255 caracteres.',
          })
          return // Impede a execução do restante do código
        }

        // Define valores padrão para evitar campos vazios
        this.task.status = this.task.status || 'Pendente'
        this.task.descricao = this.task.descricao || 'Sem descrição'
        this.task.categoria = this.task.categoria || 'Outros'

        // Define a data de conclusão se o status for "Concluído"
        if (this.task.status === 'Concluído' && !this.task.completed_at) {
          this.task.completed_at = new Date().toISOString() // Define a data de conclusão no formato ISO
        } else if (this.task.status !== 'Concluído') {
          this.task.completed_at = null // Limpa a data se não estiver concluído
        }

        // Faz a requisição para atualizar a tarefa
        await axios.put(`http://127.0.0.1:8000/api/tarefas/${this.id}`, this.task)
        this.$q.notify({ type: 'positive', message: 'Tarefa atualizada com sucesso!' })
      } catch (error) {
        console.error('Erro ao atualizar a tarefa:', error)
        this.$q.notify({ type: 'negative', message: 'Erro ao atualizar a tarefa.' })
      }
    },
    async deleteTask() {
      try {
        await axios.delete(`http://127.0.0.1:8000/api/tarefas/${this.id}`, this.task)
        this.$q.notify({ type: 'positive', message: 'Sucesso ao deletar a tarefa' })
        this.$router.push('/')
      } catch (error) {
        console.error('Erro ao deletar a tarefa:', error)
        this.$q.notify({ type: 'negative', message: 'Erro ao deletar a tarefa.' })
      }
    },
  },
}
</script>

<style lang="scss"></style>

<style lang="scss" scoped>
.task-edit-container {
  display: flex;
  flex-direction: column;
  /* Organiza o título e a caixa em coluna */
  align-items: center;
  /* Centraliza os itens horizontalmente */
  justify-content: center;
  /* Alinha os itens ao topo */
  height: 100vh;
  /* Altura mínima para ocupar toda a tela */
  padding: 20px;
  /* Espaçamento interno */
  background-color: $primary;
  /* Fundo do container */
}
.task-edit-box {
  background: white;
  border-radius: 12px;
  box-shadow: 10px 10px 10px rgba(0, 0, 0, 0.2);
  width: 90%;
  max-width: 600px;
  padding: 20px;
}
.row.q-col-gutter-md.q-mt-md {
  justify-content: center;
}

.q-btn {
  margin: 0 8px;
  padding: 10px;
}
</style>
