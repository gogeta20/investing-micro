// import './assets/main.css'
import '@/core/assets/tailwind.css';
// import './tailwind.css';

import { createApp } from 'vue'
import { createPinia } from 'pinia'

// import App from './App.vue'
import App from '@/App.vue'
import router from '@/core/router'

const app = createApp(App)

app.use(createPinia())
app.use(router)

app.mount('#app')
