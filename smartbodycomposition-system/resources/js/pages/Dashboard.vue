<template>
  <div class="space-y-6">
    <!-- Header with Time Range Selector -->
    <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg shadow border border-green-200">
      <div class="p-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
          <div>
            <h1 class="text-3xl font-bold text-green-900">Health Dashboard</h1>
            <p class="text-gray-600 text-sm">Track your progress and health metrics over time</p>
          </div>
          <div class="flex gap-2 flex-wrap">
            <button
              v-for="range in ['1W', '1M', '3M', '6M']"
              :key="range"
              @click="timeRange = range"
              :class="[
                'px-4 py-2 rounded-lg font-semibold min-w-16 transition-colors duration-200',
                timeRange === range
                  ? 'bg-gradient-to-r from-green-600 to-emerald-600 text-white'
                  : 'border border-green-200 text-green-700 hover:bg-green-50'
              ]"
            >
              {{ range }}
            </button>
          </div>
        </div>
      </div>
    </div>

      <!-- Key Metrics Cards Grid -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Weight Card -->
        <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-gray-700">Weight</h3>
            <div :class="[
              'p-2 rounded-lg',
              currentData.weight_kg < previousData.weight_kg ? 'bg-green-100' : 'bg-orange-100'
            ]">
              <svg v-if="currentData.weight_kg < previousData.weight_kg" class="h-4 w-4 text-green-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                <polyline points="17 18 23 18 23 12"></polyline>
              </svg>
              <svg v-else class="h-4 w-4 text-orange-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                <polyline points="17 6 23 6 23 12"></polyline>
              </svg>
            </div>
          </div>
          <div class="space-y-2">
            <div class="flex items-baseline gap-1">
              <span class="text-3xl font-bold text-gray-900">{{ currentData.weight_kg || '-' }}</span>
              <span class="text-gray-500 text-sm">kg</span>
            </div>
            <div class="flex items-center gap-2">
              <span :class="[
                'text-sm font-medium',
                weightChange <= 0 ? 'text-green-600' : 'text-orange-600'
              ]">
                {{ weightChange <= 0 ? '-' : '+' }}{{ Math.abs(weightChange) }} kg
              </span>
              <span class="text-xs text-gray-500">({{ Math.abs(weightChangePercent).toFixed(1) }}%)</span>
            </div>
          </div>
        </div>

        <!-- BMI Card -->
        <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-gray-700">BMI</h3>
            <div class="p-2 bg-blue-100 rounded-lg">
              <svg class="h-4 w-4 text-blue-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
              </svg>
            </div>
          </div>
          <div class="space-y-2">
            <div class="flex items-baseline gap-1">
              <span class="text-3xl font-bold text-gray-900">{{ computedBMI.toFixed(1) }}</span>
              <span class="text-gray-500 text-sm">kg/m²</span>
            </div>
            <span :class="[
              'inline-block px-3 py-1 rounded-full text-xs font-semibold text-white',
              bmiZone.badgeColor
            ]">
              {{ bmiZone.label }}
            </span>
          </div>
        </div>

        <!-- Body Fat Card -->
        <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-gray-700">Body Fat</h3>
            <div class="p-2 bg-yellow-100 rounded-lg">
              <svg class="h-4 w-4 text-yellow-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="1"></circle>
                <path d="M12 1v6m4.22-4.22-4.24 4.24M12 23v-6m4.22 4.22-4.24-4.24M23 12h-6m4.22 4.22-4.24-4.24M1 12h6M4.22 16.22l4.24-4.24"></path>
              </svg>
            </div>
          </div>
          <div class="space-y-2">
            <div class="flex items-baseline gap-1">
              <span class="text-3xl font-bold text-gray-900">{{ (currentData.body_fat_percent || 0).toFixed(1) }}</span>
              <span class="text-gray-500 text-sm">%</span>
            </div>
            <div class="flex items-center gap-2">
              <span class="text-sm font-medium text-yellow-600">
                {{ bodyFatChange <= 0 ? '-' : '+' }}{{ Math.abs(bodyFatChange).toFixed(1) }}%
              </span>
            </div>
          </div>
        </div>

        <!-- Muscle Mass Card -->
        <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-gray-700">Muscle Mass</h3>
            <div class="p-2 bg-green-100 rounded-lg">
              <svg class="h-4 w-4 text-green-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M6 4h12v16H6z"></path>
                <circle cx="6" cy="4" r="1"></circle>
                <circle cx="18" cy="4" r="1"></circle>
              </svg>
            </div>
          </div>
          <div class="space-y-2">
            <div class="flex items-baseline gap-1">
              <span class="text-3xl font-bold text-gray-900">{{ (currentData.muscle_mass || 0).toFixed(1) }}</span>
              <span class="text-gray-500 text-sm">kg</span>
            </div>
            <div class="flex items-center gap-2">
              <span class="text-sm font-medium text-green-600">
                {{ muscleMassChange >= 0 ? '+' : '-' }}{{ Math.abs(muscleMassChange).toFixed(1) }} kg
              </span>
            </div>
          </div>
        </div>
      </div>

      <!-- Charts Grid -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Weight Trend Chart -->
        <div class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden">
          <div class="border-b border-gray-200 p-6">
            <div class="flex items-center gap-3">
              <div class="p-2 bg-green-100 rounded-lg">
                <svg class="h-5 w-5 text-green-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                  <polyline points="17 18 23 18 23 12"></polyline>
                </svg>
              </div>
              <div>
                <h3 class="font-bold text-gray-900">Weight Trend</h3>
                <p class="text-sm text-gray-600">Weight progress over selected period</p>
              </div>
            </div>
          </div>
          <div class="p-6">
            <MetricLineChart
              :labels="weightChart.labels"
              :values="weightChart.values"
              label="Weight"
              color="#16a34a"
              fill-color="rgba(34, 197, 94, 0.18)"
              unit="kg"
              empty-title="No weight records in this time range"
              empty-subtitle="Add more measurements to see your weight trend"
            />
          </div>
        </div>

        <!-- BMI Trend Chart removed as per user request -->

        <!-- Muscle Mass Trend -->
        <div class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden">
          <div class="border-b border-gray-200 p-6">
            <div class="flex items-center gap-3">
              <div class="p-2 bg-green-100 rounded-lg">
                <svg class="h-5 w-5 text-green-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M6 4h12v16H6z"></path>
                </svg>
              </div>
              <div>
                <h3 class="font-bold text-gray-900">Muscle Mass</h3>
                <p class="text-sm text-gray-600">Lean muscle growth tracking</p>
              </div>
            </div>
          </div>
          <div class="p-6">
            <MetricLineChart
              :labels="muscleChart.labels"
              :values="muscleChart.values"
              label="Muscle Mass"
              color="#059669"
              fill-color="rgba(16, 185, 129, 0.18)"
              unit="kg"
              empty-title="No muscle mass records in this time range"
              empty-subtitle="Log muscle measurements to view this chart"
            />
            <div class="flex justify-between text-sm">
              <span class="text-gray-600">Physical Rating</span>
              <span class="font-medium text-green-600">{{ currentData.physical_rating || '-' }}</span>
            </div>
          </div>
        </div>

        <!-- Body Fat Percentage Chart -->
        <div class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden">
          <div class="border-b border-gray-200 p-6">
            <div class="flex items-center gap-3">
              <div class="p-2 bg-yellow-100 rounded-lg">
                <svg class="h-5 w-5 text-yellow-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <circle cx="12" cy="12" r="1"></circle>
                  <path d="M12 1v6m4.22-4.22-4.24 4.24M12 23v-6m4.22 4.22-4.24-4.24M23 12h-6m4.22 4.22-4.24-4.24M1 12h6M4.22 16.22l4.24-4.24"></path>
                </svg>
              </div>
              <div>
                <h3 class="font-bold text-gray-900">Body Fat %</h3>
                <p class="text-sm text-gray-600">Fat composition trend</p>
              </div>
            </div>
          </div>
          <div class="p-6">
            <MetricLineChart
              :labels="bodyFatChart.labels"
              :values="bodyFatChart.values"
              label="Body Fat"
              color="#d97706"
              fill-color="rgba(245, 158, 11, 0.18)"
              unit="%"
              empty-title="No body fat records in this time range"
              empty-subtitle="Record body fat measurements to view the trend"
            />
          </div>
        </div>

        <!-- Health Zones Reference -->
        <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
          <h3 class="font-bold text-gray-900 mb-1">Health Zones</h3>
          <p class="text-sm text-gray-600 mb-6">Your current status across metrics</p>

          <div class="space-y-6">
            <!-- BMI Zone Indicator -->
            <div class="space-y-2">
              <div class="flex justify-between items-center">
                <span class="text-sm font-medium text-gray-700">BMI Status</span>
                <div class="flex items-center gap-3">
                  <span class="text-sm font-semibold" :class="bmiZone.textColor">
                    {{ computedBMI.toFixed(1) }} kg/m²
                  </span>
                  <span :class="[
                    'inline-block px-3 py-1 rounded-full text-xs font-semibold text-white',
                    bmiZone.badgeColor
                  ]">
                    {{ bmiZone.label }}
                  </span>
                </div>
              </div>
              <div class="relative">
                <div class="w-full h-3 bg-gray-200 rounded-full overflow-hidden flex">
                  <div
                    v-for="segment in bmiBarSegments"
                    :key="segment.label"
                    class="h-full"
                    :class="segment.color"
                    :style="{ width: segment.width }"
                  ></div>
                  <div
                    class="absolute top-1/2 -translate-y-1/2 w-4 h-4 bg-white border-2 rounded-full shadow-md transition-all"
                    :class="bmiZone.borderColor"
                    :style="{ left: `calc(${bmiMarkerPosition}% - 8px)` }"
                    :title="`BMI: ${computedBMI.toFixed(1)}`"
                  ></div>
                </div>
              </div>
              <div class="flex justify-between text-xs text-gray-600">
                <span
                  v-for="zone in bmiZones"
                  :key="zone.label"
                  :class="[
                    'transition-colors',
                    zone.label === bmiZone.label ? ['font-bold', zone.textColor] : 'text-gray-500'
                  ]"
                >
                  {{ zone.shortLabel }}
                </span>
              </div>
            </div>

          </div>
        </div>
      </div>

      <!-- Personal Data Export -->
      <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-lg shadow border border-green-200">
        <div class="border-b border-green-200 p-6">
          <h3 class="font-bold text-gray-900 mb-1">Export Your Data</h3>
          <p class="text-sm text-gray-600">Download your personal body composition records</p>
        </div>

        <div class="p-6 space-y-6">
          <!-- Privacy Notice -->
          <div class="p-4 bg-white rounded-lg border border-green-100">
            <p class="text-sm text-green-700">
              <span class="font-semibold">Personal Data Only:</span> You can only export your own body composition records. This data is private and personal to you.
            </p>
          </div>

          <!-- Date Range Selection -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="space-y-2">
              <label class="text-sm font-medium text-gray-700">Start Date</label>
              <input
                v-model="startDate"
                type="date"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500 bg-white"
              />
            </div>
            <div class="space-y-2">
              <label class="text-sm font-medium text-gray-700">End Date</label>
              <input
                v-model="endDate"
                type="date"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500 bg-white"
              />
            </div>
          </div>

          <!-- Export Format Options -->
          <div>
            <p class="text-sm font-medium text-gray-700 mb-3">Select Download Format</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
              <button
                @click="handleExport('csv')"
                class="flex items-center justify-center gap-2 px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-semibold rounded-lg transition-all duration-200"
              >
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                  <polyline points="7 10 12 15 17 10"></polyline>
                  <line x1="12" y1="15" x2="12" y2="3"></line>
                </svg>
                Download as CSV
              </button>
              <button
                @click="handleExport('pdf')"
                class="flex items-center justify-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700 text-white font-semibold rounded-lg transition-all duration-200"
              >
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path>
                  <polyline points="13 2 13 9 20 9"></polyline>
                </svg>
                Print as PDF
              </button>
            </div>
          </div>

          <!-- Records Preview -->
          <div class="border-t border-green-200 pt-4">
            <p class="text-sm font-medium text-gray-700 mb-3">Records in Date Range</p>
            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
              <div class="overflow-x-auto">
                <table class="w-full text-sm">
                  <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                      <th class="text-left py-3 px-4 font-semibold text-gray-700">Date</th>
                      <th class="text-left py-3 px-4 font-semibold text-gray-700">Weight (kg)</th>
                      <th class="text-left py-3 px-4 font-semibold text-gray-700">Body Fat %</th>
                      <th class="text-left py-3 px-4 font-semibold text-gray-700">Muscle Mass</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="record in filteredRecords.slice(0, 5)" :key="record.id" class="border-b border-gray-200 hover:bg-gray-50">
                      <td class="py-3 px-4">{{ record.measurement_date }}</td>
                      <td class="py-3 px-4">{{ record.weight_kg?.toFixed(1) || '-' }} kg</td>
                      <td class="py-3 px-4">{{ record.body_fat_percent?.toFixed(1) || '-' }}%</td>
                      <td class="py-3 px-4">{{ record.muscle_mass?.toFixed(1) || '-' }} kg</td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div class="p-3 bg-gray-50 text-xs text-gray-600 border-t border-gray-200">
                {{ filteredRecords.length }} records found in selected date range
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Health Recommendations -->
      <div class="bg-gradient-to-br from-green-50 to-blue-50 rounded-lg shadow border border-green-200">
        <div class="border-b border-green-200 p-6">
          <div class="flex items-center gap-3">
            <div class="p-2 bg-green-100 rounded-lg">
              <svg class="h-5 w-5 text-green-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
              </svg>
            </div>
            <div>
              <h3 class="font-bold text-gray-900">Health Recommendations</h3>
              <p class="text-sm text-gray-600">Personalized suggestions based on your metrics</p>
            </div>
          </div>
        </div>

        <div class="p-6">
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
            <div class="flex items-start gap-3 p-4 bg-white rounded-lg border border-green-100 hover:shadow-sm transition-shadow">
              <div class="w-2 h-2 rounded-full bg-green-600 mt-2 flex-shrink-0"></div>
              <div>
                <p class="font-medium text-sm text-gray-900">Stay Hydrated</p>
                <p class="text-xs text-gray-600 mt-1">Aim for 8 glasses of water daily to maintain optimal body function.</p>
              </div>
            </div>
            <div class="flex items-start gap-3 p-4 bg-white rounded-lg border border-green-100 hover:shadow-sm transition-shadow">
              <div class="w-2 h-2 rounded-full bg-green-600 mt-2 flex-shrink-0"></div>
              <div>
                <p class="font-medium text-sm text-gray-900">Maintain Your Progress</p>
                <p class="text-xs text-gray-600 mt-1">You're on track with your weight goals. Keep up the great work!</p>
              </div>
            </div>
            <div class="flex items-start gap-3 p-4 bg-white rounded-lg border border-green-100 hover:shadow-sm transition-shadow">
              <div class="w-2 h-2 rounded-full bg-green-600 mt-2 flex-shrink-0"></div>
              <div>
                <p class="font-medium text-sm text-gray-900">Regular Exercise</p>
                <p class="text-xs text-gray-600 mt-1">30 minutes of moderate activity helps maintain muscle mass.</p>
              </div>
            </div>
            <div class="flex items-start gap-3 p-4 bg-white rounded-lg border border-blue-100 hover:shadow-sm transition-shadow">
              <div class="w-2 h-2 rounded-full bg-blue-600 mt-2 flex-shrink-0"></div>
              <div>
                <p class="font-medium text-sm text-gray-900">Sleep Quality</p>
                <p class="text-xs text-gray-600 mt-1">Aim for 7-9 hours of quality sleep to support recovery.</p>
              </div>
            </div>
            <div class="flex items-start gap-3 p-4 bg-white rounded-lg border border-blue-100 hover:shadow-sm transition-shadow">
              <div class="w-2 h-2 rounded-full bg-blue-600 mt-2 flex-shrink-0"></div>
              <div>
                <p class="font-medium text-sm text-gray-900">Balanced Nutrition</p>
                <p class="text-xs text-gray-600 mt-1">Focus on whole foods, proteins, and healthy carbohydrates.</p>
              </div>
            </div>
            <div class="flex items-start gap-3 p-4 bg-white rounded-lg border border-blue-100 hover:shadow-sm transition-shadow">
              <div class="w-2 h-2 rounded-full bg-blue-600 mt-2 flex-shrink-0"></div>
              <div>
                <p class="font-medium text-sm text-gray-900">Track Progress</p>
                <p class="text-xs text-gray-600 mt-1">Update your measurements weekly for better insights.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import MetricLineChart from '../components/MetricLineChart.vue'
