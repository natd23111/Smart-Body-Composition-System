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
        <p class="text-gray-600">Track your health journey with precision</p>
      </div>

      <!-- Invalid link state -->
      <div v-if="!token || !emailParam" class="shadow-lg border border-gray-200 rounded-lg p-8 text-center">
        <div class="p-3 bg-red-100 rounded-full inline-flex mb-4">
          <svg class="h-8 w-8 text-red-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line>
          </svg>
        </div>
        <h2 class="text-xl font-bold text-gray-900 mb-2">Invalid Reset Link</h2>
        <p class="text-gray-600 text-sm mb-6">This password reset link is invalid or has expired.</p>
        <router-link to="/forgot-password" class="inline-block px-4 py-2 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition-colors">
          Request a new link
        </router-link>
      </div>

      <!-- Reset form -->
      <div v-else class="shadow-lg border border-gray-200 rounded-lg">
        <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-b border-green-200 px-6 py-4 rounded-t-lg">
          <h2 class="text-2xl font-bold text-green-900">Set New Password</h2>
          <p class="text-gray-600 text-sm">Choose a strong password for your account</p>
        </div>

        <div class="pt-6 px-6 pb-6">
          <!-- Error -->
          <Transition name="fade">
            <div v-if="error" class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg flex items-start gap-3">
              <svg class="h-5 w-5 text-red-600 flex-shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line>
              </svg>
              <p class="text-red-800 text-sm">{{ error }}</p>
            </div>
          </Transition>

          <!-- Success -->
          <div v-if="done" class="text-center py-4">
            <div class="p-3 bg-green-100 rounded-full inline-flex mb-4">
              <svg class="h-8 w-8 text-green-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline>
              </svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">Password updated!</h3>
            <p class="text-gray-600 text-sm mb-6">Your password has been reset successfully. You can now log in.</p>
            <router-link to="/login" class="inline-block px-4 py-2 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition-colors">
              Go to Login
            </router-link>
          </div>

          <form v-if="!done" @submit.prevent="handleSubmit" class="space-y-4">
            <!-- New Password -->
            <div class="space-y-2">
              <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
              <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                </svg>
                <input
                  id="password"
                  v-model="form.password"
                  :type="showPassword ? 'text' : 'password'"
                  placeholder="••••••••"
                  class="pl-10 pr-10 w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500 transition"
                  :class="{ 'border-red-500 bg-red-50': errors.password }"
                  :disabled="loading"
                />
                <button type="button" @click="showPassword = !showPassword" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                  <svg v-if="!showPassword" class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>
                  </svg>
                  <svg v-else class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line>
                  </svg>
                </button>
              </div>
              <!-- Password strength indicator -->
              <div v-if="form.password" class="space-y-1">
                <div class="flex gap-1">
                  <div class="flex-1 h-1 rounded-full transition-colors" :class="form.password.length >= 8 ? 'bg-green-600' : 'bg-gray-300'"></div>
                  <div class="flex-1 h-1 rounded-full transition-colors" :class="/[A-Z]/.test(form.password) ? 'bg-green-600' : 'bg-gray-300'"></div>
                  <div class="flex-1 h-1 rounded-full transition-colors" :class="/[0-9]/.test(form.password) ? 'bg-green-600' : 'bg-gray-300'"></div>
                </div>
                <p class="text-xs text-gray-600">
                  {{ form.password.length < 8 ? 'Minimum 8 characters' : '&#x2713; Length OK' }}
                  {{ /[A-Z]/.test(form.password) ? '&#x2713; Uppercase' : '&bull; Needs uppercase' }}
                  {{ /[0-9]/.test(form.password) ? '&#x2713; Number' : '&bull; Needs number' }}
                </p>
              </div>
              <p v-if="errors.password" class="text-xs text-red-600 flex items-center gap-1">
                <svg class="h-3 w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <circle cx="12" cy="12" r="10"></circle>
                  <line x1="12" y1="8" x2="12" y2="12"></line>
                  <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                {{ errors.password }}
              </p>
            </div>

            <!-- Confirm Password -->
            <div class="space-y-2">
              <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
              <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                </svg>
                <input
                  id="password_confirmation"
                  v-model="form.password_confirmation"
                  :type="showConfirm ? 'text' : 'password'"
                  placeholder="••••••••"
                  class="pl-10 pr-10 w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500 transition"
                  :class="{ 'border-red-500 bg-red-50': errors.password_confirmation }"
                  :disabled="loading"
                />
                <button type="button" @click="showConfirm = !showConfirm" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                  <svg v-if="!showConfirm" class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>
                  </svg>
                  <svg v-else class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line>
                  </svg>
                </button>
              </div>
              <p v-if="errors.password_confirmation" class="text-xs text-red-600 flex items-center gap-1">
                <svg class="h-3 w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <circle cx="12" cy="12" r="10"></circle>
                  <line x1="12" y1="8" x2="12" y2="12"></line>
                  <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                {{ errors.password_confirmation }}
              </p>
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
              {{ loading ? 'Updating...' : 'Update Password' }}
            </button>
          </form>

          <div v-if="!done" class="mt-6 pt-6 border-t border-gray-200 text-center">
            <router-link to="/login" class="text-sm text-green-600 hover:text-green-700 font-medium transition-colors">
              &larr; Back to Login
            </router-link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRoute } from 'vue-router'
import { authService } from '@/services/api'

const route = useRoute()
const token = route.query.token ?? ''
const emailParam = route.query.email ?? ''

const form = ref({ password: '', password_confirmation: '' })
const errors = ref({})
const error = ref('')
const loading = ref(false)
const done = ref(false)
const showPassword = ref(false)
const showConfirm = ref(false)

async function handleSubmit() {
  errors.value = {}
  error.value = ''

  if (!form.value.password) {
    errors.value.password = 'Password is required'
  } else if (form.value.password.length < 8) {
    errors.value.password = 'Password must be at least 8 characters'
  } else if (!isStrongPassword(form.value.password)) {
    errors.value.password = 'Password must contain uppercase, lowercase, and numbers'
  }

  if (!form.value.password_confirmation) {
    errors.value.password_confirmation = 'Please confirm your password'
  } else if (form.value.password !== form.value.password_confirmation) {
    errors.value.password_confirmation = 'Passwords do not match'
  }

  if (Object.keys(errors.value).length > 0) {
    return
  }

  loading.value = true
  try {
    await authService.resetPassword(token, emailParam, form.value.password, form.value.password_confirmation)
    done.value = true
  } catch (err) {
    error.value = err.response?.data?.error || err.message || 'Something went wrong. Please try again.'
  } finally {
    loading.value = false
  }
}

const isStrongPassword = (password) => {
  return /[A-Z]/.test(password) && /[a-z]/.test(password) && /[0-9]/.test(password)
}
</script>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity 0.3s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>
