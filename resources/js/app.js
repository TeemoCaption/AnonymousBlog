import './bootstrap';
import {createApp} from 'vue' 

import app from './components/App.vue'
import router from './router/index.js' // 導入router內的index.js

createApp(app).use(router).mount("#app"); 