import { getUserProfile } from '../services/authService'

const timeRange = ref('1M')
const startDate = ref('')
const endDate = ref('')
const measurements = ref([])
const loading = ref(true)
const userHeight = ref(null) // in cm

// Computed properties
const data = computed(() => {
  const currentDate = new Date()
  let startDateObj

  switch (timeRange.value) {
    case '1W':
      startDateObj = new Date(currentDate)
      startDateObj.setDate(currentDate.getDate() - 7)
      break
    case '1M':
      startDateObj = new Date(currentDate)
      startDateObj.setMonth(currentDate.getMonth() - 1)
      break
    case '3M':
      startDateObj = new Date(currentDate)
      startDateObj.setMonth(currentDate.getMonth() - 3)
      break
    case '6M':
      startDateObj = new Date(currentDate)
      startDateObj.setMonth(currentDate.getMonth() - 6)
      break
    default:
      startDateObj = new Date(currentDate)
      startDateObj.setMonth(currentDate.getMonth() - 1)
  }

  return measurements.value.filter(m => {
    const measurementDate = new Date(m.measurement_date)
    return measurementDate >= startDateObj && measurementDate <= currentDate
  }).sort((a, b) => new Date(a.measurement_date) - new Date(b.measurement_date))
})

const currentData = computed(() => {
  if (data.value.length === 0) {
    return { weight_kg: 0, body_fat_percent: 0, muscle_mass: 0 }
  }
  return data.value[data.value.length - 1]
})

