import { createApp } from 'vue'
import App from './App.vue'
import ElementPlus from 'element-plus'
import 'element-plus/dist/index.css'
import router from './router';
import axios from 'axios';

axios.defaults.baseURL = 'http://localhost:8000';

createApp(App)
    .use(router)
    .use(ElementPlus)
    .mount('#app');
