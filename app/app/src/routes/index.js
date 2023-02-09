import Login from "@/components/Login.vue";
import Applications from "@/components/Application/Applications.vue";
import Users from "@/components/User/Users.vue";
import Application from "@/components/Application/Application.vue";
import User from "@/components/User/User.vue";
import ApplicationStatus from "@/components/Application/ApplicationStatus.vue";
import { createRouter, createWebHistory } from 'vue-router'
import store from '@/store'

const routes = [
    {
        path: '/login',
        name: 'Login',
        component: Login
    },
    {
        path: '/applications',
        name: 'Applications',
        component: Applications
    },
    {
        path: '/application/:applicationId',
        name: 'Application',
        component: Application,
        meta: { requiresAdmin: true }
    },
    {
        path: '/application/:applicationId/status/:status',
        name: 'ApplicationStatus',
        component: ApplicationStatus,
        meta: { requiresAdmin: true }
    },
    {
        path: '/users',
        name: 'Users',
        component: Users,
        meta: { requiresAdmin: true }
    },
    {
        path: '/user/:userId',
        name: 'User',
        component: User,
        meta: { requiresAdmin: true }
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes: routes
})

router.beforeEach((to, from) => {
    // if not logged in redirect to /login
    if (!store.methods.isLoggedIn() && to.path !== '/login') {
        return '/login';
    }
    // if is logged and visit /login redirect to /
    if (store.methods.isLoggedIn() && to.path === '/login') {
        return '/';
    }
    // if is logged in employee and visits / redirect to applications
    if (store.methods.isLoggedIn() && store.methods.isRole('employee') && to.path === '/') {
        return '/applications';
    }
    // if is logged in admin and visits / redirect to users
    if (store.methods.isLoggedIn() && store.methods.isRole('admin') && to.path === '/') {
        return '/users';
    }
    // if path is available only on admins and user visits, redirect to existing
    if (to.meta.requiresAdmin && !store.methods.isRole('admin')) {
        return from.path
    }
})

export default router
