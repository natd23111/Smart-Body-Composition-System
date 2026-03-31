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
            <label class="block text-sm font-medium text-gray-900 mb-2">Physical Rating</label>
            <select
              v-model="form.physicalRating"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
            >
              <option value="">Select Rating</option>
              <option value="1">Very Poor</option>
              <option value="2">Poor</option>
              <option value="3">Fair</option>
              <option value="4">Good</option>
              <option value="5">Excellent</option>
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
      <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Measurements</h3>
      
      <div v-if="measurements.length === 0" class="text-center py-8">
        <p class="text-gray-600">No measurements recorded yet. Add one above to get started!</p>
      </div>

      <div v-else class="overflow-x-auto">
        <table class="min-w-full">
          <thead>
            <tr class="border-b border-gray-200">
              <th class="px-4 py-3 text-left text-sm font-semibold text-gray-900">Date</th>
              <th class="px-4 py-3 text-left text-sm font-semibold text-gray-900">Weight (kg)</th>
              <th class="px-4 py-3 text-left text-sm font-semibold text-gray-900">Body Fat (%)</th>
              <th class="px-4 py-3 text-left text-sm font-semibold text-gray-900">Muscle (kg)</th>
              <th class="px-4 py-3 text-left text-sm font-semibold text-gray-900">BMR</th>
              <th class="px-4 py-3 text-left text-sm font-semibold text-gray-900">Water (%)</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="measurement in measurements" :key="measurement.id" class="border-b border-gray-100 hover:bg-gray-50">
              <td class="px-4 py-3 text-sm text-gray-900">{{ formatDate(measurement.measurement_date) }}</td>
              <td class="px-4 py-3 text-sm text-gray-900">{{ measurement.weight_kg?.toFixed(1) || '-' }}</td>
              <td class="px-4 py-3 text-sm text-gray-900">{{ measurement.body_fat_percent?.toFixed(1) || '-' }}%</td>
              <td class="px-4 py-3 text-sm text-gray-900">{{ measurement.muscle_mass?.toFixed(1) || '-' }}</td>
              <td class="px-4 py-3 text-sm text-gray-900">{{ measurement.bmr ? measurement.bmr.toFixed(0) : '-' }}</td>
              <td class="px-4 py-3 text-sm text-gray-900">{{ measurement.body_water_percent?.toFixed(1) || '-' }}%</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const form = ref({
  measurementDate: new Date().toISOString().split('T')[0],
  measurementTime: '',
  weightKg: null,
  bodyFatPercent: null,
  bodyFatKg: null,
  bodyWaterPercent: null,
  muscleMass: null,
  physicalRating: '',
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
    measurements.value = data.data || data || []
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
    physicalRating: '',
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
