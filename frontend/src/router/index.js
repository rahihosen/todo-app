import { createRouter, createWebHistory } from 'vue-router'
import Login from '../components/LoginPage.vue'
import Dashboard from '../components/TodoApp.vue'
import Signup from '../components/SignupPage.vue'

const routes = [
    { path: '/', redirect: '/login' },
    { path: '/login', name: 'Login', component: Login },
    { path: '/dashboard', name: 'Dashboard', component: Dashboard },
    { path: '/signup', name: 'Signup', component: Signup },
]

const router = createRouter({
    history: createWebHistory(),
    routes,
})

export default router
