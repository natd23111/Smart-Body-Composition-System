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
            <label class="block text-sm font-medium text-gray-900 mb-2">Weight ({{ unitStore.weightLabel }}) *</label>
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

        <!-- Calories and Body Age -->
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
            <label class="block text-sm font-medium text-gray-900 mb-2">Body Age</label>
            <input
              v-model.number="form.bodyAge"
              type="number"
              step="1"
              placeholder="35"
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
              <th class="px-4 py-3 text-left text-sm font-semibold text-gray-900">Weight ({{ unitStore.weightLabel }})</th>
              <th class="px-4 py-3 text-left text-sm font-semibold text-gray-900">Body Fat (%)</th>
              <th class="px-4 py-3 text-left text-sm font-semibold text-gray-900">Body Fat ({{ unitStore.weightLabel }})</th>
              <th class="px-4 py-3 text-left text-sm font-semibold text-gray-900">Muscle ({{ unitStore.weightLabel }})</th>
              <th class="px-4 py-3 text-left text-sm font-semibold text-gray-900">Bone Mass ({{ unitStore.weightLabel }})</th>
              <th class="px-4 py-3 text-left text-sm font-semibold text-gray-900">Water (%)</th>
              <th class="px-4 py-3 text-left text-sm font-semibold text-gray-900">Visceral Fat</th>
              <th class="px-4 py-3 text-left text-sm font-semibold text-gray-900">Calories (kcal)</th>
              <th class="px-4 py-3 text-left text-sm font-semibold text-gray-900">Body Age</th>
              <th class="px-4 py-3 text-left text-sm font-semibold text-gray-900">Physical Rating</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="measurement in paginatedMeasurements" :key="measurement.id" class="border-b border-gray-100 hover:bg-gray-50">
              <td class="px-4 py-3 text-sm text-gray-900">{{ formatDate(measurement.measurement_date) }}</td>
              <td class="px-4 py-3 text-sm text-gray-900">{{ measurement.measurement_time || '-' }}</td>
              <td class="px-4 py-3 text-sm text-gray-900">{{ unitStore.convertWeight(measurement.weight_kg)?.toFixed(1) || '-' }}</td>
              <td class="px-4 py-3 text-sm text-gray-900">{{ measurement.body_fat_percent?.toFixed(1) || '-' }}%</td>
              <td class="px-4 py-3 text-sm text-gray-900">{{ unitStore.convertWeight(measurement.body_fat_kg)?.toFixed(1) || '-' }}</td>
              <td class="px-4 py-3 text-sm text-gray-900">{{ unitStore.convertWeight(measurement.muscle_mass)?.toFixed(1) || '-' }}</td>
              <td class="px-4 py-3 text-sm text-gray-900">{{ unitStore.convertWeight(measurement.bone_mass)?.toFixed(1) || '-' }}</td>
              <td class="px-4 py-3 text-sm text-gray-900">{{ measurement.body_water_percent?.toFixed(1) || '-' }}%</td>
              <td class="px-4 py-3 text-sm text-gray-900">{{ measurement.visceral_fat?.toFixed(1) || '-' }}</td>
              <td class="px-4 py-3 text-sm text-gray-900">{{ measurement.kcal || '-' }}</td>
              <td class="px-4 py-3 text-sm text-gray-900">{{ measurement.body_age || '-' }}</td>
              <td class="px-4 py-3 text-sm text-gray-900">{{ getRatingLabel(measurement.physical_rating) || '-' }}</td>
              <td class="px-4 py-3 text-sm text-gray-900 whitespace-nowrap">
                <button @click="startEditMeasurement(measurement)" class="p-1.5 hover:bg-yellow-100 rounded transition-colors" title="Edit">
                  <svg class="h-4 w-4 text-yellow-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                  </svg>
                </button>
                <button @click="confirmDeleteMeasurement(measurement)" class="p-1.5 hover:bg-red-100 rounded transition-colors" title="Delete">
                  <svg class="h-4 w-4 text-red-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="3 6 5 6 21 6"></polyline><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"></path><path d="M10 11v6"></path><path d="M14 11v6"></path>
                  </svg>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
          <!-- Edit Measurement Modal -->
    <div v-if="editModalOpen" class="fixed inset-0 flex items-center justify-center z-50 bg-opacity-30 backdrop-blur-sm">
      <div class="bg-white rounded-lg shadow-lg p-8 w-full max-w-lg relative">
        <h3 class="text-lg font-semibold mb-4">Edit Measurement</h3>
        <form @submit.prevent="updateMeasurement" class="space-y-4">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-900 mb-2">Measurement Date *</label>
              <input v-model="editForm.measurement_date" type="date" required class="w-full px-4 py-2 border border-gray-300 rounded-lg" />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-900 mb-2">Measurement Time</label>
              <input v-model="editForm.measurement_time" type="time" class="w-full px-4 py-2 border border-gray-300 rounded-lg" />
            </div>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-900 mb-2">Weight ({{ unitStore.weightLabel }}) *</label>
              <input v-model.number="editForm.weight_kg" type="number" step="0.1" required class="w-full px-4 py-2 border border-gray-300 rounded-lg" />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-900 mb-2">Body Fat (%)</label>
              <input v-model.number="editForm.body_fat_percent" type="number" step="0.1" class="w-full px-4 py-2 border border-gray-300 rounded-lg" />
            </div>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-900 mb-2">Body Fat (kg)</label>
              <input v-model.number="editForm.body_fat_kg" type="number" step="0.1" class="w-full px-4 py-2 border border-gray-300 rounded-lg" />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-900 mb-2">Body Water (%)</label>
              <input v-model.number="editForm.body_water_percent" type="number" step="0.1" class="w-full px-4 py-2 border border-gray-300 rounded-lg" />
            </div>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-900 mb-2">Muscle Mass (kg)</label>
              <input v-model.number="editForm.muscle_mass" type="number" step="0.1" class="w-full px-4 py-2 border border-gray-300 rounded-lg" />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-900 mb-2">Bone Mass (kg)</label>
              <input v-model.number="editForm.bone_mass" type="number" step="0.1" class="w-full px-4 py-2 border border-gray-300 rounded-lg" />
            </div>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-900 mb-2">Physical Rating (1-9)</label>
              <select v-model.number="editForm.physical_rating" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                <option :value="null">Select Rating</option>
                <option v-for="n in 9" :key="n" :value="n">{{ n }}</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-900 mb-2">Visceral Fat</label>
              <input v-model.number="editForm.visceral_fat" type="number" step="0.1" class="w-full px-4 py-2 border border-gray-300 rounded-lg" />
            </div>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-900 mb-2">Calories (kcal)</label>
              <input v-model.number="editForm.kcal" type="number" step="1" class="w-full px-4 py-2 border border-gray-300 rounded-lg" />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-900 mb-2">Body Age</label>
              <input v-model.number="editForm.body_age" type="number" step="1" class="w-full px-4 py-2 border border-gray-300 rounded-lg" />
            </div>
          </div>
          <div class="flex justify-end gap-2 mt-4">
            <button type="button" @click="editModalOpen = false" class="px-4 py-2 border rounded-lg">Cancel</button>
            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg">Save</button>
          </div>
        </form>
        <button @click="editModalOpen = false" class="absolute top-2 right-2 text-gray-400 hover:text-gray-700">&times;</button>
      </div>
    </div>

        <!-- Delete Confirmation Modal -->
    <div v-if="deleteModalOpen" class="fixed inset-0 flex items-center justify-center z-50 bg-opacity-30 backdrop-blur-sm">
      <div class="bg-white rounded-lg shadow-lg p-8 w-full max-w-sm relative">
        <h3 class="text-lg font-semibold mb-4">Delete Measurement</h3>
        <p>Are you sure you want to delete this measurement?</p>
        <div class="flex justify-end gap-2 mt-6">
          <button type="button" @click="deleteModalOpen = false" class="px-4 py-2 border rounded-lg">Cancel</button>
          <button type="button" @click="deleteMeasurement" class="px-4 py-2 bg-red-600 text-white rounded-lg">Delete</button>
        </div>
        <button @click="deleteModalOpen = false" class="absolute top-2 right-2 text-gray-400 hover:text-gray-700">&times;</button>
      </div>
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
import { useUnitStore } from '../stores/unitStore'

