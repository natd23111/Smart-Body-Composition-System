<template>
  <div class="space-y-6">

    <!-- Page Header -->
    <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
      <h2 class="text-2xl font-bold text-gray-900 mb-2">Health Trends & AI Insights</h2>
      <p class="text-gray-600">Comprehensive analysis of your health metrics over time with rule-based insights about trends and patterns.</p>
    </div>

    <!-- Loading -->
    <div v-if="loading" class="text-center py-16 text-gray-500">
      <div class="text-3xl mb-3">⏳</div>
      <p>Loading your trend data...</p>
    </div>

    <template v-if="!loading">
      <div :class="{ 'opacity-50 pointer-events-none': transitioning }" class="transition-opacity duration-300 space-y-6">

      <!-- Controls (always visible) -->
      <div class="bg-white rounded-lg shadow border border-gray-200 p-4 flex flex-col md:flex-row gap-4 items-start md:items-end">
        <div>
          <label class="text-sm font-semibold text-gray-700 mb-2 block">Time Period</label>
          <div class="flex gap-2 flex-wrap">
            <button
              v-for="period in PERIODS"
              :key="period.value"
              @click="selectedPeriod = period.value"
              :class="selectedPeriod === period.value
                ? 'bg-blue-600 text-white border-blue-600'
                : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50'"
              class="px-3 py-1.5 text-sm font-medium border rounded-lg transition-colors"
            >
              {{ period.label }}
            </button>
          </div>
        </div>
        <div class="flex-1">
          <label class="text-sm font-semibold text-gray-700 mb-2 block">Filter by Metric</label>
          <select
            v-model="selectedMetric"
            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white"
          >
            <option value="all">All Metrics</option>
            <option v-for="m in metricOptions" :key="m.key" :value="m.key">{{ m.label }}</option>
          </select>
        </div>
      </div>

      <!-- Not enough data for this period -->
      <div v-if="!meta.has_data" class="bg-white rounded-lg shadow border border-dashed border-gray-300 p-12 text-center">
        <div class="text-5xl mb-4">📏</div>
        <h3 class="text-xl font-semibold mb-2 text-gray-900">Not Enough Data Yet</h3>
        <p class="text-gray-600">Record at least 2 body composition measurements to see trends and insights.</p>
      </div>

      <!-- Overall Summary Card -->
      <div v-if="meta.has_data" class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg p-6">
        <h3 class="text-xl font-bold text-gray-900 mb-3">Your Health Summary</h3>
        <p class="text-gray-800 text-base leading-relaxed">{{ meta.summary }}</p>
        <div class="bg-white p-3 rounded border border-blue-200 mt-4">
          <p class="text-sm text-gray-700">
            <strong>💡 AI Insight:</strong> {{ meta.ai_insight }}
          </p>
        </div>
      </div>

      <!-- No insights for period -->
      <div v-if="meta.has_data && filteredInsights.length === 0" class="bg-white rounded-lg shadow border border-gray-200 p-10 text-center">
        <div class="text-4xl mb-3">🔭</div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">No data for this period</h3>
        <p class="text-gray-600">Not enough measurements in the selected time range. Try a longer period.</p>
      </div>

      <!-- Insight Cards -->
      <div class="space-y-6">
        <div
          v-for="insight in filteredInsights"
          :key="insight.key"
          class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden"
        >
          <!-- Card Header -->
          <div class="p-6 border-b border-gray-100">
            <div class="flex items-center gap-2 mb-2 flex-wrap">
              <div :class="severityIconClass(insight.severity)">
                <!-- high: alert triangle -->
                <svg v-if="insight.severity === 'high'" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
                </svg>
                <!-- moderate: alert circle -->
                <svg v-else-if="insight.severity === 'moderate'" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                <!-- low: check circle -->
                <svg v-else class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
                </svg>
              </div>
              <h3 class="text-xl font-bold text-gray-900">{{ insight.label }}</h3>
              <span :class="severityBadgeClass(insight.severity)" class="px-2.5 py-0.5 rounded-full text-xs font-semibold capitalize">
                {{ insight.severity }} Severity
              </span>
            </div>
            <p class="text-sm text-gray-500">{{ insight.observation }}</p>
          </div>

          <div class="p-6 space-y-5">
            <!-- Before / After / Change -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
              <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-xs text-gray-500 mb-1">Before Value</p>
                <p class="text-2xl font-bold text-gray-900">{{ insight.before }}{{ insight.unit ?? '' }}</p>
              </div>
              <div class="bg-gray-50 rounded-lg p-4 flex items-center justify-center">
                <span class="text-2xl text-gray-400">→</span>
              </div>
              <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-xs text-gray-500 mb-1">Current Value</p>
                <p class="text-2xl font-bold text-gray-900">{{ insight.after }}{{ insight.unit ?? '' }}</p>
              </div>
              <div :class="changeCardClass(insight)" class="rounded-lg p-4">
                <p class="text-xs text-gray-500 mb-1">Change</p>
                <div class="flex items-center gap-1">
                  <span class="text-xl font-bold" :class="changeArrowClass(insight)">{{ trendArrow(insight.direction) }}</span>
                  <p class="text-2xl font-bold">
                    {{ Math.abs(insight.change_percent).toFixed(1) }}%
                  </p>
                </div>
              </div>
            </div>

            <!-- Trend Chart -->
            <div class="bg-gray-50 rounded-lg p-4">
              <p class="text-sm font-semibold text-gray-700 mb-3">📈 Metric Trend Over Time</p>
              <div style="height: 220px; position: relative;">
                <Line v-if="insight.data_points?.length >= 2" :data="buildChartData(insight)" :options="chartOptions" />
                <p v-else class="text-sm text-gray-400 text-center pt-10">Not enough data points for chart</p>
              </div>
            </div>

            <!-- Normal Range -->
            <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
              <p class="font-semibold text-gray-900 mb-2">📊 Healthy Range</p>
              <p class="text-sm text-gray-700">
                Normal range for {{ insight.label.toLowerCase() }}:
                <strong>{{ insight.normal_range?.min ?? '—' }}</strong> to <strong>{{ insight.normal_range?.max ?? '—' }}</strong>{{ insight.unit ?? '' }}
              </p>
              <p class="text-sm text-gray-500 text-xs mt-0.5 italic" v-if="insight.normal_range?.context">{{ insight.normal_range.context }}</p>
              <p class="text-sm text-gray-600 mt-1">
                Your current value of <strong>{{ insight.after }}{{ insight.unit ?? '' }}</strong> is {{ rangeStatus(insight) }}.
              </p>
            </div>

            <!-- AI Insight -->
            <div class="bg-indigo-50 p-4 rounded-lg border border-indigo-200">
              <p class="font-semibold text-gray-900 mb-2">🤖 AI Insight Summary</p>
              <p class="text-gray-800 mb-3 text-sm leading-relaxed">{{ insight.conclusion }}</p>
              <button
                @click="toggleExpanded(insight.key)"
                class="flex items-center gap-1.5 px-3 py-1.5 border border-indigo-300 text-sm text-indigo-700 rounded-lg hover:bg-indigo-100 transition-colors"
              >
                <span>{{ expandedId === insight.key ? '▲' : '▼' }}</span>
                {{ expandedId === insight.key ? 'Hide AI Logic' : 'Show How AI Analysed This' }}
              </button>

              <div v-if="expandedId === insight.key" class="mt-4 pt-4 border-t border-indigo-200 space-y-3">
                <div class="bg-white p-3 rounded border border-indigo-200">
                  <p class="font-semibold text-gray-900 mb-1">1️⃣ The Observation</p>
                  <p class="text-sm text-gray-700">{{ insight.observation }}</p>
                </div>
                <div class="bg-white p-3 rounded border border-indigo-200">
                  <p class="font-semibold text-gray-900 mb-1">2️⃣ The Rule Applied</p>
                  <p class="text-xs text-gray-700 font-mono bg-gray-50 px-2 py-1.5 rounded leading-relaxed whitespace-pre-line">{{ insight.rule_applied }}</p>
                </div>
                <div class="bg-white p-3 rounded border border-indigo-200">
                  <p class="font-semibold text-gray-900 mb-1">3️⃣ The Conclusion</p>
                  <p class="text-sm text-gray-700">Based on the change detected and the rule triggered, this insight was generated to help you understand your health trend.</p>
                </div>
                <div class="bg-white p-3 rounded border border-indigo-200">
                  <p class="font-semibold text-gray-900 mb-1">4️⃣ The Evidence</p>
                  <p class="text-sm text-gray-700">
                    This conclusion is supported by {{ insight.data_points?.length ?? 0 }} measurement{{ (insight.data_points?.length ?? 0) === 1 ? '' : 's' }} showing a
                    {{ insight.direction === 'stable' ? 'stable' : insight.direction + 'ward' }} trend of
                    {{ Math.abs(insight.change_percent).toFixed(1) }}% over the selected time period.
                  </p>
                </div>
              </div>
            </div>

            <!-- Related Recommendations -->
            <div class="bg-green-50 p-4 rounded-lg border border-green-200">
              <p class="font-semibold text-gray-900 mb-3">💡 Related Recommendations</p>
              <div class="space-y-3">
                <div v-for="rec in insight.recommendations" :key="rec.title" class="flex items-start gap-2">
                  <span class="text-lg flex-shrink-0">{{ rec.icon }}</span>
                  <div>
                    <p class="font-medium text-gray-900 text-sm">{{ rec.title }}</p>
                    <p class="text-xs text-gray-600">{{ rec.description }}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      </div>
    </template>

    <!-- Error -->
    <div v-if="error" class="bg-red-50 border border-red-200 rounded-lg p-4 text-red-700 text-sm">{{ error }}</div>

  </div>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue'
