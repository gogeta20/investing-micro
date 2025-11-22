import { createRouter, createWebHistory, type RouteRecordRaw } from 'vue-router';
// import homeRoutes from '@/modules/home/application/routes';
import HomeView from "@/pages/HomeView.vue";
import Projects from '@/pages/Projects.vue';
import Skills from '@/pages/Skills.vue';
import AnalysisDaily from '@/pages/stock/AnalysisDaily.vue';
import PortafolioView from '@/pages/stock/PortafolioView.vue';
import StocksView from '@/pages/stock/StocksView.vue';
import Chatbot from '@/pages/test/Chatbot/Chatbot.vue';
import PaymentCancel from '@/pages/test/Payments/PaymentCancel.vue';
import Payments from '@/pages/test/Payments/Payments.vue';
import PaymentSuccess from '@/pages/test/Payments/PaymentSuccess.vue';
import Symfony from '@/pages/test/Symfony.vue';
import Whoami from '@/pages/Whoami.vue';

const routes: RouteRecordRaw[] = [
  {
    path: '/',
    component: () => import("@/core/layout/BaseLayout.vue"),
    children: [
      {
        path: "",
        redirect: "/home",
      },
      {
        path: "/home",
        component: HomeView,
      },
      {
        path: "/projects",
        component: Projects,
      },
      {
        path: "/whoami",
        component: Whoami,
      },
      {
        path: "/skills",
        component: Skills,
      },
      {
        path: "/symfony-cqrs",
        component: Symfony,
      },
      {
        path: "/payments",
        component: Payments,
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
      },
      {
        path: "/portfolio",
        component: PortafolioView,
      },
      {
        path: "/analysis/daily",
        component: AnalysisDaily,
      },
    ],
  },
];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
});

export default router;
