import { createRouter, createWebHistory } from 'vue-router'
import Login from '@/pages/Login.vue'

const routes = [
  {
    path: '/',
    redirect: '/login',
  },
  {
    path: '/login',
    name: 'login',
    component: Login,
  },
  // Add more routes here as you build other pages
  // {
  //   path: '/dashboard',
  //   name: 'dashboard',
  //   component: Dashboard,
  //   meta: { requiresAuth: true }
  // }
]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL || '/'),
  routes,
})

// Navigation guard for authentication
router.beforeEach((to, from, next) => {
  const token = localStorage.getItem('auth_token')

  // Redirect to login if accessing protected route without token
  if (to.meta.requiresAuth && !token) {
    next('/login')
  } else if (to.path === '/login' && token) {
    // Redirect to dashboard if already logged in
    next('/dashboard')
  } else {
    next()
  }
})

export default router
