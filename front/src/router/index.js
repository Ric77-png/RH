import { createRouter, createWebHistory } from 'vue-router'
import RhView from '../view/RhView.vue'
import LoginView from '../view/LoginView.vue'

const routes = [
  { path: '/', redirect: '/login' },
  { path: '/login', name: 'login', component: LoginView },
  { path: '/nouvelle', name: 'nouvelle', component: RhView },
  { path: '/rh-dashboard', name: 'rh-dashboard', component: RhView },
  { path: '/manager-dashboard', name: 'manager-dashboard', component: RhView },
  { path: '/finance-dashboard', name: 'finance-dashboard', component: RhView },
  { path: '/direction-dashboard', name: 'direction-dashboard', component: RhView },
  { path: '/candidat-dashboard', name: 'candidat-dashboard', component: RhView },
]

export default createRouter({
  history: createWebHistory(),
  routes,
})