import { Line } from 'vue-chartjs'
import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Filler,
  Tooltip,
} from 'chart.js'
import api from '@/services/api'

ChartJS.register(CategoryScale, LinearScale, PointElement, LineElement, Filler, Tooltip)


const PERIODS = [
  { label: '1 Week',   value: 7 },
  { label: '2 Weeks',  value: 14 },
  { label: '1 Month',  value: 30 },
  { label: '3 Months', value: 90 },
]


const loading     = ref(true)
const transitioning = ref(false)
const error       = ref('')
const insights    = ref([])
const meta        = ref({ has_data: false, measurement_count: 0, summary: '', ai_insight: '' })
const selectedPeriod = ref(30)
const selectedMetric = ref('all')
const expandedId  = ref(null)


const metricOptions = computed(() =>
  insights.value.map(i => ({ key: i.key, label: i.label }))
)

const SEVERITY_ORDER = { high: 0, moderate: 1, low: 2 }

const filteredInsights = computed(() => {
  const list = selectedMetric.value === 'all'
    ? insights.value
    : insights.value.filter(i => i.key === selectedMetric.value)
  return [...list].sort((a, b) => (SEVERITY_ORDER[a.severity] ?? 3) - (SEVERITY_ORDER[b.severity] ?? 3))
})


async function loadTrends(isPeriodSwitch = false) {
  if (isPeriodSwitch) {
    transitioning.value = true
  } else {
    loading.value = true
  }
  error.value = ''
  try {
    const response = await api.get(`/trends?period=${selectedPeriod.value}`)
    insights.value = response.data.data  ?? []
    meta.value     = response.data.meta  ?? { has_data: false, measurement_count: 0, summary: '', ai_insight: '' }
  } catch (e) {
    error.value = e.response?.data?.message || e.message || 'Failed to load trend data.'
  } finally {
    loading.value = false
    transitioning.value = false
  }
}

