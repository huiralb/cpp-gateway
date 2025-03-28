import './bootstrap';

import { createApp } from 'vue';
import App from './pages/App.vue';
import router from './router';
import { createPinia } from 'pinia';
import 'vue3-toastify/dist/index.css';

createApp(App)
    .use(router)
    .use(createPinia())
    .mount('#app');
