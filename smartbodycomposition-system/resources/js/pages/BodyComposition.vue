<template>
  <div class="space-y-6">
    <!-- Page Header -->
    <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
      <h2 class="text-2xl font-bold text-gray-900 mb-2">Body Composition</h2>
      <p class="text-gray-600">Track and manage your detailed body composition measurements</p>
    </div>

    <!-- Add New Measurement Form -->
    <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
      <h3 class="text-lg font-semibold text-gray-900 mb-6">Record New Measurement</h3>

      <!-- Alert Messages -->
      <Transition name="fade">
        <div v-if="successMessage" class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg flex items-start gap-3">
          <svg class="h-5 w-5 text-green-600 flex-shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
            <polyline points="22 4 12 14.01 9 11.01"></polyline>
          </svg>
          <p class="text-green-800 text-sm">{{ successMessage }}</p>
        </div>
      </Transition>

      <Transition name="fade">
        <div v-if="errorMessage" class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg flex items-start gap-3">
          <svg class="h-5 w-5 text-red-600 flex-shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"></circle>
            <line x1="12" y1="8" x2="12" y2="12"></line>
            <line x1="12" y1="16" x2="12.01" y2="16"></line>
          </svg>
          <p class="text-red-800 text-sm">{{ errorMessage }}</p>
        </div>
      </Transition>

      <form @submit.prevent="recordMeasurement" class="space-y-6">
        <!-- Date and Time -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-900 mb-2">Measurement Date *</label>
            <input
              v-model="form.measurementDate"
              type="date"
              required
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
              :class="{ 'border-red-500 bg-red-50': errors.measurementDate }"
            />
            <p v-if="errors.measurementDate" class="text-xs text-red-600 mt-1">{{ errors.measurementDate }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-900 mb-2">Measurement Time</label>
            <input
              v-model="form.measurementTime"
              type="time"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
            />
          </div>
        </div>

        <!-- Weight and Body Fat Percentage -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-900 mb-2">Weight (kg) *</label>
            <input
              v-model.number="form.weightKg"
              type="number"
              step="0.1"
              placeholder="72.5"
              required
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
              :class="{ 'border-red-500 bg-red-50': errors.weightKg }"
            />
            <p v-if="errors.weightKg" class="text-xs text-red-600 mt-1">{{ errors.weightKg }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-900 mb-2">Body Fat (%)</label>
            <input
              v-model.number="form.bodyFatPercent"
              type="number"
              step="0.1"
              placeholder="18.5"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
            />
          </div>
        </div>

        <!-- Body Fat (kg) and Body Water (%) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-900 mb-2">Body Fat (kg)</label>
            <input
              v-model.number="form.bodyFatKg"
              type="number"
              step="0.1"
              placeholder="13.5"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-900 mb-2">Body Water (%)</label>
            <input
              v-model.number="form.bodyWaterPercent"
              type="number"
              step="0.1"
              placeholder="60.5"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
            />
          </div>
        </div>

        <!-- Muscle Mass and Bone Mass -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-900 mb-2">Muscle Mass (kg)</label>
            <input
              v-model.number="form.muscleMass"
              type="number"
              step="0.1"
              placeholder="30.5"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-900 mb-2">Bone Mass (kg)</label>
            <input
              v-model.number="form.boneMass"
              type="number"
              step="0.1"
              placeholder="3.2"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
            />
          </div>
        </div>

        <!-- Physical Rating and Visceral Fat -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-900 mb-2">Physical Rating (1-9)</label>
            <select
              v-model.number="form.physicalRating"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
            >
              <option :value="null">Select Rating</option>
              <option :value="1">1</option>
              <option :value="2">2</option>
              <option :value="3">3</option>
              <option :value="4">4</option>
              <option :value="5">5</option>
              <option :value="6">6</option>
              <option :value="7">7</option>
              <option :value="8">8</option>
              <option :value="9">9</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-900 mb-2">Visceral Fat</label>
            <input
              v-model.number="form.visceralFat"
              type="number"
              step="0.1"
              placeholder="5.2"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
            />
          </div>
        </div>

        <!-- Calories and BMR -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-900 mb-2">Calories (kcal)</label>
            <input
              v-model.number="form.kcal"
              type="number"
              step="1"
              placeholder="1800"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-900 mb-2">BMR (kcal/day)</label>
            <input
              v-model.number="form.bmr"
              type="number"
              step="1"
              placeholder="1600"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
            />
          </div>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end gap-4 pt-4">
          <button
            @click="resetForm"
            type="button"
            class="px-6 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors"
          >
            Clear
          </button>
          <button
            type="submit"
            :disabled="loading"
            class="px-6 py-2 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 disabled:from-green-400 disabled:to-emerald-400 text-white font-semibold rounded-lg transition-all duration-200 flex items-center gap-2"
          >
            <svg v-if="loading" class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            {{ loading ? 'Recording...' : 'Record Measurement' }}
          </button>
        </div>
      </form>
    </div>

    <!-- Measurement History -->
    <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
      <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold text-gray-900">Recent Measurements</h3>
        <div class="flex items-center gap-4" v-if="measurements.length > 0">
          <div class="flex items-center gap-2">
            <label class="text-sm font-medium text-gray-700">Records per page:</label>
            <select
              v-model.number="perPage"
              @change="currentPage = 1"
              class="px-3 py-1 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
            >
              <option :value="10">10</option>
              <option :value="50">50</option>
              <option :value="100">100</option>
            </select>
          </div>
        </div>
      </div>

      <div v-if="measurements.length === 0" class="text-center py-8">
        <p class="text-gray-600">No measurements recorded yet. Add one above to get started!</p>
      </div>

      <div v-else class="overflow-x-auto">
        <table class="min-w-full">
          <thead>
            <tr class="border-b border-gray-200">
              <th class="px-4 py-3 text-left text-sm font-semibold text-gray-900">Date</th>
              <th class="px-4 py-3 text-left text-sm font-semibold text-gray-900">Time</th>
              <th class="px-4 py-3 text-left text-sm font-semibold text-gray-900">Weight (kg)</th>
              <th class="px-4 py-3 text-left text-sm font-semibold text-gray-900">Body Fat (%)</th>
              <th class="px-4 py-3 text-left text-sm font-semibold text-gray-900">Body Fat (kg)</th>
              <th class="px-4 py-3 text-left text-sm font-semibold text-gray-900">Muscle (kg)</th>
              <th class="px-4 py-3 text-left text-sm font-semibold text-gray-900">Bone Mass (kg)</th>
              <th class="px-4 py-3 text-left text-sm font-semibold text-gray-900">Water (%)</th>
              <th class="px-4 py-3 text-left text-sm font-semibold text-gray-900">Visceral Fat</th>
              <th class="px-4 py-3 text-left text-sm font-semibold text-gray-900">Calories (kcal)</th>
              <th class="px-4 py-3 text-left text-sm font-semibold text-gray-900">BMR</th>
              <th class="px-4 py-3 text-left text-sm font-semibold text-gray-900">Physical Rating</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="measurement in paginatedMeasurements" :key="measurement.id" class="border-b border-gray-100 hover:bg-gray-50">
              <td class="px-4 py-3 text-sm text-gray-900">{{ formatDate(measurement.measurement_date) }}</td>
              <td class="px-4 py-3 text-sm text-gray-900">{{ measurement.measurement_time || '-' }}</td>
              <td class="px-4 py-3 text-sm text-gray-900">{{ measurement.weight_kg?.toFixed(1) || '-' }}</td>
              <td class="px-4 py-3 text-sm text-gray-900">{{ measurement.body_fat_percent?.toFixed(1) || '-' }}%</td>
              <td class="px-4 py-3 text-sm text-gray-900">{{ measurement.body_fat_kg?.toFixed(1) || '-' }}</td>
              <td class="px-4 py-3 text-sm text-gray-900">{{ measurement.muscle_mass?.toFixed(1) || '-' }}</td>
              <td class="px-4 py-3 text-sm text-gray-900">{{ measurement.bone_mass?.toFixed(1) || '-' }}</td>
              <td class="px-4 py-3 text-sm text-gray-900">{{ measurement.body_water_percent?.toFixed(1) || '-' }}%</td>
              <td class="px-4 py-3 text-sm text-gray-900">{{ measurement.visceral_fat?.toFixed(1) || '-' }}</td>
              <td class="px-4 py-3 text-sm text-gray-900">{{ measurement.kcal || '-' }}</td>
              <td class="px-4 py-3 text-sm text-gray-900">{{ measurement.bmr ? measurement.bmr.toFixed(0) : '-' }}</td>
              <td class="px-4 py-3 text-sm text-gray-900">{{ getRatingLabel(measurement.physical_rating) || '-' }}</td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination Controls -->
      <div v-if="measurements.length > 0" class="flex justify-between items-center mt-6 pt-4 border-t border-gray-200">
        <div class="text-sm text-gray-600">
          Showing {{ startIndex + 1 }} to {{ Math.min(endIndex, measurements.length) }} of {{ measurements.length }} records
        </div>
        <div class="flex gap-2">
          <button
            @click="currentPage = Math.max(1, currentPage - 1)"
            :disabled="currentPage === 1"
            class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
          >
            Previous
          </button>
          <div class="flex items-center gap-1">
            <span v-for="page in pageNumbers" :key="page" class="inline-block">
              <button
                v-if="page === '...'"
                disabled
                class="px-2 py-1 text-gray-500 cursor-default"
              >
                ...
              </button>
              <button
                v-else
                @click="currentPage = page"
                :class="{
                  'px-3 py-1 bg-green-600 text-white rounded-lg': currentPage === page,
                  'px-3 py-1 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50': currentPage !== page,
                }"
                class="transition-colors"
              >
                {{ page }}
              </button>
            </span>
          </div>
          <button
            @click="currentPage = Math.min(totalPages, currentPage + 1)"
            :disabled="currentPage === totalPages"
            class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
          >
            Next
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'

const form = ref({
  measurementDate: new Date().toISOString().split('T')[0],
  measurementTime: '',
  weightKg: null,
  bodyFatPercent: null,
  bodyFatKg: null,
  bodyWaterPercent: null,
  muscleMass: null,
  physicalRating: null,
  boneMass: null,
  kcal: null,
  bmr: null,
  visceralFat: null,
})

const measurements = ref([])
const loading = ref(false)
const successMessage = ref('')
const errorMessage = ref('')
const errors = ref({})
const currentPage = ref(1)
const perPage = ref(10)

// Load measurements on mount
onMounted(async () => {
  await loadMeasurements()
})

// Load measurements from API
const loadMeasurements = async () => {
  try {
    const response = await fetch('/api/body-compositions', {
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('auth_token')}`,
      },
    })
    const data = await response.json()
    // Sort by latest measurement_date descending
    const sorted = (data.data || data || []).slice().sort((a, b) => {
      return new Date(b.measurement_date) - new Date(a.measurement_date)
    })
    measurements.value = sorted
  } catch (error) {
    console.error('Failed to load measurements:', error)
  }
}

// Record new measurement
const recordMeasurement = async () => {
  errors.value = {}
  errorMessage.value = ''
  successMessage.value = ''

  // Validate required fields
  if (!form.value.measurementDate) {
    errors.value.measurementDate = 'Measurement date is required'
  }
  if (form.value.weightKg === null || form.value.weightKg === '') {
    errors.value.weightKg = 'Weight is required'
  }

  if (Object.keys(errors.value).length > 0) {
    return
  }

  loading.value = true

  try {
    const response = await fetch('/api/body-compositions', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${localStorage.getItem('auth_token')}`,
      },
      body: JSON.stringify({
        measurement_date: form.value.measurementDate,
        measurement_time: form.value.measurementTime || null,
        weight_kg: form.value.weightKg,
        body_fat_percent: form.value.bodyFatPercent,
        body_fat_kg: form.value.bodyFatKg,
        body_water_percent: form.value.bodyWaterPercent,
        muscle_mass: form.value.muscleMass,
        physical_rating: form.value.physicalRating || null,
        bone_mass: form.value.boneMass,
        kcal: form.value.kcal,
        bmr: form.value.bmr,
        visceral_fat: form.value.visceralFat,
      }),
    })

    const data = await response.json()

    if (!response.ok) {
      throw new Error(data.message || 'Failed to record measurement')
    }

    successMessage.value = 'Measurement recorded successfully!'
    resetForm()
    await loadMeasurements()

    setTimeout(() => {
      successMessage.value = ''
    }, 3000)
  } catch (error) {
    errorMessage.value = error.message || 'Failed to record measurement. Please try again.'
  } finally {
    loading.value = false
  }
}

