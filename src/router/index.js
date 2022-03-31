import { createRouter, createWebHistory } from 'vue-router';
import ListarEquipos from '../views/ListarEquipos.vue';
import AgregarEquipos from '../views/AgregarEquipos.vue';
import ClasifView from '@/views/ClasifView';


const routes = [
  {
    path: '/',
    name: 'equipo',
    component: ListarEquipos,
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
  {
    path: '/about',
    name: 'about',
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () => import(/* webpackChunkName: "about" */ '../views/AboutView.vue'),
  },
];

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes,
});

export default router;
