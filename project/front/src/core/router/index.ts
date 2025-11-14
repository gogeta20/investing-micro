import { createRouter, createWebHistory } from 'vue-router';
// import homeRoutes from '@/modules/home/application/routes';
import HomeView from "@/pages/HomeView.vue";
import Projects from '@/pages/Projects.vue';
import Skills from '@/pages/Skills.vue';
import Payments from '@/pages/test/Payments/Payments.vue';
import PaymentCancel from '@/pages/test/Payments/PaymentCancel.vue';
import PaymentSuccess from '@/pages/test/Payments/PaymentSuccess.vue';
import Symfony from '@/pages/test/Symfony.vue';
import Whoami from '@/pages/Whoami.vue';
import Chatbot from '@/pages/test/Chatbot/Chatbot.vue';
import StocksView from '@/pages/StocksView.vue';

const routes = [
  {
    path: '/',
    redirect: "/home",
    component: () => import("@/core/layout/BaseLayout.vue"),
    children: [
      {
        path: "/home",
        component: HomeView,
        children: [],
      },
      {
        path: "/projects",
        component: Projects,
        children: [],
      },
      {
        path: "/whoami",
        component: Whoami,
        children: [],
      },
      {
        path: "/skills",
        component: Skills,
        children: [],
      },
      {
        path: "/symfony-cqrs",
        component: Symfony,
        children: [],
      },
      {
        path: "/payments",
        component: Payments,
        children: [],
      },
      {
        path: "/payments/success",
        component: PaymentSuccess,
      },
      {
        path: "/payments/cancel",
        component: PaymentCancel,
      },
      {
        path: "/chatbot",
        component: Chatbot,
      },
      {
        path: "/stocks",
        component: StocksView,
        children: [],
      },
    ],
  },
];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
});

export default router;
