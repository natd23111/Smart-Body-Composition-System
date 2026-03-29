<template>
  <div class="flex flex-col min-h-screen bg-gray-50">
    <!-- Top Navigation Bar -->
    <nav class="sticky top-0 z-50 bg-white shadow border-b border-gray-200">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 py-0 flex items-center justify-between">
        <!-- Logo -->
        <div class="flex items-center gap-3 py-4">
          <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-green-600 to-emerald-600 rounded-lg">
            <svg class="h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
            </svg>
          </div>
          <div class="text-xl font-bold text-gray-900">Smart Body Composition</div>
        </div>

        <!-- Center - Tab Navigation for Desktop -->
        <div class="hidden md:flex items-center">
          <!-- Regular User Tabs -->
          <div
            v-for="tab in regularUserTabs"
            :key="tab.name"
            class="relative group"
          >
            <router-link
              :to="tab.path"
              :class="[
                'px-4 py-4 font-medium text-sm transition-colors duration-200 whitespace-nowrap flex items-center gap-2 text-gray-600 hover:text-gray-900',
                isActiveTab(tab.path)
                  ? 'border-green-600'
                  : 'border-transparent hover:border-gray-300'
              ]"
            >
              <svg v-if="tab.icon" :class="`h-4 w-4 text-gray-500`" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" v-html="tab.icon"></svg>
              {{ tab.label }}
            </router-link>
          </div>

          <!-- Divider for Admin -->
          <div v-if="isAdmin" class="border-l border-gray-200 h-8"></div>

          <!-- Admin-Only Tabs -->
          <div
            v-for="tab in adminTabs"
            v-show="isAdmin"
            :key="tab.name"
            class="relative group"
          >
            <router-link
              :to="tab.path"
              :class="[
                'px-4 py-4 font-medium text-sm transition-colors duration-200 whitespace-nowrap flex items-center gap-2 text-gray-600 hover:text-gray-900',
                isActiveTab(tab.path)
                  ? 'text-orange-600 font-semibold'
                  : 'hover:text-gray-700'
              ]"
            >
              <svg v-if="tab.icon" :class="`h-4 w-4 text-gray-500`" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" v-html="tab.icon"></svg>
              {{ tab.label }}
            </router-link>
          </div>
        </div>

        <!-- Right Side - User Menu & Actions -->
        <div class="flex items-center gap-4 py-4">
          <!-- Profile Dropdown -->
          <div class="relative group">
            <button class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-100 transition-colors">
              <div class="w-8 h-8 bg-gradient-to-br from-green-600 to-emerald-600 rounded-full flex items-center justify-center text-white font-bold text-sm">
                {{ userInitial }}
              </div>
              <span class="text-sm font-medium text-gray-700 hidden sm:inline">{{ userName }}</span>
              <svg class="h-4 w-4 text-gray-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="6 9 12 15 18 9"></polyline>
              </svg>
            </button>
            <div class="absolute right-0 mt-0 w-48 bg-white rounded-lg shadow-lg hidden group-hover:block border border-gray-200 z-10">
              <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-green-50 hover:text-green-600 rounded-t-lg">Settings</a>
              <button
                @click="handleLogout"
                :disabled="logoutLoading"
                class="w-full text-left px-4 py-2 text-gray-700 hover:bg-red-50 hover:text-red-600 disabled:bg-gray-100 disabled:text-gray-400 rounded-b-lg transition-colors flex items-center justify-between"
              >
                <span>{{ logoutLoading ? 'Logging out...' : 'Logout' }}</span>
                <svg v-if="logoutLoading" class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
              </button>
            </div>
          </div>

          <!-- Mobile Menu Button -->
          <button class="md:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors">
            <svg class="h-6 w-6 text-gray-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <line x1="4" y1="6" x2="20" y2="6"></line>
              <line x1="4" y1="12" x2="20" y2="12"></line>
              <line x1="4" y1="18" x2="20" y2="18"></line>
            </svg>
          </button>
        </div>
      </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 py-6">
      <RouterView />
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300 py-12 mt-12">
      <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-8 mb-8">
          <div>
            <h4 class="font-semibold text-white mb-4">Product</h4>
            <ul class="space-y-2">
              <li><a href="#" class="hover:text-green-400 transition-colors">Features</a></li>
              <li><a href="#" class="hover:text-green-400 transition-colors">Pricing</a></li>
              <li><a href="#" class="hover:text-green-400 transition-colors">Security</a></li>
            </ul>
          </div>
          <div>
            <h4 class="font-semibold text-white mb-4">Support</h4>
            <ul class="space-y-2">
              <li><a href="#" class="hover:text-green-400 transition-colors">Help Center</a></li>
              <li><a href="#" class="hover:text-green-400 transition-colors">Contact</a></li>
              <li><a href="#" class="hover:text-green-400 transition-colors">Status</a></li>
            </ul>
          </div>
          <div>
            <h4 class="font-semibold text-white mb-4">Company</h4>
            <ul class="space-y-2">
              <li><a href="#" class="hover:text-green-400 transition-colors">About</a></li>
              <li><a href="#" class="hover:text-green-400 transition-colors">Blog</a></li>
              <li><a href="#" class="hover:text-green-400 transition-colors">Careers</a></li>
            </ul>
          </div>
        </div>
        <div class="border-t border-gray-800 pt-8 text-sm">
          <p>&copy; 2026 BodyComposition System. All rights reserved.</p>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { computed, ref } from 'vue'
