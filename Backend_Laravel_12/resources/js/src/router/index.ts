import { createRouter, createWebHistory } from 'vue-router'
// import Mainlayout from '@/layout/Mainlayout.vue'
// import HomeView from '../views/HomeView.vue'


const baseUrl = import.meta.env.VITE_BASE_URL || import.meta.env.BASE_URL || '/'
const router = createRouter({
  history: createWebHistory(baseUrl),
  routes: [
    {
        path: "/auth",
        name: "AuthLayout",
        component: () => import("../pages/auth/Layout.vue"),
        children: [
            {
                path: "register",
                name: "Register",
                component: () => import("../pages/auth/RegisterPage.vue"),
            },
            {
                path: "login",
                name: "Login",
                component: () => import("../pages/auth/LoginPage.vue"),
            },
        ],
    },
    {
        path: "/admin",
        name: "admin",
        component: () => import("../layouts/Layout.vue"),
        children: [
            {
                path: "dashboard",
                name: "dashboard",
                component: () => import("../pages/admin/DashboardPage.vue"),
            },
            {
                path: "users",
                name: "users",
                component: () => import("../pages/admin/UserListPage.vue"),
            },
        ],
    },
  ],
})


export default router
