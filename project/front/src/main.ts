import "@/assets/css/index.css";
import "@/assets/scss/main.scss";
import "flickity/css/flickity.css";
// import "primevue/resources/primevue.min.css";
import "primeicons/primeicons.css";

import { createApp } from "vue";
import { createPinia } from "pinia";
import PrimeVue from "primevue/config";
import App from "@/App.vue";
import router from "@/core/router";

const app = createApp(App);
app.use(router);
app.use(createPinia());
app.use(PrimeVue);
app.mount("#app");
