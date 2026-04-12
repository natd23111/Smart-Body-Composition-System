<template>
  <div class="space-y-6">

    <!-- Page Header -->
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-3xl font-bold text-gray-900">Recommendation Templates</h1>
        <p class="text-gray-500 mt-1">Manage health recommendation templates shown to users</p>
      </div>
      <button
        @click="openCreateModal"
        class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-medium hover:bg-green-700 transition-colors"
      >
        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line>
        </svg>
        New Template
      </button>
    </div>

    <!-- Summary Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
        <p class="text-sm text-gray-500 mb-1">Total Templates</p>
        <p class="text-2xl font-bold text-gray-900">{{ templates.length }}</p>
      </div>
      <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
        <p class="text-sm text-gray-500 mb-1">Active</p>
        <p class="text-2xl font-bold text-green-600">{{ activeCount }}</p>
      </div>
      <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
        <p class="text-sm text-gray-500 mb-1">Archived</p>
        <p class="text-2xl font-bold text-gray-400">{{ archivedCount }}</p>
      </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow border border-gray-200 p-4">
      <div class="flex flex-col md:flex-row gap-3">
        <div class="flex-1 relative">
          <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line>
          </svg>
          <input
            v-model="searchTerm"
            type="text"
            placeholder="Search by code or title..."
            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500"
          />
        </div>
        <select v-model="filterType" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
          <option value="">All Types</option>
          <option value="Hydration">Hydration</option>
          <option value="Exercise">Exercise</option>
          <option value="Sleep">Sleep</option>
          <option value="Nutrition">Nutrition</option>
          <option value="Recovery">Recovery</option>
        </select>
        <select v-model="filterStatus" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
          <option value="">All Statuses</option>
          <option value="Active">Active</option>
          <option value="Archived">Archived</option>
        </select>
      </div>
    </div>

    <!-- Loading -->
    <div v-if="loading" class="bg-white rounded-lg shadow border border-gray-200 py-16 text-center">
      <p class="text-gray-400">Loading templates...</p>
    </div>

    <!-- Template Cards -->
    <div v-else-if="filteredTemplates.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
      <div
        v-for="template in filteredTemplates"
        :key="template.id"
        class="bg-white rounded-lg shadow border border-gray-200 p-5 flex flex-col gap-3"
      >
        <!-- Card Header -->
        <div class="flex items-start justify-between">
          <div class="flex items-center gap-3">
            <div :class="['p-2 rounded-lg', typeStyle(template.type).bg]">
              <svg class="h-5 w-5" :class="typeStyle(template.type).color" v-html="typeStyle(template.type).path" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></svg>
            </div>
            <div>
              <p class="font-semibold text-gray-900 text-sm">{{ template.title }}</p>
            </div>
          </div>
          <span :class="['px-2 py-0.5 rounded-full text-xs font-semibold', template.status === 'Active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500']">
            {{ template.status }}
          </span>
        </div>

        <!-- Summary -->
        <p class="text-sm text-gray-600 leading-relaxed">{{ template.summary }}</p>

        <!-- Bullet Points Preview -->
        <ul class="text-xs text-gray-500 space-y-1 pl-3">
          <li v-for="(point, i) in (template.details || []).slice(0, 2)" :key="i" class="flex gap-1">
            <span class="text-gray-400 mt-0.5">•</span>
            <span>{{ point }}</span>
          </li>
          <li v-if="(template.details || []).length > 2" class="text-gray-400 italic">+{{ template.details.length - 2 }} more...</li>
        </ul>

        <!-- Footer -->
        <div class="flex items-center justify-between pt-2 border-t border-gray-100">
          <div class="flex items-center gap-2">
            <span :class="['px-2 py-0.5 rounded text-xs font-medium', typeStyle(template.type).badge]">
              {{ template.type }}
            </span>
            <span :class="['px-2 py-0.5 rounded text-xs font-medium', priorityBadge(template.priority)]">
              {{ template.priority }}
            </span>
          </div>
          <div class="flex gap-1">
            <button @click="openEditModal(template)" class="p-1.5 rounded hover:bg-yellow-50 text-yellow-600 hover:text-yellow-700 transition-colors" title="Edit template">
              <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
              </svg>
            </button>
            <button @click="confirmDelete(template)" class="p-1.5 rounded hover:bg-red-50 text-red-500 hover:text-red-700 transition-colors" title="Delete template">
              <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="3 6 5 6 21 6"></polyline>
                <path d="M19 6l-1 14H6L5 6"></path>
                <path d="M10 11v6"></path><path d="M14 11v6"></path>
                <path d="M9 6V4h6v2"></path>
              </svg>
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Empty state -->
    <div v-else class="bg-white rounded-lg shadow border border-gray-200 py-16 text-center">
      <p class="text-gray-400">No templates match your search.</p>
    </div>

    <!-- Create / Edit Modal -->
    <div v-if="modalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/30 backdrop-blur-sm p-4">
      <div class="bg-white rounded-xl shadow-xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
          <h2 class="text-lg font-semibold text-gray-900">{{ editingTemplate ? 'Edit Template' : 'New Template' }}</h2>
          <button @click="closeModal" class="text-gray-400 hover:text-gray-600">
            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
          </button>
        </div>

        <form @submit.prevent="saveTemplate" class="p-6 space-y-4">
          <!-- template_id and template_code - only on create -->
          <div v-if="!editingTemplate" class="grid grid-cols-2 gap-3">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Template Key *</label>
              <input v-model="form.template_id" type="text" placeholder="e.g. sleep-recovery" required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500" />
              <p class="text-xs text-gray-400 mt-1">Unique slug, no spaces</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Template Code *</label>
              <input v-model="form.template_code" type="text" placeholder="e.g. TMPL-SLP-001" required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500" />
            </div>
          </div>

          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Type *</label>
              <select v-model="form.type" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                <option value="">Select type</option>
                <option>Hydration</option>
                <option>Exercise</option>
                <option>Nutrition</option>
                <option>Recovery</option>
                <option>Sleep</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Priority *</label>
              <select v-model="form.priority" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                <option value="high">High</option>
                <option value="medium">Medium</option>
                <option value="low">Low</option>
              </select>
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
            <select v-model="form.status" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
              <option value="Active">Active</option>
              <option value="Archived">Archived</option>
            </select>
            <p class="text-xs text-gray-400 mt-1">Archived templates are hidden from users even if their conditions are met.</p>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Title *</label>
            <input v-model="form.title" type="text" required placeholder="Recommendation title shown to user"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500" />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Summary *</label>
            <textarea v-model="form.summary" rows="2" required placeholder="Brief explanation shown at the top of the card"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500 resize-none"></textarea>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
              Bullet Points *
              <span class="font-normal text-gray-400 ml-1">(one per line)</span>
            </label>
            <textarea v-model="detailsText" rows="5" required placeholder="Each line becomes one bullet point shown to the user"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500 resize-none font-mono"></textarea>
          </div>

          <div v-if="formError" class="p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700">
            {{ formError }}
          </div>

          <div class="flex justify-end gap-3 pt-2">
            <button type="button" @click="closeModal" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg text-sm hover:bg-gray-50 transition-colors">
              Cancel
            </button>
            <button type="submit" :disabled="saving"
              class="px-4 py-2 bg-green-600 hover:bg-green-700 disabled:bg-green-400 text-white rounded-lg text-sm font-medium transition-colors flex items-center gap-2">
              <svg v-if="saving" class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
              </svg>
              {{ saving ? 'Saving...' : (editingTemplate ? 'Save Changes' : 'Create Template') }}
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div v-if="deleteModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/30 backdrop-blur-sm p-4">
      <div class="bg-white rounded-xl shadow-xl w-full max-w-sm p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-2">Delete Template</h3>
        <p class="text-sm text-gray-600 mb-1">Are you sure you want to delete:</p>
        <p class="text-sm font-medium text-gray-900 mb-4">{{ templateToDelete?.title }}</p>
        <p class="text-xs text-amber-700 bg-amber-50 border border-amber-200 rounded-lg p-3 mb-4">
          If this template is one of the engine's built-in conditions, deleting it will cause the engine to fall back to its hardcoded text. Consider archiving instead.
        </p>
        <div class="flex justify-end gap-3">
          <button @click="deleteModalOpen = false" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg text-sm hover:bg-gray-50 transition-colors">
            Cancel
          </button>
          <button @click="deleteTemplate" :disabled="saving" class="px-4 py-2 bg-red-600 hover:bg-red-700 disabled:bg-red-400 text-white rounded-lg text-sm font-medium transition-colors">
            {{ saving ? 'Deleting...' : 'Delete' }}
          </button>
        </div>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'

