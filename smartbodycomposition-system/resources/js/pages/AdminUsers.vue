<template>
  <div class="space-y-6">

    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-3xl font-bold text-gray-900">User Management</h1>
        <p class="text-gray-500 mt-1">Manage user accounts for system maintenance purposes</p>
      </div>
      <button
        @click="openCreateModal"
        class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 transition-colors"
      >
        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line>
        </svg>
        Add User
      </button>
    </div>

    <div v-if="pageError" class="bg-red-50 border border-red-200 rounded-lg p-4 text-sm text-red-700">
      {{ pageError }}
    </div>

    <div v-if="pageMessage" class="bg-green-50 border border-green-200 rounded-lg p-4 text-sm text-green-700">
      {{ pageMessage }}
    </div>

    <div class="bg-white rounded-lg shadow border border-gray-200 p-4">
      <div class="flex flex-col md:flex-row gap-3">
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
        <select
          v-model="filterRole"
          class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500"
        >
          <option value="All">All Roles</option>
          <option value="user">User</option>
          <option value="admin">Admin</option>
        </select>
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

    <div class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden">
      <div class="px-6 py-4 border-b border-gray-100">
        <h3 class="font-semibold text-gray-900">Users ({{ loading ? '…' : filteredUsers.length }})</h3>
        <p class="text-sm text-gray-500">Create, edit, and remove accounts from a single place. Status is stored as an account setting.</p>
      </div>
      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
              <th class="text-left py-3 px-4 font-semibold text-gray-700">Name</th>
              <th class="text-left py-3 px-4 font-semibold text-gray-700">Email</th>
              <th class="text-left py-3 px-4 font-semibold text-gray-700">Role</th>
              <th class="text-left py-3 px-4 font-semibold text-gray-700">Created</th>
              <th class="text-left py-3 px-4 font-semibold text-gray-700">Last Activity</th>
              <th class="text-left py-3 px-4 font-semibold text-gray-700">Status</th>
              <th class="text-left py-3 px-4 font-semibold text-gray-700">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            <tr v-if="loading">
              <td colspan="7" class="py-10 text-center text-gray-400 text-sm">Loading users…</td>
            </tr>
            <tr v-else-if="filteredUsers.length === 0">
              <td colspan="7" class="py-10 text-center text-gray-400 text-sm">No users match the current filters.</td>
            </tr>
            <tr v-else v-for="user in filteredUsers" :key="user.id" class="hover:bg-gray-50 transition-colors">
              <td class="py-3 px-4 font-medium text-gray-900">{{ user.name }}</td>
              <td class="py-3 px-4 text-gray-600">{{ user.email }}</td>
              <td class="py-3 px-4">
                <span :class="user.role === 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700'" class="px-2 py-0.5 rounded-full text-xs font-semibold capitalize">
                  {{ user.role }}
                </span>
              </td>
              <td class="py-3 px-4 text-gray-600">{{ user.created_at }}</td>
              <td class="py-3 px-4 text-gray-600">{{ user.last_activity }}</td>
              <td class="py-3 px-4">
                <span :class="user.status === 'Active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600'" class="px-2 py-0.5 rounded-full text-xs font-semibold">
                  {{ user.status }}
                </span>
              </td>
              <td class="py-3 px-4">
                <div class="flex items-center gap-2">
                  <button
                    @click="openEditModal(user)"
                    class="p-1.5 hover:bg-yellow-100 rounded transition-colors"
                    title="Edit Account"
                  >
                    <svg class="h-4 w-4 text-yellow-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                    </svg>
                  </button>
                  <button
                    @click="openDeleteModal(user)"
                    :disabled="deletingId === user.id"
                    class="p-1.5 hover:bg-red-100 rounded transition-colors disabled:opacity-50"
                    title="Delete Account"
                  >
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

      <div class="flex items-center justify-between px-6 py-4 border-t border-gray-100">
        <p class="text-sm text-gray-500">
          Showing <span class="font-semibold">{{ filteredUsers.length }}</span> of
          <span class="font-semibold">{{ users.length }}</span> users
        </p>
      </div>
    </div>

    <div v-if="isModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50 p-4">
      <div class="w-full max-w-lg bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
          <div>
            <h3 class="text-lg font-semibold text-gray-900">{{ isEditing ? 'Edit User' : 'Add User' }}</h3>
            <p class="text-sm text-gray-500">
              {{ isEditing ? 'Update account details and access level.' : 'Create a new account with a starting password.' }}
            </p>
          </div>
          <button @click="closeModal" class="text-gray-400 hover:text-gray-600 text-xl leading-none">×</button>
        </div>

        <form @submit.prevent="submitForm" class="p-6 space-y-4">
          <div v-if="formError" class="bg-red-50 border border-red-200 rounded-lg p-3 text-sm text-red-700">
            {{ formError }}
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
            <input v-model="form.name" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500" />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input v-model="form.email" type="email" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500" />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
            <select v-model="form.role" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500 bg-white">
              <option value="user">User</option>
              <option value="admin">Admin</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Account Status</label>
            <select v-model="form.status" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500 bg-white">
              <option value="Active">Active</option>
              <option value="Inactive">Inactive</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
              {{ isEditing ? 'New Password (optional)' : 'Password' }}
            </label>
            <input
              v-model="form.password"
              type="password"
              :placeholder="isEditing ? 'Leave blank to keep current password' : 'Enter a secure password'"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500"
            />
            <p class="text-xs text-gray-500 mt-1">
              {{ isEditing ? 'Fill this only when you need to replace the existing password.' : 'Password must be at least 8 characters.' }}
            </p>
          </div>

          <div class="flex items-center justify-end gap-3 pt-2">
            <button type="button" @click="closeModal" class="px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50 transition-colors">
              Cancel
            </button>
            <button
              type="submit"
              :disabled="saving"
              class="px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-semibold hover:bg-green-700 transition-colors disabled:opacity-50"
            >
              {{ saving ? 'Saving...' : (isEditing ? 'Save Changes' : 'Create User') }}
            </button>
          </div>
        </form>
      </div>
    </div>

    <div v-if="isDeleteModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50 p-4">
      <div class="w-full max-w-md bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
          <h3 class="text-lg font-semibold text-gray-900">Delete User</h3>
          <p class="text-sm text-gray-500 mt-1">This action permanently removes the selected account.</p>
        </div>

        <div class="px-6 py-5 space-y-3">
          <p class="text-sm text-gray-700">
            Delete <span class="font-semibold text-gray-900">{{ pendingDeleteUser?.name }}</span>
            (<span class="text-gray-600">{{ pendingDeleteUser?.email }}</span>)?
          </p>
          <p class="text-sm text-red-600">This cannot be undone.</p>
        </div>

        <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-gray-100">
          <button
            type="button"
            @click="closeDeleteModal"
            :disabled="deletingId !== null"
            class="px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50 transition-colors disabled:opacity-50"
          >
            Cancel
          </button>
          <button
            type="button"
            @click="removeUser"
            :disabled="deletingId !== null"
            class="px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-semibold hover:bg-red-700 transition-colors disabled:opacity-50"
          >
            {{ deletingId !== null ? 'Deleting...' : 'Delete User' }}
          </button>
        </div>
      </div>
    </div>

  </div>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue'