// Compute BMI dynamically using latest weight and user height
const computedBMI = computed(() => {
  const weight = currentData.value.weight_kg || 0
  const heightCm = userHeight.value
  if (!weight || !heightCm) return 0
  const heightM = heightCm / 100
  return weight / (heightM * heightM)
})

const previousData = computed(() => {
  if (data.value.length === 0) {
    return { weight_kg: 0, body_fat_percent: 0, muscle_mass: 0, bmi: 0 }
  }
  return data.value[0]
})

const weightChange = computed(() => {
  const curr = currentData.value.weight_kg
  const prev = previousData.value.weight_kg
  return parseFloat((curr - prev).toFixed(2))
})

const weightChangePercent = computed(() => {
  if (previousData.value.weight_kg === 0) return 0
  return ((weightChange.value / previousData.value.weight_kg) * 100).toFixed(1)
})

const bodyFatChange = computed(() => {
  const curr = currentData.value.body_fat_percent
  const prev = previousData.value.body_fat_percent
  return parseFloat((curr - prev).toFixed(2))
})

const muscleMassChange = computed(() => {
  const curr = currentData.value.muscle_mass
  const prev = previousData.value.muscle_mass
  return parseFloat((curr - prev).toFixed(2))
})

const bmiZones = [
  { label: 'Underweight', shortLabel: 'Underweight', min: 0, max: 18.5, color: 'bg-blue-500', badgeColor: 'bg-blue-600', textColor: 'text-blue-700', borderColor: 'border-blue-600' },
  { label: 'Normal Weight', shortLabel: 'Normal', min: 18.5, max: 25, color: 'bg-green-500', badgeColor: 'bg-green-600', textColor: 'text-green-700', borderColor: 'border-green-600' },
  { label: 'Overweight', shortLabel: 'Overweight', min: 25, max: 30, color: 'bg-yellow-500', badgeColor: 'bg-yellow-600', textColor: 'text-yellow-700', borderColor: 'border-yellow-600' },
  { label: 'Obese', shortLabel: 'Obese', min: 30, max: 40, color: 'bg-red-500', badgeColor: 'bg-red-600', textColor: 'text-red-700', borderColor: 'border-red-600' },
]

