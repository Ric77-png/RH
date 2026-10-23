
import { createRouter, createWebHistory } from 'vue-router'
import DefaultLayout from '../layouts/DefaultLayout.vue'
import LoginView from '../view/LoginView.vue'
import RhView from '../view/RhView.vue'
import ManagerView from '../view/ManagerView.vue'

const routes = [
  {
    path: '/',
    redirect: '/login',
  },
  {
    path: '/login',
    name: 'login',
    component: LoginView,
  },
  {
    path: '/',
    component: DefaultLayout,
    children: [
      {
        path: 'rh-dashboard',
        name: 'rh-dashboard',
        component: RhView,
      },
      {
        path: 'manager-dashboard',
        name: 'manager-dashboard',
        component: ManagerView,
      },
      {
        path: 'nouvelle',
        name: 'nouvelle',
        component: ManagerView,
      },
      {
        path: 'finance-dashboard',
        name: 'finance-dashboard',
        component: RhView,
      },
      {
        path: 'direction-dashboard',
        name: 'direction-dashboard',
        component: RhView,
      },
      {
        path: 'candidat-dashboard',
        name: 'candidat-dashboard',
        component: RhView,
      },
    ],
  },
]

export default createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior() {
    return { top: 0 }
  },
})