import { adminService } from '@/services/api'

const searchTerm = ref('')
const filterRole = ref('All')
const filterStatus = ref('All')
const loading = ref(true)
const saving = ref(false)
const deletingId = ref(null)
const pageError = ref('')
const pageMessage = ref('')
const formError = ref('')
const users = ref([])
const isModalOpen = ref(false)
const isDeleteModalOpen = ref(false)
const isEditing = ref(false)
const editingUserId = ref(null)
const pendingDeleteUser = ref(null)
const form = ref(defaultForm())

function defaultForm() {
  return {
    name: '',
    email: '',
    role: 'user',
    status: 'Active',
    password: '',
  }
}

function normalizeError(error, fallback) {
  return error.response?.data?.message
    || error.response?.data?.error
    || Object.values(error.response?.data?.errors ?? {}).flat().join(' ')
    || error.message
    || fallback
}

async function fetchUsers() {
  loading.value = true
  pageError.value = ''

  try {
    const response = await adminService.getUsers()
    users.value = response.data ?? []
  } catch (error) {
    pageError.value = normalizeError(error, 'Failed to load users.')
  } finally {
    loading.value = false
  }
}

function openCreateModal() {
  isEditing.value = false
  editingUserId.value = null
  form.value = defaultForm()
  formError.value = ''
  isModalOpen.value = true
}

function openEditModal(user, passwordOnly = false) {
  isEditing.value = true
  editingUserId.value = user.id
  form.value = {
    name: user.name,
    email: user.email,
    role: user.role,
    status: user.status,
    password: '',
  }
  formError.value = ''
  isModalOpen.value = true
}

function closeModal(force = false) {
  if (saving.value && !force) return
  isModalOpen.value = false
  formError.value = ''
}

function openDeleteModal(user) {
  pendingDeleteUser.value = user
  isDeleteModalOpen.value = true
  pageError.value = ''
  pageMessage.value = ''
}

function closeDeleteModal(force = false) {
  if (deletingId.value !== null && !force) return
  isDeleteModalOpen.value = false
  pendingDeleteUser.value = null
}

async function submitForm() {
  saving.value = true
  formError.value = ''
  pageError.value = ''
  pageMessage.value = ''

  const payload = {
    name: form.value.name.trim(),
    email: form.value.email.trim(),
    role: form.value.role,
    account_status: form.value.status,
  }

  if (form.value.password) {
    payload.password = form.value.password
  }

  try {
    if (isEditing.value) {
      const response = await adminService.updateUser(editingUserId.value, payload)
      const updatedUser = response.data
      users.value = users.value.map(user => user.id === updatedUser.id ? updatedUser : user)
      pageMessage.value = 'User updated successfully.'
    } else {
      const response = await adminService.createUser({
        ...payload,
        password: form.value.password,
      })
      users.value = [response.data, ...users.value]
      pageMessage.value = 'User created successfully.'
    }

    closeModal(true)
  } catch (error) {
    formError.value = normalizeError(error, 'Failed to save user.')
  } finally {
    saving.value = false
  }
}

async function removeUser() {
  const user = pendingDeleteUser.value
  if (!user) return

  deletingId.value = user.id
  pageError.value = ''
  pageMessage.value = ''

  try {
    await adminService.deleteUser(user.id)
    users.value = users.value.filter(entry => entry.id !== user.id)
    pageMessage.value = 'User deleted successfully.'
    closeDeleteModal(true)
  } catch (error) {
    pageError.value = normalizeError(error, 'Failed to delete user.')
  } finally {
    deletingId.value = null
  }
}

onMounted(fetchUsers)

const filteredUsers = computed(() =>
  users.value.filter(user => {
    const query = searchTerm.value.trim().toLowerCase()
    const matchesSearch = query === ''
      || user.name.toLowerCase().includes(query)
      || user.email.toLowerCase().includes(query)
    const matchesRole = filterRole.value === 'All' || user.role === filterRole.value
    const matchesStatus = filterStatus.value === 'All' || user.status === filterStatus.value

    return matchesSearch && matchesRole && matchesStatus
  })
)
</script>
