<template>
  <div class="space-y-6">

    <!-- Page Header -->
    <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
      <h2 class="text-2xl font-bold text-gray-900 mb-1">AI-Generated Health Recommendations</h2>
      <p class="text-gray-600">Personalised health advice based on your metrics and trends. Each recommendation includes an explanation of the AI's reasoning.</p>
    </div>

    <!-- Loading -->
    <div v-if="loading" class="text-center py-16 text-gray-500">
      <div class="text-3xl mb-3">⏳</div>
      <p>Loading recommendations...</p>
    </div>

    <!-- No measurements yet -->
    <div v-else-if="!meta.has_measurement" class="bg-white rounded-lg shadow border border-gray-200 p-12 text-center">
      <div class="text-5xl mb-4">📏</div>
      <h3 class="text-xl font-semibold mb-2 text-gray-900">No Measurements Yet</h3>
      <p class="text-gray-600">Log your first body composition measurement to receive personalised AI recommendations.</p>
    </div>

    <template v-else>
      <!-- Summary Stats -->
      <div class="grid grid-cols-3 gap-4">
        <div class="bg-white rounded-lg shadow border border-gray-200 p-6 text-center">
          <div class="text-2xl font-bold text-gray-900">{{ activeRecommendations.length }}</div>
          <p class="text-sm text-gray-500 mt-1">Active Recommendations</p>
        </div>
        <div class="bg-white rounded-lg shadow border border-gray-200 p-6 text-center">
          <div class="text-2xl font-bold text-red-600">{{ activeRecommendations.filter(r => r.priority === 'high').length }}</div>
          <p class="text-sm text-gray-500 mt-1">High Priority</p>
        </div>
        <div class="bg-white rounded-lg shadow border border-gray-200 p-6 text-center">
          <div class="text-2xl font-bold text-green-600">{{ activeRecommendations.filter(r => r.priority === 'low').length }}</div>
          <p class="text-sm text-gray-500 mt-1">Positive Feedback</p>
        </div>
      </div>

      <!-- Toolbar -->
      <div class="flex items-center justify-between">
        <p class="text-sm text-gray-500">Last synced: {{ formatDate(meta.synced_at) }}</p>
        <button
          @click="generate"
          :disabled="generating"
          class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 disabled:opacity-50 transition-colors"
        >
          {{ generating ? 'Refreshing...' : '🔄 Refresh Recommendations' }}
        </button>
      </div>

      <!-- Recommendation Cards -->
      <div class="space-y-4">
        <div
          v-for="rec in activeRecommendations"
          :key="rec.id"
          class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden"
        >
          <!-- Card Header -->
          <div class="px-6 pt-6 pb-2">
            <div class="flex items-start gap-4">
              <div class="text-4xl leading-none">{{ getIcon(rec.icon) }}</div>
              <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 mb-1 flex-wrap">
                  <h3 class="text-lg font-semibold text-gray-900">{{ rec.title }}</h3>
                  <span :class="priorityClass(rec.priority)" class="px-2 py-0.5 rounded-full text-xs font-semibold">
                    {{ capitalize(rec.priority) }} Priority
                  </span>
                  <span class="px-2 py-0.5 bg-blue-100 text-blue-700 rounded-full text-xs font-medium">
                    {{ rec.recommendation_type }}
                  </span>
                </div>
                <p class="text-xs text-gray-400">Synced: {{ formatDate(rec.last_synced_at) }}</p>
              </div>
            </div>
          </div>

          <!-- Card Body -->
          <div class="px-6 pb-6 space-y-4">
            <p class="text-base text-gray-700 leading-relaxed">{{ rec.summary }}</p>

            <!-- Action Buttons -->
            <div class="flex gap-2 flex-wrap items-center">
              <button
                @click="toggleExpand(rec.id)"
                class="flex items-center gap-1.5 px-3 py-1.5 border border-gray-300 text-sm text-gray-700 rounded-lg hover:bg-gray-50 transition-colors"
              >
                <span>{{ expandedId === rec.id ? '▲' : '▼' }}</span>
                {{ expandedId === rec.id ? 'Hide Explanation' : 'Why am I seeing this?' }}
              </button>
              <button
                @click="markDone(rec)"
                :disabled="updatingId === rec.id"
                class="ml-auto px-3 py-1.5 border border-red-300 text-sm text-red-600 rounded-lg hover:bg-red-50 transition-colors disabled:opacity-50"
              >
                {{ updatingId === rec.id ? 'Dismissing...' : '✕ Dismiss' }}
              </button>
            </div>

            <!-- Expanded Explanation Panel -->
            <div
              v-if="expandedId === rec.id"
              class="border-t pt-4 mt-2 space-y-4 bg-blue-50 -mx-6 -mb-6 px-6 py-4 rounded-b-lg"
            >
              <!-- What triggered this -->
              <div>
                <h4 class="font-semibold text-base mb-2 flex items-center gap-2 text-gray-900">
                  <span class="text-blue-600">⚡</span> What Triggered This Recommendation?
                </h4>
                <div class="bg-white p-3 rounded border border-blue-200 space-y-3">
                  <div
                    v-for="(basis, i) in rec.metric_basis"
                    :key="i"
                    class="pb-2 border-b border-gray-100 last:border-0 last:pb-0"
                  >
                    <div class="flex items-center justify-between mb-0.5">
                      <span class="font-medium text-gray-900 text-sm">{{ basis.label }}</span>
                      <span class="font-semibold text-gray-800 text-sm">{{ basis.value }}</span>
                    </div>
                    <p class="text-xs text-gray-600">{{ basis.insight }}</p>
                  </div>
                </div>
              </div>

              <!-- Recommended actions -->
              <div>
                <h4 class="font-semibold text-base mb-2 text-gray-900">📋 Recommended Actions</h4>
                <div class="bg-white p-3 rounded border border-blue-200 space-y-2">
                  <div v-for="(detail, i) in rec.details" :key="i" class="flex gap-3">
                    <div class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-700 rounded-full flex items-center justify-center text-xs font-semibold">
                      {{ i + 1 }}
                    </div>
                    <p class="text-sm text-gray-700 pt-0.5">{{ detail }}</p>
                  </div>
                </div>
              </div>

              <!-- Measurement snapshot -->
              <div v-if="rec.measurement_snapshot">
                <h4 class="font-semibold text-base mb-2 text-gray-900">📊 Measurement Used</h4>
                <div class="bg-white p-3 rounded border border-blue-200">
                  <div class="grid grid-cols-2 gap-x-6 gap-y-1 text-sm">
                    <template v-for="(val, key) in filteredSnapshot(rec.measurement_snapshot)" :key="key">
                      <span class="text-gray-500">{{ snapshotLabel(key) }}</span>
                      <span class="font-semibold text-gray-900">{{ val ?? '—' }}</span>
                    </template>
                  </div>
                  <p class="text-xs text-gray-400 mt-2 border-t pt-2">Date: {{ rec.measurement_snapshot.measurement_date }}</p>
                </div>
              </div>

              <!-- Confidence badge -->
              <div class="flex items-center gap-2 text-sm">
                <span class="text-gray-600 font-medium">Confidence:</span>
                <span :class="confidenceClass(rec.confidence)" class="px-2 py-0.5 rounded-full text-xs font-semibold">
                  {{ capitalize(rec.confidence) }}
                </span>
              </div>

              <!-- Disclaimer -->
              <p class="text-xs text-gray-500 italic border-t pt-3">
                💡 {{ rec.disclaimer }}
              </p>
            </div>
          </div>
        </div>
      </div>

      <!-- Empty State (all dismissed) -->
      <div v-if="activeRecommendations.length === 0" class="bg-white rounded-lg shadow border border-gray-200 p-12 text-center">
        <div class="text-4xl mb-4">✨</div>
        <h3 class="text-xl font-semibold mb-2 text-gray-900">No Active Recommendations</h3>
        <p class="text-gray-600">All recommendations have been dismissed. Click "Refresh Recommendations" to reload.</p>
      </div>
    </template>

    <!-- Error Banner -->
    <div v-if="error" class="bg-red-50 border border-red-200 rounded-lg p-4 text-red-700 text-sm">
      {{ error }}
    </div>

  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { getRecommendations, generateRecommendations, updateRecommendationStatus } from '@/services/authService'