const getZone = (value, zones) => {
  const matchedZone = zones.find(zone => value >= zone.min && value < zone.max)
  return matchedZone || zones[zones.length - 1]
}

const bmiZone = computed(() => getZone(computedBMI.value || 0, bmiZones))

const filteredRecords = computed(() => {
  if (!startDate.value || !endDate.value) return measurements.value
  return measurements.value.filter(record => {
    return record.measurement_date >= startDate.value && record.measurement_date <= endDate.value
  })
})

const formatChartLabel = (dateString) => {
  return new Date(dateString).toLocaleDateString('en-US', {
    month: 'short',
    day: 'numeric',
  })
}

const buildMetricChart = (key) => {
  const chartRecords = data.value.filter(record => typeof record[key] === 'number' && !Number.isNaN(record[key]))

  return {
    labels: chartRecords.map(record => formatChartLabel(record.measurement_date)),
    values: chartRecords.map(record => Number(record[key].toFixed(1))),
  }
}

const weightChart = computed(() => buildMetricChart('weight_kg'))
const muscleChart = computed(() => buildMetricChart('muscle_mass'))
const bodyFatChart = computed(() => buildMetricChart('body_fat_percent'))

const buildZoneSegments = (zones) => {
  const max = zones[zones.length - 1].max

  return zones.map((zone) => ({
    label: zone.label,
    color: zone.color,
    width: `${((zone.max - zone.min) / max) * 100}%`,
  }))
}