import { RouterView, useRouter, useRoute } from 'vue-router'
import { useAuthStore } from '../stores/authStore'

const router = useRouter()
const route = useRoute()
const authStore = useAuthStore()
const logoutLoading = ref(false)

// Get user info
const userName = computed(() => authStore.user.value?.name || 'User')
const userInitial = computed(() => (authStore.user.value?.name || 'U').charAt(0).toUpperCase())

// Check if user is admin
const isAdmin = computed(() => {
  return authStore.user?.value?.role === 'admin'
})

// Regular user tabs
const regularUserTabs = [
  {
    name: 'dashboard',
    label: 'Dashboard',
    path: '/dashboard',
    icon: '<circle cx="12" cy="12" r="1"></circle><path d="M12 1v6m4.22-4.22-4.24 4.24M12 23v-6m4.22 4.22-4.24-4.24M23 12h-6m4.22 4.22-4.24-4.24M1 12h6M4.22 16.22l4.24-4.24"></path>'
  },
  {
    name: 'body-composition',
    label: 'Body Composition',
    path: '/body-composition',
    icon: '<path d="M6 4h12v16H6z"></path><circle cx="6" cy="4" r="1"></circle><circle cx="18" cy="4" r="1"></circle>'
  },
  {
    name: 'recommendations',
    label: 'Recommendations',
    path: '/recommendations',
    icon: '<path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>'
  },
  {
    name: 'ai-tips',
    label: 'AI Tips',
    path: '/ai-tips',
    icon: '<path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"></path>'
  },
  {
    name: 'trends',
    label: 'Trends',
    path: '/trends',
    icon: '<polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline><polyline points="17 18 23 18 23 12"></polyline>'
  }
]

// Admin-only tabs
const adminTabs = [
  {
    name: 'admin-dashboard',
    label: 'Dashboard',
    path: '/admin/dashboard',
    icon: '<path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline>'
  },
  {
    name: 'users',
    label: 'Users',
    path: '/admin/users',
    icon: '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path>'
  },
  {
    name: 'data-management',
    label: 'Data Mgmt',
    path: '/admin/data',
    icon: '<path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><polyline points="12 3 12 15"></polyline>'
  },
  {
    name: 'templates',
    label: 'Templates',
    path: '/admin/templates',
    icon: '<path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>'
  },
  {
    name: 'settings',
    label: 'Settings',
    path: '/admin/settings',
    icon: '<circle cx="12" cy="12" r="3"></circle><path d="M12 1v6m4.22-1.22-4.24 4.24M12 23v-6m4.22 4.22-4.24-4.24M23 12h-6m4.22 4.22-4.24-4.24M1 12h6M4.22 16.22l4.24-4.24"></path>'
  }
]

// Check if a tab is active
const isActiveTab = (path) => {
  return route.path === path || route.path.startsWith(path.split('/').slice(0, -1).join('/'))
}

// Handle logout with loading state and error handling
const handleLogout = async () => {
  logoutLoading.value = true

  try {
    console.log('Starting logout...')

    // Call logout from auth store
    const success = await authStore.logout()

    console.log('Logout returned:', success)

    if (success) {
      // Small delay for better UX - shows the loading spinner briefly
      await new Promise(resolve => setTimeout(resolve, 500))

      console.log('Redirecting to login...')
      // Use replace to avoid going back to dashboard in browser history
      await router.replace('/login')
    } else {
      console.warn('Logout returned false')
      logoutLoading.value = false
    }
  } catch (error) {
    console.error('Logout error:', error)
    // Even if there's an error, still redirect to login
    logoutLoading.value = false
    await router.replace('/login')
  }
}
</script>

<style scoped>
/* Navbar styling */
nav {
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}
</style>