const loading = ref(true)
const generating = ref(false)
const error = ref('')
const recommendations = ref([])
const meta = ref({ has_measurement: false })
const expandedId = ref(null)
const dismissedIds = ref(new Set())
const updatingId = ref(null)

const activeRecommendations = computed(() =>
  recommendations.value.filter(r => !dismissedIds.value.has(r.id))
)

const loadRecommendations = async () => {
  error.value = ''
  try {
    const result = await getRecommendations()
    recommendations.value = result.data ?? []
    meta.value = result.meta ?? { has_measurement: false }
  } catch (e) {
    error.value = e.message || 'Failed to load recommendations.'
  } finally {
    loading.value = false
  }
}

const generate = async () => {
  generating.value = true
  error.value = ''
  try {
    const result = await generateRecommendations()
    recommendations.value = result.data ?? []
    meta.value = result.meta ?? { has_measurement: false }
    dismissedIds.value = new Set()
    expandedId.value = null
  } catch (e) {
    error.value = e.message || 'Failed to refresh recommendations.'
  } finally {
    generating.value = false
  }
}

const markDone = async (rec) => {
  updatingId.value = rec.id
  try {
    await updateRecommendationStatus(rec.id, 'completed')
    dismissedIds.value = new Set([...dismissedIds.value, rec.id])
    if (expandedId.value === rec.id) expandedId.value = null
  } catch (e) {
    error.value = e.message
  } finally {
    updatingId.value = null
  }
}

