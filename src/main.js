import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import axios from "axios";
import VueAxios from 'vue-axios';
import vfmPlugin from 'vue-final-modal';
import { initializeApp } from "firebase/app";

// Your web app's Firebase configuration

const firebaseConfig = {
  apiKey: "AIzaSyBYPWZOydJTDT3S1j_vWPnPN85dRTfFzbk",
  authDomain: "stock-equipo-nande-cable.firebaseapp.com",
  projectId: "stock-equipo-nande-cable",
  storageBucket: "stock-equipo-nande-cable.appspot.com",
  messagingSenderId: "260279715350",
  appId: "1:260279715350:web:d9136ab28d0a91eb861737"
};

// Initialize Firebase
initializeApp(firebaseConfig);

createApp(App).use(vfmPlugin).use(router).use(VueAxios, axios).mount('#app');
