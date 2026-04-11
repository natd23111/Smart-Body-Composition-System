<template>
  <div class="space-y-6">

    <!-- Page Header -->
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-3xl font-bold text-gray-900">Recommendation Templates</h1>
        <p class="text-gray-500 mt-1">Manage AI-generated health recommendation templates</p>
      </div>
      <button
        @click="showCreateModal = true"
        class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-medium hover:bg-green-700 transition-colors"
      >
        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line>
        </svg>
        Create Template
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
            placeholder="Search by ID or title..."
            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500"
          />
        </div>
        <select
          v-model="filterType"
          class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500"
        >
          <option value="">All Types</option>
          <option value="Hydration">Hydration</option>
          <option value="Exercise">Exercise</option>
          <option value="Sleep">Sleep</option>
          <option value="Nutrition">Nutrition</option>
        </select>
        <select
          v-model="filterStatus"
          class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500"
        >
          <option value="">All Statuses</option>
          <option value="Active">Active</option>
          <option value="Archived">Archived</option>
        </select>
      </div>
    </div>

    <!-- Template Cards -->
    <div v-if="filteredTemplates.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
      <div
        v-for="template in filteredTemplates"
        :key="template.id"
        class="bg-white rounded-lg shadow border border-gray-200 p-5 flex flex-col gap-3"
      >
        <!-- Card Header -->
        <div class="flex items-start justify-between">
          <div class="flex items-center gap-3">
            <div :class="['p-2 rounded-lg', typeIcon(template.type).bg]">
              <svg class="h-5 w-5" :class="typeIcon(template.type).color" v-html="typeIcon(template.type).path" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></svg>
            </div>
            <div>
              <p class="font-semibold text-gray-900 text-sm">{{ template.title }}</p>
              <p class="font-mono text-xs text-gray-400">{{ template.templateId }}</p>
            </div>
          </div>
          <span :class="['px-2 py-0.5 rounded-full text-xs font-semibold', template.status === 'Active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500']">
            {{ template.status }}
          </span>
        </div>

        <!-- Content -->
        <p class="text-sm text-gray-600 leading-relaxed">{{ template.content }}</p>

        <!-- Footer -->
        <div class="flex items-center justify-between pt-2 border-t border-gray-100">
          <span :class="['px-2 py-0.5 rounded text-xs font-medium', typeIcon(template.type).badge]">
            {{ template.type }}
          </span>
          <div class="flex gap-2">
            <button class="p-1.5 rounded hover:bg-yellow-50 text-yellow-600 hover:text-yellow-700 transition-colors" title="Edit template">
              <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
              </svg>
            </button>
            <button class="p-1.5 rounded hover:bg-red-50 text-red-500 hover:text-red-700 transition-colors" title="Remove template">
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

  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const searchTerm = ref('')
const filterType = ref('')
const filterStatus = ref('')
const showCreateModal = ref(false)

const templates = [
  {
    id: 1,
    templateId: 'TMPL-HYD-001',
    title: 'Daily Hydration Boost',
    type: 'Hydration',
    status: 'Active',
    content: 'Your body water percentage is below the optimal range. Aim for at least 2.5 litres of water daily and reduce high-sodium foods to support proper hydration.',
  },
  {
    id: 2,
    templateId: 'TMPL-HYD-002',
    title: 'Post-Workout Rehydration',
    type: 'Hydration',
    status: 'Active',
    content: 'After each workout, replenish fluids within 30 minutes. Aim for approximately 500 ml of water or a low-sugar electrolyte drink to restore balance.',
  },
  {
    id: 3,
    templateId: 'TMPL-NUT-001',
    title: 'Balanced Caloric Intake',
    type: 'Nutrition',
    status: 'Active',
    content: 'Your current body fat is above the healthy range. Consider reducing calorie-dense snacks and increasing fibre-rich vegetables and lean protein in your meals.',
  },
  {
    id: 4,
    templateId: 'TMPL-EXE-001',
    title: 'Strength Training Plan',
    type: 'Exercise',
    status: 'Active',
    content: 'Your muscle-to-body-weight ratio is below the recommended threshold. Incorporate 2–3 strength training sessions per week, focusing on compound movements like squats and rows.',
  },
  {
    id: 5,
    templateId: 'TMPL-SLP-001',
    title: 'Sleep Recovery Protocol',
    type: 'Sleep',
    status: 'Active',
    content: 'Adequate sleep is essential for muscle recovery and fat metabolism. Target 7–9 hours per night and maintain a consistent sleep schedule, even on weekends.',
  },
  {
    id: 6,
    templateId: 'TMPL-EXE-006',
    title: 'Low-Impact Cardio Starter',
    type: 'Exercise',
    status: 'Archived',
    content: 'Begin with 20–30 minutes of low-impact aerobics (walking, cycling) three times a week to build baseline cardiovascular endurance before progressing to higher intensity.',
  },
]

const activeCount = computed(() => templates.filter(t => t.status === 'Active').length)
const archivedCount = computed(() => templates.filter(t => t.status === 'Archived').length)

const filteredTemplates = computed(() =>
  templates.filter(t => {
    const matchSearch = !searchTerm.value ||
      t.templateId.toLowerCase().includes(searchTerm.value.toLowerCase()) ||
      t.title.toLowerCase().includes(searchTerm.value.toLowerCase())
    const matchType = !filterType.value || t.type === filterType.value
    const matchStatus = !filterStatus.value || t.status === filterStatus.value
    return matchSearch && matchType && matchStatus
  })
)

function typeIcon(type) {
  const map = {
    Hydration: {
      bg: 'bg-blue-100',
      color: 'text-blue-600',
      badge: 'bg-blue-100 text-blue-700',
      path: '<path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z"></path>',
    },
    Exercise: {
      bg: 'bg-orange-100',
      color: 'text-orange-600',
      badge: 'bg-orange-100 text-orange-700',
      path: '<circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline>',
    },
    Sleep: {
      bg: 'bg-purple-100',
      color: 'text-purple-600',
      badge: 'bg-purple-100 text-purple-700',
      path: '<path d="M21 12.79A9 9 0 1 1 11.21 3a7 7 0 0 0 9.79 9.79z"></path>',
    },
    Nutrition: {
      bg: 'bg-green-100',
      color: 'text-green-600',
      badge: 'bg-green-100 text-green-700',
      path: '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>',
    },
  }
  return map[type] || map.Nutrition
}
</script>