const templates = ref([])
const loading = ref(true)
const saving = ref(false)
const formError = ref('')

const searchTerm = ref('')
const filterType = ref('')
const filterStatus = ref('')

const modalOpen = ref(false)
const editingTemplate = ref(null)
const deleteModalOpen = ref(false)
const templateToDelete = ref(null)

const form = ref({
  template_id: '',
  template_code: '',
  type: '',
  title: '',
  summary: '',
  priority: 'medium',
  status: 'Active',
})
const detailsText = ref('')

// Stats

const activeCount = computed(() => templates.value.filter(t => t.status === 'Active').length)
const archivedCount = computed(() => templates.value.filter(t => t.status === 'Archived').length)

const filteredTemplates = computed(() =>
  templates.value.filter(t => {
    const matchSearch = !searchTerm.value ||
      t.template_code.toLowerCase().includes(searchTerm.value.toLowerCase()) ||
      t.title.toLowerCase().includes(searchTerm.value.toLowerCase())
    const matchType = !filterType.value || t.type === filterType.value
    const matchStatus = !filterStatus.value || t.status === filterStatus.value
    return matchSearch && matchType && matchStatus
  })
)

// API

const authHeaders = () => ({
  'Content-Type': 'application/json',
  'Authorization': `Bearer ${localStorage.getItem('auth_token')}`,
})

