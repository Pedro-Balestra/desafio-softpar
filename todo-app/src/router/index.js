import { createRouter, createWebHistory } from 'vue-router';
import HomePage from '../pages/HomePage.vue';
import TaskDetails from '../pages/TaskDetails.vue';

const routes = [
  {
    path: '/',
    name: 'Home',
    component: HomePage,
  },
  {
    path: '/task/:id',
    name: 'TaskDetails',
    component: TaskDetails,
    props: route => ({ id: route.params.id }), // Ajuste para passar o ID de forma explícita
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
