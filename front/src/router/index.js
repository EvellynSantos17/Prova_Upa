import { createRouter, createWebHistory } from 'vue-router'
import Welcome from '../components/HelloWorld.vue'
import Login from '../Login.vue'
import Cadastro from '../Cadastro.vue'

const routes = [
  { path: '/', name: 'Welcome', component: Welcome },
  { path: '/login', name: 'Login', component: Login },
  { path: '/cadastro', name: 'Cadastro', component: Cadastro }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router
