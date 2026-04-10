<template>
  <div class="min-h-screen bg-gradient-to-br from-green-50 to-white flex items-center justify-center p-4">
    <div class="w-full max-w-md">
      <!-- Header -->
      <div class="mb-8 text-center">
        <div class="inline-flex items-center justify-center mb-4">
          <div class="p-3 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg">
            <svg class="h-8 w-8 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
            </svg>
          </div>
        </div>
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Smart Body Composition</h1>
        <p class="text-gray-600">Let's set up your profile</p>
      </div>

      <!-- Card -->
      <div class="shadow-lg border border-gray-200 rounded-lg">
        <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-b border-green-200 px-6 py-4 rounded-t-lg">
          <h2 class="text-2xl font-bold text-green-900">Complete Your Profile</h2>
          <p class="text-gray-600 text-sm">This helps us personalise your health recommendations</p>
        </div>

        <!-- Step indicator -->
        <div class="px-6 pt-5 flex items-center gap-2">
          <div class="flex-1 h-1.5 rounded-full bg-green-500"></div>
          <div class="flex-1 h-1.5 rounded-full bg-green-500"></div>
          <span class="text-xs text-gray-500 ml-1">Step 2 of 2</span>
        </div>

        <div class="px-6 pt-4 pb-6">
          <!-- Error -->
          <Transition name="fade">
            <div v-if="error" class="mb-5 p-4 bg-red-50 border border-red-200 rounded-lg">
              <p class="text-red-800 text-sm">{{ error }}</p>
            </div>
          </Transition>

          <!-- Validation summary -->
          <Transition name="fade">
            <div v-if="hasValidationErrors" class="mb-5 p-4 bg-red-50 border border-red-200 rounded-lg">
              <p class="text-red-800 text-sm font-medium">Please fix the following errors:</p>
              <ul class="mt-1 list-disc list-inside space-y-0.5">
                <li v-for="(msg, field) in errors" :key="field" class="text-red-700 text-sm">{{ msg }}</li>
              </ul>
            </div>
          </Transition>

          <form @submit.prevent="handleSubmit" class="space-y-5">
            <!-- Age -->
            <div class="space-y-2">
              <label for="age" class="block text-sm font-medium text-gray-700">Age</label>
              <input
                id="age"
                v-model.number="form.age"
                type="number"
                min="1"
                max="150"
                placeholder="e.g. 25"
                class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500 transition"
                :class="{ 'border-red-500 bg-red-50': errors.age }"
                :disabled="loading"
              />
              <p v-if="errors.age" class="text-xs text-red-600">{{ errors.age }}</p>
            </div>

            <!-- Gender -->
            <div class="space-y-2">
              <label class="block text-sm font-medium text-gray-700">Gender</label>
              <div class="grid grid-cols-3 gap-3">
                <button
                  v-for="option in genderOptions"
                  :key="option.value"
                  type="button"
                  @click="form.gender = option.value"
                  :disabled="loading"
                  :class="form.gender === option.value
                    ? 'border-green-500 bg-green-50 text-green-700 ring-1 ring-green-500'
                    : 'border-gray-300 bg-gray-50 text-gray-700 hover:border-green-400 hover:bg-green-50'"
                  class="py-2 px-3 border rounded-lg text-sm font-medium transition-all flex flex-col items-center gap-1"
                >
                  <span class="text-lg">{{ option.icon }}</span>
                  <span>{{ option.label }}</span>
                </button>
              </div>
              <p v-if="errors.gender" class="text-xs text-red-600">{{ errors.gender }}</p>
            </div>

            <!-- Height -->
            <div class="space-y-2">
              <label for="height" class="block text-sm font-medium text-gray-700">Height (cm)</label>
              <input
                id="height"
                v-model.number="form.height_cm"
                type="number"
                min="50"
                max="250"
                step="0.1"
                placeholder="e.g. 170"
                class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500 transition"
                :class="{ 'border-red-500 bg-red-50': errors.height_cm }"
                :disabled="loading"
              />
              <p v-if="errors.height_cm" class="text-xs text-red-600">{{ errors.height_cm }}</p>
            </div>

            <button
              type="submit"
              :disabled="loading"
              class="w-full mt-2 py-2.5 px-4 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 disabled:from-green-400 disabled:to-emerald-400 text-white font-semibold rounded-lg transition-all duration-200 flex items-center justify-center gap-2"
            >
              <svg v-if="loading" class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              {{ loading ? 'Saving...' : 'Save' }}
            </button>
          </form>

          <div class="mt-4 text-center">
            <button
              type="button"
              @click="skipSetup"
              class="text-sm text-gray-500 hover:text-gray-700 transition-colors"
              :disabled="loading"
            >
              Skip for now
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/authPiniaStore'
import { updateUserProfile } from '@/services/authService'

const router = useRouter()
const authStore = useAuthStore()

const form = ref({
  age: '',
  gender: '',
  height_cm: '',
})

const errors = ref({})
const error = ref('')
const loading = ref(false)

const hasValidationErrors = computed(() => Object.keys(errors.value).length > 0)

const genderOptions = [
  { value: 'Male',   label: 'Male',   icon: '\u2642\uFE0F' },
  { value: 'Female', label: 'Female', icon: '\u2640\uFE0F' },
  { value: 'Other',  label: 'Other',  icon: '\u26A7\uFE0F' },
]

function validate() {
  errors.value = {}

  if (!form.value.age) {
    errors.value.age = 'Age is required'
  } else if (form.value.age < 1 || form.value.age > 150) {
    errors.value.age = 'Please enter a valid age'
  }

  if (!form.value.gender) {
    errors.value.gender = 'Please select a gender'
  }

  if (!form.value.height_cm) {
    errors.value.height_cm = 'Height is required'
  } else if (form.value.height_cm < 50 || form.value.height_cm > 250) {
    errors.value.height_cm = 'Please enter a valid height (50–250 cm)'
  }

  return Object.keys(errors.value).length === 0
}

async function handleSubmit() {
  if (!validate()) return

  loading.value = true
  error.value = ''
  errors.value = {}
  try {
    const updated = await updateUserProfile({
      fullName: authStore.user?.name,
      email: authStore.user?.email,
      age: form.value.age,
      gender: form.value.gender,
      height_cm: form.value.height_cm,
    })
    authStore.user = updated
    localStorage.setItem('user', JSON.stringify(updated))
    router.push('/dashboard')
  } catch (err) {
    error.value = err.message || 'Failed to save profile. Please try again.'
  } finally {
    loading.value = false
  }
}

function skipSetup() {
  router.push('/dashboard')
}
</script>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity 0.3s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>