const toggleExpand = (id) => {
  expandedId.value = expandedId.value === id ? null : id
}

const getIcon = (icon) => {
  const map = { droplet: '💧', apple: '🍎', activity: '🏃', 'trending-up': '📈', heart: '❤️' }
  return map[icon] ?? '💡'
}

const priorityClass = (priority) => ({
  high: 'bg-red-100 text-red-800',
  medium: 'bg-yellow-100 text-yellow-800',
  low: 'bg-green-100 text-green-800',
}[priority] ?? 'bg-gray-100 text-gray-800')

const confidenceClass = (confidence) => ({
  high: 'bg-green-100 text-green-800',
  medium: 'bg-yellow-100 text-yellow-800',
  low: 'bg-gray-100 text-gray-800',
}[confidence] ?? 'bg-gray-100 text-gray-800')

const capitalize = (s) => s ? s.charAt(0).toUpperCase() + s.slice(1) : ''

const formatDate = (iso) => {
  if (!iso) return '—'
  return new Date(iso).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' })
}

const SNAPSHOT_LABELS = {
  weight_kg: 'Weight (kg)',
  body_fat_percent: 'Body Fat %',
  body_water_percent: 'Body Water %',
  muscle_mass: 'Muscle Mass (kg)',
  visceral_fat: 'Visceral Fat',
  physical_rating: 'Physical Rating',
  bmi: 'BMI',
  activity_level: 'Activity Level',
}

const filteredSnapshot = (snapshot) =>
  Object.fromEntries(
    Object.entries(snapshot).filter(([k]) => k !== 'measurement_date' && SNAPSHOT_LABELS[k] !== undefined)
  )

const snapshotLabel = (key) => SNAPSHOT_LABELS[key] ?? key

onMounted(loadRecommendations)
</script>

