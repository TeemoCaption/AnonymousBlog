import { createRouter, createWebHistory } from 'vue-router'
import HomePage from '../components/index/HomePage.vue'
import TestPage from '../components/index/TestPage.vue'

const routes = [
    {
        path: '/',
        name: 'home',
        component: HomePage
    },
    {
        path: '/test',
        name: 'test',
        component: TestPage
    },
]

const router = createRouter({
history: createWebHistory(import.meta.env.BASE_URL),
routes
})

export default router