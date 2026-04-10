<template>
  <div class="bg-white border-b border-gray-200 sticky top-16 z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
      <div class="flex items-center gap-0 overflow-x-auto">
        <!-- Regular User Tabs -->
        <router-link
          v-for="tab in regularUserTabs"
          :key="tab.name"
          :to="tab.path"
          :class="[
            'px-4 py-3 font-medium text-sm border-b-2 transition-colors duration-200 whitespace-nowrap',
            isActive(tab.path)
              ? 'border-green-600 text-green-600'
              : 'border-transparent text-gray-600 hover:text-gray-900 hover:border-gray-300'
          ]"
        >
          <span class="flex items-center gap-2">
            <svg v-if="tab.icon" :class="`${tab.iconClass || 'h-4 w-4'} ${ isActive(tab.path) ? 'text-green-600' : 'text-gray-500'}`" xmlns="http://www.w3.org/2000/svg" :viewBox="tab.iconViewBox || '0 0 24 24'" :fill="tab.iconFill || 'none'" :stroke="tab.iconStroke || 'currentColor'" :stroke-width="tab.iconStrokeWidth || '2'" stroke-linecap="round" stroke-linejoin="round" v-html="tab.icon"></svg>
            {{ tab.label }}
          </span>
        </router-link>

        <!-- Divider for Admin -->
        <div v-if="isAdmin" class="border-l border-gray-200 h-12 mx-2"></div>

        <!-- Admin-Only Tabs -->
        <router-link
          v-for="tab in adminTabs"
          v-show="isAdmin"
          :key="tab.name"
          :to="tab.path"
          :class="[
            'px-4 py-3 font-medium text-sm border-b-2 transition-colors duration-200 whitespace-nowrap',
            isActive(tab.path)
              ? 'border-orange-600 text-orange-600'
              : 'border-transparent text-gray-600 hover:text-gray-900 hover:border-gray-300'
          ]"
        >
          <span class="flex items-center gap-2">
            <svg v-if="tab.icon" :class="`${tab.iconClass || 'h-4 w-4'} ${ isActive(tab.path) ? 'text-orange-600' : 'text-gray-500'}`" xmlns="http://www.w3.org/2000/svg" :viewBox="tab.iconViewBox || '0 0 24 24'" :fill="tab.iconFill || 'none'" :stroke="tab.iconStroke || 'currentColor'" :stroke-width="tab.iconStrokeWidth || '2'" stroke-linecap="round" stroke-linejoin="round" v-html="tab.icon"></svg>
            {{ tab.label }}
          </span>
        </router-link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import { useAuthStore } from '../stores/authPiniaStore'

const route = useRoute()
const authStore = useAuthStore()

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
    icon: '<path fill-rule="evenodd" clip-rule="evenodd" d="M14 19C14 18.4477 14.4477 18 15 18H33C33.5523 18 34 18.4477 34 19V34C34 34.5523 33.5523 35 33 35H15C14.4477 35 14 34.5523 14 34V19ZM16 25V28H20V25H16ZM16 33V30H20V33H16ZM22 33V30H26V33H22ZM28 33V30H32V33H28ZM28 25V28H32V25H28ZM22 28H26V25H22V28ZM16 23H32V20H16V23Z" fill="currentColor"/><path fill-rule="evenodd" clip-rule="evenodd" d="M10 5C10 4.44772 10.4477 4 11 4H31C31.2652 4 31.5196 4.10536 31.7071 4.29289L37.7071 10.2929C37.8946 10.4804 38 10.7348 38 11V43C38 43.5523 37.5523 44 37 44H11C10.4477 44 10 43.5523 10 43V5ZM12 6V42H36V12H31C30.4477 12 30 11.5523 30 11V6H12ZM32 7.41421V10H34.5858L32 7.41421Z" fill="currentColor"/>',
    iconViewBox: '0 0 48 48',
    iconFill: 'none',
    iconStroke: 'none',
    iconStrokeWidth: '0',
    iconClass: 'h-[18px] w-[18px]'
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
const isActive = (path) => {
  return route.path === path || route.path.startsWith(path.split('/').slice(0, -1).join('/'))
}
</script>

<style scoped>
/* Smooth scrolling for horizontal overflow */
::-webkit-scrollbar {
  height: 4px;
}

::-webkit-scrollbar-track {
  background: transparent;
}

::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 2px;
}

::-webkit-scrollbar-thumb:hover {
  background: #94a3b8;
}
</style>
