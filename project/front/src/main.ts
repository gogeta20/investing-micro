import "@/assets/css/index.css";
import "@/assets/scss/main.scss";
import "flickity/css/flickity.css";

import { createApp } from "vue";
import { createPinia } from 'pinia'
import Particles from "particles.vue3";
import App from "@/App.vue";
import router from '@/core/router'

const app = createApp(App)
app.use(router)
app.use(createPinia())
app.use(Particles)
app.mount("#app")

