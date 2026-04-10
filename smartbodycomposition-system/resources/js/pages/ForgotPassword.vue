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

      <!-- Card -->
      <div class="shadow-lg border border-gray-200 rounded-lg">
        <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-b border-green-200 px-6 py-4 rounded-t-lg">
          <h2 class="text-2xl font-bold text-green-900">Reset Password</h2>
          <p class="text-gray-600 text-sm">Enter your email and we'll send you a reset link</p>
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
          <Transition name="fade">
            <div v-if="sent" class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg flex items-start gap-3">
              <svg class="h-5 w-5 text-green-600 flex-shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline>
              </svg>
              <div>
                <p class="text-green-800 text-sm font-medium">Check your inbox</p>
                <p class="text-green-700 text-sm mt-1">If an account with that email exists, a reset link has been sent. Check your spam folder if you don't see it.</p>
              </div>
            </div>
          </Transition>

          <form v-if="!sent" @submit.prevent="handleSubmit" class="space-y-4">
            <div class="space-y-2">
              <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
              <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <rect x="2" y="4" width="20" height="16" rx="2"></rect><path d="m10 9 5 3 5-3"></path>
                </svg>
                <input
                  id="email"
                  v-model="email"
                  type="email"
                  placeholder="john@example.com"
                  class="pl-10 w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500 transition"
                  :class="{ 'border-red-500 bg-red-50': errors.email }"
                  :disabled="loading"
                />
              </div>
              <p v-if="errors.email" class="text-xs text-red-600 flex items-center gap-1">
                <svg class="h-3 w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <circle cx="12" cy="12" r="10"></circle>
                  <line x1="12" y1="8" x2="12" y2="12"></line>
                  <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                {{ errors.email }}
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
              {{ loading ? 'Sending...' : 'Send Reset Link' }}
            </button>
          </form>

          <div class="mt-6 pt-6 border-t border-gray-200 text-center">
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
import { authService } from '@/services/api'

const email = ref('')
const errors = ref({})
const loading = ref(false)
const error = ref('')
const sent = ref(false)

async function handleSubmit() {
  errors.value = {}
  error.value = ''

  if (!email.value) {
    errors.value.email = 'Email is required'
  } else if (!/^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(email.value)) {
    errors.value.email = 'Please enter a valid email'
  }

  if (Object.keys(errors.value).length > 0) {
    return
  }

  loading.value = true
  try {
    await authService.forgotPassword(email.value)
    sent.value = true
  } catch (err) {
    error.value = err.response?.data?.error || err.message || 'Something went wrong. Please try again.'
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity 0.3s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>