const getMarkerPosition = (value, max) => {
  if (!value || value < 0) {
    return 0
  }

  return Math.min((value / max) * 100, 100)
}

const bmiBarSegments = computed(() => buildZoneSegments(bmiZones))

const bmiMarkerPosition = computed(() => getMarkerPosition(computedBMI.value, bmiZones[bmiZones.length - 1].max))

// Methods
const loadMeasurements = async () => {
  try {
    loading.value = true
    const response = await fetch('/api/body-compositions', {
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('auth_token')}`,
      },
    })
    const result = await response.json()
    measurements.value = (result.data || result || []).sort((a, b) =>
      new Date(a.measurement_date) - new Date(b.measurement_date)
    )

    // Set default date range to 6 months from oldest record
    if (measurements.value.length > 0) {
      const oldestDate = new Date(measurements.value[0].measurement_date)
      const today = new Date()

      endDate.value = today.toISOString().split('T')[0]
      startDate.value = oldestDate.toISOString().split('T')[0]
    } else {
      // Default to 6 months if no data
      const today = new Date()
      endDate.value = today.toISOString().split('T')[0]
      const sixMonthsAgo = new Date(today)
      sixMonthsAgo.setMonth(today.getMonth() - 6)
      startDate.value = sixMonthsAgo.toISOString().split('T')[0]
    }
  } catch (error) {
    console.error('Failed to load measurements:', error)
  } finally {
    loading.value = false
  }
}

// Load on mount
onMounted(async () => {
  loadMeasurements()
  try {
    const profile = await getUserProfile()
    userHeight.value = profile.height_cm
  } catch (e) {
    userHeight.value = null
  }
})

// Watch for time range changes
watch(() => timeRange.value, () => {
  // Data will be filtered automatically by computed property
})

// Methods
const handleExport = (format) => {
  if (filteredRecords.value.length === 0) {
    alert('No records found in the selected date range.')
    return
  }

  if (format === 'csv') {
    exportAsCSV(filteredRecords.value)
  } else if (format === 'pdf') {
    exportAsPDF(filteredRecords.value)
  }
}

const exportAsCSV = (records) => {
  const headers = ['Date', 'Weight (kg)', 'Body Fat (%)', 'Muscle Mass (kg)', 'Bone Mass (kg)', 'Water (%)', 'Visceral Fat', 'BMR', 'Physical Rating']
  const rows = records.map(record => [
    record.measurement_date,
    record.weight_kg?.toFixed(1) || '-',
    record.body_fat_percent?.toFixed(1) || '-',
    record.muscle_mass?.toFixed(1) || '-',
    record.bone_mass?.toFixed(1) || '-',
    record.body_water_percent?.toFixed(1) || '-',
    record.visceral_fat?.toFixed(1) || '-',
    record.bmr || '-',
    record.physical_rating || '-'
  ])

  const csvContent = [
    headers.join(','),
    ...rows.map(row => row.map(cell => `"${cell}"`).join(','))
  ].join('\n')

  const blob = new Blob([csvContent], { type: 'text/csv' })
  const url = window.URL.createObjectURL(blob)
  const a = document.createElement('a')
  a.href = url
  a.download = `body-composition-${startDate.value}-to-${endDate.value}.csv`
  document.body.appendChild(a)
  a.click()
  window.URL.revokeObjectURL(url)
  document.body.removeChild(a)
}

const exportAsPDF = (records) => {
  let pdfContent = `
  <html>
    <head>
      <title>Body Composition Report</title>
      <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { color: #16a34a; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background-color: #f3f4f6; padding: 10px; border: 1px solid #d1d5db; text-align: left; }
        td { padding: 10px; border: 1px solid #d1d5db; }
        .summary { background-color: #f0fdf4; padding: 15px; border-radius: 8px; margin-bottom: 20px; }
        .footer { margin-top: 30px; font-size: 12px; color: #6b7280; }
      </style>
    </head>
    <body>
      <h1>Body Composition Report</h1>
      <div class="summary">
        <p><strong>Report Period:</strong> ${startDate.value} to ${endDate.value}</p>
        <p><strong>Total Records:</strong> ${records.length}</p>
        <p><strong>Generated:</strong> ${new Date().toLocaleDateString()}</p>
      </div>
      <table>
        <thead>
          <tr>
            <th>Date</th>
            <th>Weight (kg)</th>
            <th>Body Fat (%)</th>
            <th>Muscle Mass (kg)</th>
            <th>Bone Mass (kg)</th>
            <th>Water (%)</th>
            <th>Visceral Fat</th>
            <th>BMR</th>
            <th>Physical Rating</th>
          </tr>
        </thead>
        <tbody>
          ${records.map(record => `
            <tr>
              <td>${record.measurement_date}</td>
              <td>${record.weight_kg?.toFixed(1) || '-'}</td>
              <td>${record.body_fat_percent?.toFixed(1) || '-'}</td>
              <td>${record.muscle_mass?.toFixed(1) || '-'}</td>
              <td>${record.bone_mass?.toFixed(1) || '-'}</td>
              <td>${record.body_water_percent?.toFixed(1) || '-'}</td>
              <td>${record.visceral_fat?.toFixed(1) || '-'}</td>
              <td>${record.bmr || '-'}</td>
              <td>${record.physical_rating || '-'}</td>
            </tr>
          `).join('')}
        </tbody>
      </table>
      <div class="footer">
        <p>This is a personal health record. Keep it private and secure.</p>
      </div>
    </body>
  </html>
  `

  const printWindow = window.open('', '', 'height=400,width=800')
  if (printWindow) {
    printWindow.document.write(pdfContent)
    printWindow.document.close()
    printWindow.print()
  }
}
</script>

<style scoped>
/* Smooth transitions */
button {
  transition: all 0.2s ease;
}

/* Responsive adjustments */
@media (max-width: 640px) {
  :deep(.grid) {
    grid-template-columns: 1fr;
  }
}
</style>
