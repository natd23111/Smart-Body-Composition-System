import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/authPiniaStore'
import MainLayout from '@/layouts/MainLayout.vue'
import Login from '@/pages/Login.vue'
import Register from '@/pages/Register.vue'
import Dashboard from '@/pages/Dashboard.vue'
import BodyComposition from '@/pages/BodyComposition.vue'
import Recommendations from '@/pages/Recommendations.vue'
import AITips from '@/pages/AITips.vue'
import Trends from '@/pages/Trends.vue'
import Settings from '@/pages/Settings.vue'

const routes = [
  {
    path: '/',
    redirect: '/dashboard',
  },
  {
    path: '/login',
    name: 'login',
    component: Login,
    meta: { requiresAuth: false }
  },
  {
    path: '/register',
    name: 'register',
    component: Register,
    meta: { requiresAuth: false }
  },
  {
    path: '/',
    component: MainLayout,
    meta: { requiresAuth: true },
    children: [
      {
        path: 'dashboard',
        name: 'dashboard',
        component: Dashboard,
        meta: { requiresAuth: true }
      },
      {
        path: 'body-composition',
        name: 'body-composition',
        component: BodyComposition,
        meta: { requiresAuth: true }
      },
      {
        path: 'recommendations',
        name: 'recommendations',
        component: Recommendations,
        meta: { requiresAuth: true }
      },
      {
        path: 'ai-tips',
        name: 'ai-tips',
        component: AITips,
        meta: { requiresAuth: true }
      },
      {
        path: 'trends',
        name: 'trends',
        component: Trends,
        meta: { requiresAuth: true }
      },
      {
        path: 'settings',
        name: 'settings',
        component: Settings,
        meta: { requiresAuth: true }
      }
    ]
  }
]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL || '/'),
  routes,
})

// Navigation guard for authentication
router.beforeEach((to, from, next) => {
  const authStore = useAuthStore()

  // Initialize auth from localStorage on first load if not already loaded

  if (authStore.user === undefined || authStore.user === null) {
    authStore.initAuth()
  }

  const isAuthenticated = authStore.isAuthenticated
  const requiresAuth = to.meta.requiresAuth

  console.log('Router guard:', {
    path: to.path,
    isAuthenticated,
    requiresAuth,
    user: authStore.user
  })

  // Redirect to login if accessing protected route without authentication
  if (requiresAuth && !isAuthenticated) {
    console.log('Redirecting to login - not authenticated')
    next('/login')
  } else if (to.path === '/login' && isAuthenticated) {
    // Redirect to dashboard if already logged in
    console.log('Already authenticated, redirecting to dashboard')
    next('/dashboard')
  } else {
    next()
  }
})

export default router