const loadTemplates = async () => {
  try {
    loading.value = true
    const res = await fetch('/api/admin/templates', { headers: authHeaders() })
    const data = await res.json()
    templates.value = data
  } catch (e) {
    console.error('Failed to load templates', e)
  } finally {
    loading.value = false
  }
}

onMounted(loadTemplates)

// Modal helpers

const openCreateModal = () => {
  editingTemplate.value = null
  form.value = { template_id: '', template_code: '', type: '', title: '', summary: '', priority: 'medium', status: 'Active' }
  detailsText.value = ''
  formError.value = ''
  modalOpen.value = true
}

const openEditModal = (template) => {
  editingTemplate.value = template
  form.value = {
    type: template.type,
    title: template.title,
    summary: template.summary,
    priority: template.priority,
    status: template.status,
  }
  detailsText.value = (template.details || []).join('\n')
  formError.value = ''
  modalOpen.value = true
}

const closeModal = () => {
  modalOpen.value = false
  editingTemplate.value = null
}

// Save (create or update)

const saveTemplate = async () => {
  formError.value = ''
  const lines = detailsText.value.split('\n').map(l => l.trim()).filter(Boolean)
  if (lines.length === 0) {
    formError.value = 'At least one bullet point is required.'
    return
  }

  const payload = { ...form.value, details: lines }
  const isEdit = !!editingTemplate.value
  const url = isEdit ? `/api/admin/templates/${editingTemplate.value.id}` : '/api/admin/templates'
  const method = isEdit ? 'PUT' : 'POST'

  try {
    saving.value = true
    const res = await fetch(url, { method, headers: authHeaders(), body: JSON.stringify(payload) })
    const data = await res.json()
    if (!res.ok) {
      formError.value = data.message || Object.values(data.errors || {}).flat().join(' ') || 'Failed to save template.'
      return
    }
    if (isEdit) {
      const idx = templates.value.findIndex(t => t.id === editingTemplate.value.id)
      if (idx !== -1) templates.value[idx] = data
    } else {
      templates.value.push(data)
    }
    closeModal()
  } catch (e) {
    formError.value = 'Network error. Please try again.'
  } finally {
    saving.value = false
  }
}

// Delete

const confirmDelete = (template) => {
  templateToDelete.value = template
  deleteModalOpen.value = true
}

const deleteTemplate = async () => {
  try {
    saving.value = true
    const res = await fetch(`/api/admin/templates/${templateToDelete.value.id}`, {
      method: 'DELETE',
      headers: authHeaders(),
    })
    if (!res.ok) throw new Error()
    templates.value = templates.value.filter(t => t.id !== templateToDelete.value.id)
    deleteModalOpen.value = false
  } catch (e) {
    alert('Failed to delete template.')
  } finally {
    saving.value = false
  }
}

// Style helpers

function typeStyle(type) {
  const map = {
    Hydration: {
      bg: 'bg-blue-100', color: 'text-blue-600', badge: 'bg-blue-100 text-blue-700',
      path: '<path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z"></path>',
    },
    Exercise: {
      bg: 'bg-orange-100', color: 'text-orange-600', badge: 'bg-orange-100 text-orange-700',
      path: '<circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline>',
    },
    Sleep: {
      bg: 'bg-purple-100', color: 'text-purple-600', badge: 'bg-purple-100 text-purple-700',
      path: '<path d="M21 12.79A9 9 0 1 1 11.21 3a7 7 0 0 0 9.79 9.79z"></path>',
    },
    Nutrition: {
      bg: 'bg-green-100', color: 'text-green-600', badge: 'bg-green-100 text-green-700',
      path: '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>',
    },
    Recovery: {
      bg: 'bg-teal-100', color: 'text-teal-600', badge: 'bg-teal-100 text-teal-700',
      path: '<path d="M22 12h-4l-3 9L9 3l-3 9H2"></path>',
    },
  }
  return map[type] || map.Nutrition
}

function priorityBadge(priority) {
  return {
    high: 'bg-red-100 text-red-700',
    medium: 'bg-yellow-100 text-yellow-700',
    low: 'bg-gray-100 text-gray-600',
  }[priority] || 'bg-gray-100 text-gray-600'
}
</script>
