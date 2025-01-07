import axios from 'axios';

const api = axios.create({
  baseURL: 'http://localhost:8000/api',
});

export default {
  getTasks() {
    return api.get('/tarefas');
  },
  createTask(task) {
    return api.post('/tarefas', task);
  },
  updateTask(id, task) {
    return api.put(`/tarefas/${id}`, task);
  },
  deleteTask(id) {
    return api.delete(`/tarefas/${id}`);
  },
  fetchTaskById(id) {
    return api.get(`/tarefas/${id}`);
  },
};
