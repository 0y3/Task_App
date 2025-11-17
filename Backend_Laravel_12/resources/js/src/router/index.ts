import { createRouter, createWebHistory } from 'vue-router'
// import Mainlayout from '@/layout/Mainlayout.vue'
// import HomeView from '../views/HomeView.vue'


const baseUrl = import.meta.env.VITE_BASE_URL || import.meta.env.BASE_URL || '/'
const router = createRouter({
  history: createWebHistory(baseUrl),
  routes: [
    {
        // path: '/login',
        // name: 'Login',
        // // component: loadView('auth/LoginPage')
        // component: () => import('../pages/auth/LoginPage.vue')

        path: "/auth",
        name: "AuthLayout",
        component: () => import("../pages/auth/Layout.vue"),
        children: [
            {
                path: "/register",
                name: "Register",
                component: () => import("../pages/auth/RegisterPage.vue"),
            },
            {
                path: "/login",
                name: "Login",
                component: () => import("../pages/auth/LoginPage.vue"),
            },
        ],
    },
  ],
})


export default router