const unitStore = useUnitStore()

// Delete modal state
const deleteModalOpen = ref(false)
const measurementToDelete = ref(null)

function confirmDeleteMeasurement(measurement) {
  measurementToDelete.value = measurement
  deleteModalOpen.value = true
}

async function deleteMeasurement() {
  try {
    const response = await fetch(`/api/body-compositions/${measurementToDelete.value.id}`, {
      method: 'DELETE',
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('auth_token')}`,
      },
    })
    if (!response.ok) throw new Error('Failed to delete measurement')
    deleteModalOpen.value = false
    await loadMeasurements()
  } catch (error) {
    alert(error.message || 'Failed to delete measurement')
  }
}
// Edit modal state
const editModalOpen = ref(false)
const editForm = ref({})

// Start editing a measurement
function startEditMeasurement(measurement) {
  // Map backend keys to modal keys for editing
  editForm.value = {
    id: measurement.id,
    measurement_date: measurement.measurement_date || '',
    measurement_time: measurement.measurement_time || '',
    weight_kg: unitStore.convertWeight(measurement.weight_kg),
    body_fat_percent: measurement.body_fat_percent,
    body_fat_kg: unitStore.convertWeight(measurement.body_fat_kg),
    body_water_percent: measurement.body_water_percent,
    muscle_mass: unitStore.convertWeight(measurement.muscle_mass),
    bone_mass: unitStore.convertWeight(measurement.bone_mass),
    kcal: measurement.kcal,
    body_age: measurement.body_age,
    visceral_fat: measurement.visceral_fat,
    physical_rating: measurement.physical_rating,
  }
  editModalOpen.value = true
}

// Update measurement (PUT/PATCH)
async function updateMeasurement() {
  try {
    const payload = {
      ...editForm.value,
      weight_kg: unitStore.toKg(editForm.value.weight_kg),
      body_fat_kg: unitStore.toKg(editForm.value.body_fat_kg),
      muscle_mass: unitStore.toKg(editForm.value.muscle_mass),
      bone_mass: unitStore.toKg(editForm.value.bone_mass),
    }
    const response = await fetch(`/api/body-compositions/${editForm.value.id}`, {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${localStorage.getItem('auth_token')}`,
      },
      body: JSON.stringify(payload),
    })
    const data = await response.json()
    if (!response.ok) throw new Error(data.message || 'Failed to update measurement')
    editModalOpen.value = false
    await loadMeasurements()
  } catch (error) {
    alert(error.message || 'Failed to update measurement')
  }
}


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
  bodyAge: null,
  visceralFat: null,
})

const measurements = ref([])
const loading = ref(false)
const successMessage = ref('')
const errorMessage = ref('')
const errors = ref({})
const currentPage = ref(1)
const perPage = ref(7)

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
        weight_kg: unitStore.toKg(form.value.weightKg),
        body_fat_percent: form.value.bodyFatPercent,
        body_fat_kg: unitStore.toKg(form.value.bodyFatKg),
        body_water_percent: form.value.bodyWaterPercent,
        muscle_mass: unitStore.toKg(form.value.muscleMass),
        physical_rating: form.value.physicalRating || null,
        bone_mass: unitStore.toKg(form.value.boneMass),
        kcal: form.value.kcal,
        body_age: form.value.bodyAge,
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
    bodyAge: null,
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
