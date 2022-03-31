import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import axios from "axios";
import VueAxios from 'vue-axios';
import vfmPlugin from 'vue-final-modal';

createApp(App).use(vfmPlugin).use(router).use(VueAxios, axios).mount('#app');
