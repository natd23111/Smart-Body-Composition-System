<template>
  <div class="space-y-6">
    <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
      <div>
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Recommendations</h2>
        <p class="text-gray-600">Personalized recommendations based on your body composition data.</p>
      </div>
    </div>

    <Transition name="fade">
      <div v-if="successMessage" class="p-4 bg-green-50 border border-green-200 rounded-lg flex items-start gap-3">
        <svg class="h-5 w-5 text-green-600 flex-shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
          <polyline points="22 4 12 14.01 9 11.01"></polyline>
        </svg>
        <p class="text-green-800 text-sm">{{ successMessage }}</p>
      </div>
    </Transition>

    <Transition name="fade">
      <div v-if="errorMessage" class="p-4 bg-red-50 border border-red-200 rounded-lg flex items-start gap-3">
        <svg class="h-5 w-5 text-red-600 flex-shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="12" cy="12" r="10"></circle>
          <line x1="12" y1="8" x2="12" y2="12"></line>
          <line x1="12" y1="16" x2="12.01" y2="16"></line>
        </svg>
        <p class="text-red-800 text-sm">{{ errorMessage }}</p>
      </div>
    </Transition>

    <div v-if="!loading && !meta.has_measurement" class="bg-white rounded-lg shadow border border-dashed border-gray-300 p-10 text-center">
      <h3 class="text-lg font-semibold text-gray-900 mb-2">No body composition record yet</h3>
      <p class="text-gray-600">Record a body composition measurement first so the backend can match the correct recommendation templates.</p>
    </div>

    <div v-else-if="loading && recommendations.length === 0" class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div v-for="index in 4" :key="index" class="bg-white rounded-lg shadow border border-gray-200 p-6 animate-pulse">
        <div class="h-6 w-1/2 bg-gray-200 rounded mb-4"></div>
        <div class="h-4 w-full bg-gray-100 rounded mb-2"></div>
        <div class="h-4 w-5/6 bg-gray-100 rounded"></div>
      </div>
    </div>

    <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div
        v-for="recommendation in recommendations"
        :key="recommendation.id"
        class="bg-white rounded-lg shadow border border-gray-200 p-6 hover:shadow-md transition-shadow cursor-pointer"
        @click="openRecommendation(recommendation)"
      >
        <div class="flex items-start justify-between gap-4">
          <div class="flex items-start gap-3 min-w-0">
            <div :class="iconContainerClass(recommendation.icon)">
              <svg v-if="recommendation.icon === 'droplet'" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 2.69l.34.45C13.74 5 18 10.68 18 14a6 6 0 1 1-12 0c0-3.32 4.26-9 5.66-10.86L12 2.69z"></path>
              </svg>
              <svg v-else-if="recommendation.icon === 'activity'" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
              </svg>
              <svg v-else-if="recommendation.icon === 'moon'" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 3a6 6 0 1 0 9 9 9 9 0 1 1-9-9z"></path>
              </svg>
              <svg v-else-if="recommendation.icon === 'apple'" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 7c1.5-3 4-4 6-4"></path>
                <path d="M17.5 9C19.43 9 21 10.57 21 12.5 21 17.19 16.97 21 12 21S3 17.19 3 12.5C3 10.57 4.57 9 6.5 9c1.54 0 2.85 1 3.32 2.39h4.36C14.65 10 15.96 9 17.5 9z"></path>
                <path d="M12 7c-1.5-3-4-4-6-4"></path>
              </svg>
              <svg v-else-if="recommendation.icon === 'trending-up'" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline>
                <polyline points="16 7 22 7 22 13"></polyline>
              </svg>
              <svg v-else class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
              </svg>
            </div>

            <div class="min-w-0">
              <div class="flex items-center gap-2 mb-1 flex-wrap">
                <h3 class="text-lg font-semibold text-gray-900">{{ recommendation.title }}</h3>
                <span :class="statusClass(recommendation.status)" class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold">
                  {{ statusLabel(recommendation.status) }}
                </span>
              </div>
              <p class="text-xs text-gray-500 mb-3">{{ recommendation.recommendation_type }}</p>
              <p class="text-sm text-gray-600">{{ recommendation.summary }}</p>
            </div>
          </div>
        </div>

        <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between gap-3">
          <span class="text-xs font-medium uppercase tracking-wide text-gray-500">{{ recommendation.priority }} priority</span>
        </div>
      </div>
    </div>

    <div v-if="selectedRecommendation" class="fixed inset-0 flex items-center justify-center z-50 bg-black/20 backdrop-blur-sm px-4" @click.self="closeRecommendation">
      <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl max-h-[90vh] overflow-y-auto relative">
        <div class="p-6 border-b border-gray-200">
          <div class="flex items-start justify-between gap-4">
            <div>
              <p class="text-xs font-medium uppercase tracking-wide text-gray-500 mb-2">{{ selectedRecommendation.recommendation_type }}</p>
              <h3 class="text-xl font-bold text-gray-900">{{ selectedRecommendation.title }}</h3>
              <p class="text-sm text-gray-600 mt-2">{{ selectedRecommendation.summary }}</p>
            </div>

            <button @click="closeRecommendation" class="text-gray-400 hover:text-gray-700 text-2xl leading-none">&times;</button>
          </div>
        </div>

        <div class="p-6 space-y-6">
          <div>
            <p class="text-sm font-semibold text-gray-900 mb-3">Progress</p>
            <div class="flex flex-wrap gap-2">
              <button
                v-for="status in statuses"
                :key="status"
                @click="updateStatus(selectedRecommendation, status)"
                :disabled="statusSaving && selectedRecommendationIdSaving === selectedRecommendation.id"
                :class="progressButtonClass(selectedRecommendation.status, status)"
                class="px-3 py-2 rounded-lg text-sm font-medium transition-colors disabled:opacity-60"
              >
                {{ statusLabel(status) }}
              </button>
            </div>
          </div>

          <div>
            <p class="text-sm font-semibold text-gray-900 mb-3">Recommended Actions</p>
            <ul class="space-y-2">
              <li v-for="(detail, index) in selectedRecommendation.details" :key="index" class="flex items-start gap-3 text-sm text-gray-700">
                <span class="w-2 h-2 rounded-full bg-green-600 mt-1.5 flex-shrink-0"></span>
                <span>{{ detail }}</span>
              </li>
            </ul>
          </div>

          <div v-if="selectedRecommendation.metric_basis?.length">
            <p class="text-sm font-semibold text-gray-900 mb-3">Why This Card Was Matched</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
              <div v-for="metric in selectedRecommendation.metric_basis" :key="`${metric.label}-${metric.value}`" class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                <p class="text-xs uppercase tracking-wide text-gray-500 mb-1">{{ metric.label }}</p>
                <p class="text-lg font-semibold text-gray-900">{{ metric.value }}</p>
                <p class="text-sm text-gray-600 mt-2">{{ metric.insight }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue'
import { healthRecommendationService } from '../services/api'

const recommendations = ref([])
const meta = ref({
  has_measurement: false,
  measurement: null,
})
const loading = ref(false)
const statusSaving = ref(false)
const selectedRecommendationIdSaving = ref(null)
const selectedRecommendation = ref(null)
const successMessage = ref('')
const errorMessage = ref('')
const statuses = ['pending', 'in-progress', 'completed']

onMounted(async () => {
  await refreshRecommendations()
})

async function refreshRecommendations() {
  loading.value = true
  errorMessage.value = ''

  try {
    const response = await healthRecommendationService.getAll()

    recommendations.value = response.data.data || []
    meta.value = response.data.meta || {
      has_measurement: false,
      measurement: null,
    }

    if (selectedRecommendation.value) {
      selectedRecommendation.value = recommendations.value.find(
        (recommendation) => recommendation.id === selectedRecommendation.value.id,
      ) || null
    }
  } catch (error) {
    errorMessage.value = error.response?.data?.message || error.message || 'Failed to load recommendations.'
  } finally {
    loading.value = false
  }
}

function openRecommendation(recommendation) {
  selectedRecommendation.value = recommendation
}

function closeRecommendation() {
  selectedRecommendation.value = null
}

async function updateStatus(recommendation, status) {
  if (!recommendation || recommendation.status === status) {
    return
  }

  statusSaving.value = true
  selectedRecommendationIdSaving.value = recommendation.id
  errorMessage.value = ''

  try {
    const response = await healthRecommendationService.updateStatus(recommendation.id, status)
    const updated = response.data.data
    const index = recommendations.value.findIndex((item) => item.id === updated.id)

    if (index !== -1) {
      recommendations.value[index] = updated
    }

    if (selectedRecommendation.value?.id === updated.id) {
      selectedRecommendation.value = updated
    }

    flashSuccess(`Progress updated to ${statusLabel(status)}.`)
  } catch (error) {
    errorMessage.value = error.response?.data?.message || error.message || 'Failed to update recommendation progress.'
  } finally {
    statusSaving.value = false
    selectedRecommendationIdSaving.value = null
  }
}

function flashSuccess(message) {
  successMessage.value = message

  setTimeout(() => {
    successMessage.value = ''
  }, 3000)
}

function statusLabel(status) {
  switch (status) {
    case 'completed':
      return 'Completed'
    case 'in-progress':
      return 'In Progress'
    default:
      return 'Pending'
  }
}

function statusClass(status) {
  switch (status) {
    case 'completed':
      return 'bg-green-100 text-green-700'
    case 'in-progress':
      return 'bg-blue-100 text-blue-700'
    default:
      return 'bg-gray-100 text-gray-700'
  }
}

function progressButtonClass(currentStatus, status) {
  if (currentStatus === status) {
    return 'bg-green-600 text-white border border-green-600'
  }

  return 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50'
}

function iconContainerClass(icon) {
  switch (icon) {
    case 'droplet':
      return 'p-2 rounded-lg bg-cyan-100 text-cyan-700'
    case 'activity':
      return 'p-2 rounded-lg bg-blue-100 text-blue-700'
    case 'moon':
      return 'p-2 rounded-lg bg-violet-100 text-violet-700'
    case 'apple':
      return 'p-2 rounded-lg bg-amber-100 text-amber-700'
    case 'trending-up':
      return 'p-2 rounded-lg bg-emerald-100 text-emerald-700'
    default:
      return 'p-2 rounded-lg bg-rose-100 text-rose-700'
  }
}
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
