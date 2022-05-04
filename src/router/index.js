import { createRouter, createWebHistory } from 'vue-router';
import ListarEquipos from '../views/ListarEquipos.vue';
import AgregarEquipos from '../views/AgregarEquipos.vue';
import ClasifView from '@/views/ClasifView';
import LoginView from '@/views/LoginView';
import Logout from '@/views/Logout';

//uso los nombres de las rutas para indicar en la API sobre qué elemento se está trabajando
const routes = [
  {
    path: '/logout',
    name: 'logout',
    component: Logout,
  },
  {
    path: '/',
    name: 'equipo',
    component: ListarEquipos,
  },
  {
    path: '/login',
    name: 'login',
    component: LoginView,
  },
  {
    path: '/a',
    name: 'agregar',
    component: AgregarEquipos,
  },
  {
    path: '/c',
    name: 'categoria',
    component: ClasifView,
  },
  {
    path: '/e',
    name: 'estado',
    component: ClasifView,
  },
  {
    path: '/m',
    name: 'modelo',
    component: ClasifView,
  },
];

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes,
});

export default router;
