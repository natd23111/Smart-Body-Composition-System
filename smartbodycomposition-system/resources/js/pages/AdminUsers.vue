<template>
  <div class="space-y-6">

    <!-- Page Header -->
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-3xl font-bold text-gray-900">User Management</h1>
        <p class="text-gray-500 mt-1">Manage user accounts for system maintenance purposes</p>
      </div>
      <button class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 transition-colors">
        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line>
        </svg>
        Add User
      </button>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white rounded-lg shadow border border-gray-200 p-4">
      <div class="flex flex-col md:flex-row gap-3">
        <!-- Search -->
        <div class="flex-1 relative">
          <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line>
          </svg>
          <input
            v-model="searchTerm"
            type="text"
            placeholder="Search by name, email..."
            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500"
          />
        </div>
        <!-- Role filter -->
        <select
          v-model="filterRole"
          class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500"
        >
          <option value="All">All Roles</option>
          <option value="user">User</option>
          <option value="admin">Admin</option>
        </select>
        <!-- Status filter -->
        <select
          v-model="filterStatus"
          class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500"
        >
          <option value="All">All Status</option>
          <option value="Active">Active</option>
          <option value="Inactive">Inactive</option>
        </select>
      </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden">
      <div class="px-6 py-4 border-b border-gray-100">
        <h3 class="font-semibold text-gray-900">Users ({{ loading ? '…' : filteredUsers.length }})</h3>
        <p class="text-sm text-gray-500">Basic user information for account management</p>
      </div>
      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
              <th class="text-left py-3 px-4 font-semibold text-gray-700">Name</th>
              <th class="text-left py-3 px-4 font-semibold text-gray-700">Email</th>
              <th class="text-left py-3 px-4 font-semibold text-gray-700">Role</th>
              <th class="text-left py-3 px-4 font-semibold text-gray-700">Last Activity</th>
              <th class="text-left py-3 px-4 font-semibold text-gray-700">Status</th>
              <th class="text-left py-3 px-4 font-semibold text-gray-700">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            <tr v-if="loading">
              <td colspan="6" class="py-10 text-center text-gray-400 text-sm">Loading users…</td>
            </tr>
            <tr v-else v-for="user in filteredUsers" :key="user.id" class="hover:bg-gray-50 transition-colors">
              <td class="py-3 px-4 font-medium text-gray-900">{{ user.name }}</td>
              <td class="py-3 px-4 text-gray-600">{{ user.email }}</td>
              <td class="py-3 px-4">
                <span :class="user.role === 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700'" class="px-2 py-0.5 rounded-full text-xs font-semibold capitalize">
                  {{ user.role }}
                </span>
              </td>
              <td class="py-3 px-4 text-gray-600">{{ user.last_activity }}</td>
              <td class="py-3 px-4">
                <span :class="user.status === 'Active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600'" class="px-2 py-0.5 rounded-full text-xs font-semibold">
                  {{ user.status }}
                </span>
              </td>
              <td class="py-3 px-4">
                <div class="flex items-center gap-2">
                  <button class="p-1.5 hover:bg-yellow-100 rounded transition-colors" title="Edit Account">
                    <svg class="h-4 w-4 text-yellow-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                    </svg>
                  </button>
                  <button class="p-1.5 hover:bg-orange-100 rounded transition-colors" title="Reset Password">
                    <svg class="h-4 w-4 text-orange-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <polyline points="1 4 1 10 7 10"></polyline><path d="M3.51 15a9 9 0 1 0 .49-3.5"></path>
                    </svg>
                  </button>
                  <button class="p-1.5 hover:bg-red-100 rounded transition-colors" title="Deactivate Account">
                    <svg class="h-4 w-4 text-red-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <polyline points="3 6 5 6 21 6"></polyline><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"></path><path d="M10 11v6"></path><path d="M14 11v6"></path>
                    </svg>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="flex items-center justify-between px-6 py-4 border-t border-gray-100">
        <p class="text-sm text-gray-500">
          Showing <span class="font-semibold">{{ filteredUsers.length }}</span> of
          <span class="font-semibold">{{ users.length }}</span> users
        </p>
        <div class="flex gap-2">
          <button class="px-3 py-1.5 border border-gray-300 rounded-lg text-sm hover:bg-gray-50 transition-colors">Previous</button>
          <button class="px-3 py-1.5 border border-green-600 bg-green-600 text-white rounded-lg text-sm">1</button>
          <button class="px-3 py-1.5 border border-gray-300 rounded-lg text-sm hover:bg-gray-50 transition-colors">Next</button>
        </div>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'

const searchTerm = ref('')
const filterRole = ref('All')
const filterStatus = ref('All')
const loading = ref(true)

const users = ref([])

async function fetchUsers() {
  try {
    const token = localStorage.getItem('auth_token')
    const res = await fetch('/api/admin/users', {
      headers: {
        'Accept': 'application/json',
        'Authorization': `Bearer ${token}`,
      },
    })
    users.value = await res.json()
  } catch (e) {
    console.error('Failed to load users:', e)
  } finally {
    loading.value = false
  }
}

onMounted(fetchUsers)

const filteredUsers = computed(() =>
  users.value.filter(u => {
    const q = searchTerm.value.toLowerCase()
    const matchSearch = u.name.toLowerCase().includes(q) || u.email.toLowerCase().includes(q)
    const matchRole   = filterRole.value === 'All' || u.role === filterRole.value
    const matchStatus = filterStatus.value === 'All' || u.status === filterStatus.value
    return matchSearch && matchRole && matchStatus
  })
)
</script>