watch(selectedPeriod, () => {
  expandedId.value = null
  loadTrends(true)
})

onMounted(loadTrends)

const chartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: { display: false },
    tooltip: { mode: 'index', intersect: false },
  },
  scales: {
    x: { grid: { display: false }, ticks: { font: { size: 11 } } },
    y: { grid: { color: 'rgba(0,0,0,0.05)' }, ticks: { font: { size: 11 } } },
  },
}

function buildChartData(insight) {
  return {
    labels: insight.data_points.map(d => formatDate(d.date)),
    datasets: [{
      data: insight.data_points.map(d => d.value),
      fill: true,
      backgroundColor: 'rgba(59,130,246,0.1)',
      borderColor: 'rgba(59,130,246,0.8)',
      borderWidth: 2,
      pointBackgroundColor: 'rgba(59,130,246,1)',
      pointRadius: 4,
      tension: 0.4,
    }],
  }
}

function formatDate(dateStr) {
  return new Date(dateStr).toLocaleDateString('en-GB', { day: 'numeric', month: 'short' })
}

function severityIconClass(severity) {
  return {
    high:     'p-2 bg-red-100 rounded-lg text-red-600',
    moderate: 'p-2 bg-yellow-100 rounded-lg text-yellow-600',
    low:      'p-2 bg-green-100 rounded-lg text-green-600',
  }[severity] ?? 'p-2 bg-gray-100 rounded-lg text-gray-500'
}

function severityBadgeClass(severity) {
  return {
    high:     'bg-red-100 text-red-800',
    moderate: 'bg-yellow-100 text-yellow-800',
    low:      'bg-green-100 text-green-800',
  }[severity] ?? 'bg-gray-100 text-gray-800'
}

function trendArrow(direction) {
  return { up: '\u2191', down: '\u2193', stable: '\u2192' }[direction] ?? '\u2192'
}

function changeArrowClass(insight) {
  if (insight.direction === 'stable') return 'text-gray-500'
  const badUp = ['body_fat_percent', 'weight_kg', 'visceral_fat']
  const isBad = badUp.includes(insight.key) ? insight.direction === 'up' : insight.direction === 'down'
  return isBad ? 'text-red-600' : 'text-green-600'
}

function changeCardClass(insight) {
  if (insight.direction === 'stable') return 'bg-gray-50'
  // for body fat, weight, visceral fat â€” up is bad; for muscle, water â€” down is bad
  const badUp = ['body_fat_percent', 'weight_kg', 'visceral_fat']
  const isBad = badUp.includes(insight.key) ? insight.direction === 'up' : insight.direction === 'down'
  return isBad ? 'bg-red-50' : 'bg-green-50'
}

function rangeStatus(insight) {
  const { after, normal_range: nr, unit } = insight
  if (nr.min === null || nr.max === null) return 'range unavailable â€” set your height in profile'
  if (after > nr.max) return `above the recommended maximum of ${nr.max}${unit}`
  if (after < nr.min) return `below the recommended minimum of ${nr.min}${unit}`
  return 'within the healthy range ✓'
}

function toggleExpanded(key) {
  expandedId.value = expandedId.value === key ? null : key
}
</script>

