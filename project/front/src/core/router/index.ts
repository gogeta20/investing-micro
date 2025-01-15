import { createRouter, createWebHistory } from 'vue-router';
import homeRoutes from '@/modules/home/application/routes';

const routes = [
  ...homeRoutes,
];

// Crear el router usando el historial web
const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
});

export default router;
