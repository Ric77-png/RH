import { createRouter, createWebHistory } from 'vue-router'
import RhView from '../view/RhView.vue'
import ManagerView from '../view/ManagerView.vue'

const routes = [
  { path: '/', name: 'accueil', component: RhView },
  { path: '/nouvelle', name: 'nouvelle', component: RhView },
  {
    path: '/manager',
    name: 'manager',
    component: ManagerView,
  }
]

export default createRouter({
  // Mode « history » : de vraies URL (/formations/3), sans # dans l'adresse
  history: createWebHistory(),
  routes
})
