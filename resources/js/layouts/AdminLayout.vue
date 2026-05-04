<template>
  <div class="flex flex-col min-h-screen bg-gray-50">
    <!-- Top Navigation Bar -->
    <nav class="sticky top-0 z-50 bg-white shadow border-b border-gray-200">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 py-0 flex items-center justify-between">

        <!-- Logo -->
        <div class="flex items-center gap-3 py-4 cursor-pointer select-none" @click="scrollToTop">
          <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-green-600 to-emerald-600 rounded-lg">
            <svg class="h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
            </svg>
          </div>
          <div>
            <div class="text-xl font-bold text-gray-900 leading-tight">Smart Body Composition</div>
            <div class="text-xs font-semibold text-green-600 leading-tight tracking-wide uppercase">Admin Panel</div>
          </div>
        </div>

        <!-- Center - Admin Tab Navigation -->
        <div class="hidden md:flex items-center">
          <div
            v-for="tab in adminTabs"
            :key="tab.name"
            class="relative"
          >
            <router-link
              :to="tab.path"
              :class="[
                'px-4 py-4 font-medium text-sm transition-colors duration-200 whitespace-nowrap flex items-center gap-2',
                isActiveTab(tab.path)
                  ? 'text-green-600 border-b-2 border-green-600'
                  : 'text-gray-600 hover:text-green-600 border-b-2 border-transparent hover:border-green-300'
              ]"
            >
              <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" v-html="tab.icon"></svg>
              {{ tab.label }}
            </router-link>
          </div>
        </div>

        <!-- Right Side - Admin Badge + User Menu -->
        <div class="flex items-center gap-4 py-4">

          <!-- Admin badge -->
          <span class="hidden sm:inline-flex items-center gap-1.5 px-2.5 py-1 bg-green-50 text-green-700 border border-green-200 rounded-full text-xs font-semibold">
            <svg class="h-3 w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
            </svg>
            Admin
          </span>

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
              <router-link to="/admin/settings" class="block px-4 py-2 text-gray-700 hover:bg-green-50 hover:text-green-600 rounded-t-lg">Settings</router-link>
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
          <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors">
            <svg class="h-6 w-6 text-gray-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <line x1="4" y1="6" x2="20" y2="6"></line>
              <line x1="4" y1="12" x2="20" y2="12"></line>
              <line x1="4" y1="18" x2="20" y2="18"></line>
            </svg>
          </button>
        </div>
      </div>

      <!-- Mobile Menu -->
      <div v-if="mobileMenuOpen" class="md:hidden border-t border-gray-100 bg-white px-4 py-2 space-y-1">
        <router-link
          v-for="tab in adminTabs"
          :key="tab.name"
          :to="tab.path"
          @click="mobileMenuOpen = false"
          :class="[
            'flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors',
            isActiveTab(tab.path)
              ? 'bg-green-50 text-green-600'
              : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900'
          ]"
        >
          <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" v-html="tab.icon"></svg>
          {{ tab.label }}
        </router-link>
      </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 py-6">
      <RouterView />
    </main>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { RouterView, useRouter, useRoute } from 'vue-router'
import { useAuthStore } from '@/stores/authPiniaStore'

const router = useRouter()
const route = useRoute()
const authStore = useAuthStore()

const logoutLoading = ref(false)
const mobileMenuOpen = ref(false)

const userName = computed(() => authStore.user?.name || authStore.user?.value?.name || 'Admin')
const userInitial = computed(() => (authStore.user?.name || authStore.user?.value?.name || 'A').charAt(0).toUpperCase())

const adminTabs = [
  {
    name: 'admin-dashboard',
    label: 'Dashboard',
    path: '/admin/dashboard',
    icon: '<rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect>',
  },
  {
    name: 'admin-users',
    label: 'Users',
    path: '/admin/users',
    icon: '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path>',
  },
  {
    name: 'admin-data',
    label: 'Data Management',
    path: '/admin/data',
    icon: '<ellipse cx="12" cy="5" rx="9" ry="3"></ellipse><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"></path><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path>',
  },
  {
    name: 'admin-templates',
    label: 'Templates',
    path: '/admin/templates',
    icon: '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline>',
  },
  {
    name: 'admin-settings',
    label: 'Settings',
    path: '/admin/settings',
    icon: '<circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"></path>',
  },
]

const scrollToTop = () => window.scrollTo({ top: 0, behavior: 'smooth' })

const isActiveTab = (path) => route.path === path || route.path.startsWith(path + '/')

const handleLogout = async () => {
  logoutLoading.value = true
  try {
    await authStore.logout()
    await new Promise(resolve => setTimeout(resolve, 500))
    await router.replace('/login')
  } catch {
    await router.replace('/login')
  } finally {
    logoutLoading.value = false
  }
}
</script>

<style scoped>
nav {
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}
</style>
