// src/router/route.js
import { createRouter, createWebHistory } from 'vue-router'
import Login from '../pages/Login.vue'
import Dashboard from '../pages/Home.vue'
import Profile from '../pages/Profile.vue'
import NotFound from '../pages/NotFound.vue'
import { useAuthStore } from '@/stores/auth'

const routes = [
  {
    path: '/login',
    name: 'Login',
    component: Login,
    meta: { requiresAuth: false }
  },
  {
    path: '/',
    name: 'Dashboard',
    component: Dashboard,
    meta: { requiresAuth: true }
  },
  {
    path: '/profile',
    name: 'Profile',
    component: Profile,
    meta: { requiresAuth: true }
  },
  {
    path: '/:pathMatch(.*)*',
    name: 'NotFound',
    component: NotFound,
    meta: { requiresAuth: false }
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

// Navigation Guard
router.beforeEach(async (to, from, next) => {
  const authStore = useAuthStore()

  // Check if route requires authentication
  if (to.meta.requiresAuth) {
    try {
      // Verify user authentication status
      if (!authStore.user) {
        await authStore.fetchUser()
      }

      // If user is authenticated, proceed
      next()
    } catch (error) {
      // If not authenticated, redirect to login
      next('/login')
    }
  } else {
    // Publicly accessible routes
    next()
  }
})

export default router
