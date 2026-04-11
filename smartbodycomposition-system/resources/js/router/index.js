import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/authPiniaStore'
import MainLayout from '@/layouts/MainLayout.vue'
import Home from '@/pages/Home.vue'
import Login from '@/pages/Login.vue'
import Register from '@/pages/Register.vue'
import ForgotPassword from '@/pages/ForgotPassword.vue'
import ResetPassword from '@/pages/ResetPassword.vue'
import ProfileSetup from '@/pages/ProfileSetup.vue'
import Dashboard from '@/pages/Dashboard.vue'
import BodyComposition from '@/pages/BodyComposition.vue'
import Recommendations from '@/pages/Recommendations.vue'
import AITips from '@/pages/AITips.vue'
import Trends from '@/pages/Trends.vue'
import Goals from '@/pages/Goals.vue'
import Settings from '@/pages/Settings.vue'
import AdminDashboard from '@/pages/AdminDashboard.vue'
import AdminUsers from '@/pages/AdminUsers.vue'
import AdminData from '@/pages/AdminData.vue'
import AdminTemplates from '@/pages/AdminTemplates.vue'
import AdminSettings from '@/pages/AdminSettings.vue'
import AdminLayout from '@/layouts/AdminLayout.vue'

const routes = [
  {
    path: '/',
    redirect: '/home',
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
    path: '/forgot-password',
    name: 'forgot-password',
    component: ForgotPassword,
    meta: { requiresAuth: false }
  },
  {
    path: '/reset-password',
    name: 'reset-password',
    component: ResetPassword,
    meta: { requiresAuth: false }
  },
  {
    path: '/profile-setup',
    name: 'profile-setup',
    component: ProfileSetup,
    meta: { requiresAuth: true }
  },
  {
    path: '/',
    component: MainLayout,
    meta: { requiresAuth: true },
    children: [
      {
        path: 'home',
        name: 'home',
        component: Home,
        meta: { requiresAuth: true }
      },
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
        path: 'goals',
        name: 'goals',
        component: Goals,
        meta: { requiresAuth: true }
      },
      {
        path: 'settings',
        name: 'settings',
        component: Settings,
        meta: { requiresAuth: true }
      }
    ]
  },
  // Admin routes - separate layout
  {
    path: '/admin',
    component: AdminLayout,
    meta: { requiresAuth: true, requiresAdmin: true },
    children: [
      {
        path: 'dashboard',
        name: 'admin-dashboard',
        component: AdminDashboard,
        meta: { requiresAuth: true, requiresAdmin: true }
      },
      {
        path: 'users',
        name: 'admin-users',
        component: AdminUsers,
        meta: { requiresAuth: true, requiresAdmin: true }
      },
      {
        path: 'data',
        name: 'admin-data',
        component: AdminData,
        meta: { requiresAuth: true, requiresAdmin: true }
      },
      {
        path: 'templates',
        name: 'admin-templates',
        component: AdminTemplates,
        meta: { requiresAuth: true, requiresAdmin: true }
      },
      {
        path: 'settings',
        name: 'admin-settings',
        component: AdminSettings,
        meta: { requiresAuth: true, requiresAdmin: true }
      }
    ]
  }
]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL || '/'),
  routes,
  scrollBehavior() {
    return { top: 0, behavior: 'smooth' }
  },
})

// Navigation guard for authentication
router.beforeEach((to, from, next) => {
  const authStore = useAuthStore()

  // Initialize auth from localStorage on first load if not already loaded

  if (authStore.user === undefined || authStore.user === null) {
    authStore.initAuth()
  }

  const isAuthenticated = authStore.isAuthenticated
  const isAdmin = authStore.user?.role === 'admin'
  const requiresAuth = to.meta.requiresAuth
  const requiresAdmin = to.meta.requiresAdmin

  console.log('Router guard:', {
    path: to.path,
    isAuthenticated,
    isAdmin,
    requiresAuth,
    user: authStore.user
  })

  // Redirect to login if accessing protected route without authentication
  if (requiresAuth && !isAuthenticated) {
    console.log('Redirecting to login - not authenticated')
    next('/login')
  } else if (requiresAdmin && !isAdmin) {
    // Regular user tried to access an admin-only route
    console.log('Redirecting to home - not an admin')
    next('/home')
  } else if (to.path === '/login' && isAuthenticated) {
    // Already logged in — send admin to admin dashboard, users to home
    const destination = isAdmin ? '/admin/dashboard' : '/home'
    console.log('Already authenticated, redirecting to', destination)
    next(destination)
  } else {
    next()
  }
})

export default router