// Reset form
const resetForm = () => {
  form.value = {
    measurementDate: new Date().toISOString().split('T')[0],
    measurementTime: '',
    weightKg: null,
    bodyFatPercent: null,
    bodyFatKg: null,
    bodyWaterPercent: null,
    muscleMass: null,
    physicalRating: null,
    boneMass: null,
    kcal: null,
    bmr: null,
    visceralFat: null,
  }
  errors.value = {}
}

// Format date
const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
  })
}

// Get rating label from value
const getRatingLabel = (value) => {
  if (value === null || value === undefined || value === '') {
    return null
  }
  return value
}

// Pagination computed properties
const totalPages = computed(() => Math.ceil(measurements.value.length / perPage.value))

const startIndex = computed(() => (currentPage.value - 1) * perPage.value)

const endIndex = computed(() => startIndex.value + perPage.value)

const paginatedMeasurements = computed(() => {
  return measurements.value.slice(startIndex.value, endIndex.value)
})

const pageNumbers = computed(() => {
  const pages = []
  const maxPages = 5

  if (totalPages.value <= maxPages) {
    for (let i = 1; i <= totalPages.value; i++) {
      pages.push(i)
    }
  } else {
    pages.push(1)

    let startPage = Math.max(2, currentPage.value - 1)
    let endPage = Math.min(totalPages.value - 1, currentPage.value + 1)

    if (startPage > 2) {
      pages.push('...')
    }

    for (let i = startPage; i <= endPage; i++) {
      pages.push(i)
    }

    if (endPage < totalPages.value - 1) {
      pages.push('...')
    }

    pages.push(totalPages.value)
  }

  return pages
})
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
