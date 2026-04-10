<template>
  <div class="min-h-screen bg-gradient-to-br from-green-50 to-white flex items-center justify-center p-4">
    <div class="w-full max-w-md">
      <!-- Header Card with Logo -->
      <div class="mb-8 text-center">
        <div class="inline-flex items-center justify-center mb-4">
          <div class="p-3 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg">
            <svg class="h-8 w-8 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
            </svg>
          </div>
        </div>
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Smart Body Composition</h1>
        <p class="text-gray-600">Start your health journey today</p>
      </div>

      <!-- Registration Card -->
      <div class="shadow-lg border border-gray-200 rounded-lg">
        <!-- Card Header -->
        <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-b border-green-200 px-6 py-4 rounded-t-lg">
          <h2 class="text-2xl font-bold text-green-900">Create Account</h2>
          <p class="text-gray-600 text-sm">Join our community and start tracking your health</p>
        </div>

        <!-- Card Content -->
        <div class="pt-6 px-6 pb-6">
          <!-- Error Alert -->
          <Transition name="fade">
            <div v-if="error" class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg flex items-start gap-3">
              <svg class="h-5 w-5 text-red-600 flex-shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
              </svg>
              <p class="text-red-800 text-sm">{{ error }}</p>
            </div>
          </Transition>

          <!-- Success Alert -->
          <Transition name="fade">
            <div v-if="success" class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg flex items-start gap-3">
              <svg class="h-5 w-5 text-green-600 flex-shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
              </svg>
              <p class="text-green-800 text-sm">{{ success }}</p>
            </div>
          </Transition>

          <!-- Form -->
          <form @submit.prevent="handleRegister" class="space-y-4">
            <!-- Full Name Field -->
            <div class="space-y-2">
              <label for="fullName" class="block text-sm font-medium text-gray-700">Full Name</label>
              <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                  <circle cx="12" cy="7" r="4"></circle>
                </svg>
                <input
                  id="fullName"
                  v-model="form.fullName"
                  type="text"
                  required
                  placeholder="John Doe"
                  class="pl-10 w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500 transition"
                  :class="{ 'border-red-500 bg-red-50': errors.fullName }"
                  :disabled="loading"
                />
              </div>
              <p v-if="errors.fullName" class="text-xs text-red-600 flex items-center gap-1">
                <svg class="h-3 w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <circle cx="12" cy="12" r="10"></circle>
                  <line x1="12" y1="8" x2="12" y2="12"></line>
                  <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                {{ errors.fullName }}
              </p>
            </div>

            <!-- Email Field -->
            <div class="space-y-2">
              <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
              <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                  <path d="m10 9 5 3 5-3"></path>
                </svg>
                <input
                  id="email"
                  v-model="form.email"
                  type="email"
                  required
                  placeholder="john@example.com"
                  @blur="checkEmailAvailability"
                  class="pl-10 pr-10 w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500 transition"
                  :class="{ 'border-red-500 bg-red-50': errors.email, 'border-green-500': emailAvailable === true }"
                  :disabled="loading"
                />
                <!-- Email availability indicator -->
                <div v-if="emailChecking" class="absolute right-3 top-1/2 -translate-y-1/2">
                  <svg class="animate-spin h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                </div>
                <div v-else-if="emailAvailable === true" class="absolute right-3 top-1/2 -translate-y-1/2">
                  <svg class="h-4 w-4 text-green-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                  </svg>
                </div>
                <div v-else-if="emailAvailable === false" class="absolute right-3 top-1/2 -translate-y-1/2">
                  <svg class="h-4 w-4 text-red-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="15" y1="9" x2="9" y2="15"></line>
                    <line x1="9" y1="9" x2="15" y2="15"></line>
                  </svg>
                </div>
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

            <!-- Password Field -->
            <div class="space-y-2">
              <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
              <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                  <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                </svg>
                <input
                  id="password"
                  v-model="form.password"
                  :type="showPassword ? 'text' : 'password'"
                  required
                  placeholder="••••••••"
                  class="pl-10 pr-10 w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500 transition"
                  :class="{ 'border-red-500 bg-red-50': errors.password }"
                  :disabled="loading"
                />
                <button
                  type="button"
                  @click="showPassword = !showPassword"
                  class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition"
                  :disabled="loading"
                >
                  <svg v-if="!showPassword" class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                    <circle cx="12" cy="12" r="3"></circle>
                  </svg>
                  <svg v-else class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                    <line x1="1" y1="1" x2="23" y2="23"></line>
                  </svg>
                </button>
              </div>
              <!-- Password strength indicator -->
              <div v-if="form.password" class="space-y-1">
                <div class="flex gap-1">
                  <div
                    class="flex-1 h-1 rounded-full transition-colors"
                    :class="form.password.length >= 8 ? 'bg-green-600' : 'bg-gray-300'"
                  ></div>
                  <div
                    class="flex-1 h-1 rounded-full transition-colors"
                    :class="/[A-Z]/.test(form.password) ? 'bg-green-600' : 'bg-gray-300'"
                  ></div>
                  <div
                    class="flex-1 h-1 rounded-full transition-colors"
                    :class="/[0-9]/.test(form.password) ? 'bg-green-600' : 'bg-gray-300'"
                  ></div>
                </div>
                <p class="text-xs text-gray-600">
                  {{ form.password.length < 8 ? 'Minimum 8 characters' : '✓ Length OK' }}
                  {{ /[A-Z]/.test(form.password) ? '✓ Uppercase' : '• Needs uppercase' }}
                  {{ /[0-9]/.test(form.password) ? '✓ Number' : '• Needs number' }}
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

            <!-- Confirm Password Field -->
            <div class="space-y-2">
              <label for="confirmPassword" class="block text-sm font-medium text-gray-700">Confirm Password</label>
              <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                  <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                </svg>
                <input
                  id="confirmPassword"
                  v-model="form.confirmPassword"
                  :type="showPassword ? 'text' : 'password'"
                  required
                  placeholder="••••••••"
                  class="pl-10 pr-10 w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500 transition"
                  :class="{ 'border-red-500 bg-red-50': errors.confirmPassword }"
                  :disabled="loading"
                />
              </div>
              <p v-if="errors.confirmPassword" class="text-xs text-red-600 flex items-center gap-1">
                <svg class="h-3 w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <circle cx="12" cy="12" r="10"></circle>
                  <line x1="12" y1="8" x2="12" y2="12"></line>
                  <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                {{ errors.confirmPassword }}
              </p>
            </div>

            <!-- Submit Button -->
            <button
              type="submit"
              :disabled="loading"
              class="w-full mt-6 py-2.5 px-4 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 disabled:from-green-400 disabled:to-emerald-400 text-white font-semibold rounded-lg transition-all duration-200 flex items-center justify-center gap-2"
            >
              <svg v-if="loading" class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              {{ loading ? 'Creating Account...' : 'Create Account' }}
            </button>
          </form>

          <!-- Toggle to Login -->
          <div class="mt-6 pt-6 border-t border-gray-200 text-center">
            <p class="text-sm text-gray-600 mb-3">Already have an account?</p>
            <router-link
              to="/login"
              class="w-full block border border-green-200 text-green-600 hover:bg-green-50 hover:text-green-700 font-semibold py-2 px-4 rounded-lg transition-colors"
            >
              Log In
            </router-link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/authPiniaStore'
import { registerUser, verifyEmailAvailability } from '@/services/authService'

const router = useRouter()
const authStore = useAuthStore()

const form = ref({
  fullName: '',
  email: '',
  password: '',
  confirmPassword: '',
})

const loading = ref(false)
const error = ref('')
const success = ref('')
const errors = ref({})
const showPassword = ref(false)
const emailChecking = ref(false)
const emailAvailable = ref(null)

// Real-time email availability check (better to use debounce in production)
const checkEmailAvailability = async () => {
  if (!form.value.email || !isValidEmail(form.value.email)) {
    emailAvailable.value = null
    return
  }

  emailChecking.value = true
  try {
    const available = await verifyEmailAvailability(form.value.email)
    emailAvailable.value = available

    if (!available) {
      errors.value.email = 'This email is already registered'
    } else {
      delete errors.value.email
    }
  } catch (err) {
    console.error('Error checking email:', err)
    emailAvailable.value = null
  } finally {
    emailChecking.value = false
  }
}

const handleRegister = async () => {
  // Reset messages
  error.value = ''
  success.value = ''
  errors.value = {}

  // Validation
  if (!form.value.fullName.trim()) {
    errors.value.fullName = 'Full name is required'
  } else if (form.value.fullName.trim().length < 2) {
    errors.value.fullName = 'Full name must be at least 2 characters'
  }

  if (!form.value.email) {
    errors.value.email = 'Email is required'
  } else if (!isValidEmail(form.value.email)) {
    errors.value.email = 'Please enter a valid email'
  } else if (emailAvailable.value === false) {
    errors.value.email = 'This email is already registered'
    } else if (emailAvailable.value === null) {
    errors.value.email = 'Please wait for email validation'
    }

  if (!form.value.password) {
    errors.value.password = 'Password is required'
  } else if (form.value.password.length < 8) {
    errors.value.password = 'Password must be at least 8 characters'
  } else if (!isStrongPassword(form.value.password)) {
    errors.value.password = 'Password must contain uppercase, lowercase, and numbers'
  }

  if (!form.value.confirmPassword) {
    errors.value.confirmPassword = 'Please confirm your password'
  } else if (form.value.password !== form.value.confirmPassword) {
    errors.value.confirmPassword = 'Passwords do not match'
  }

  // If there are errors, stop submission
  if (Object.keys(errors.value).length > 0) {
    return
  }

  loading.value = true

  try {
    console.log('Starting registration...')

    // Call registration service
    const newUser = await registerUser({
      fullName: form.value.fullName.trim(),
      email: form.value.email.trim().toLowerCase(),
      password: form.value.password,
    })

    console.log('Registration successful:', newUser)

    // Update auth store with new user
    authStore.user = {
      id: newUser.id,
      name: newUser.name,
      email: newUser.email,
      role: newUser.role,
    }
    authStore.isAuthenticated = true

    success.value = 'Account created successfully! Redirecting to dashboard...'

    // Redirect to dashboard after 1.5 seconds
    setTimeout(() => {
      router.push('/dashboard')
    }, 1500)
  } catch (err) {
    console.error('Registration error:', err)
    if (err.message.includes('already registered')) {
      error.value = 'This email is already registered. Please try logging in or use a different email.'
    } else {
      error.value = err.message || 'An error occurred during registration. Please try again.'
    }
    form.value.password = ''
    form.value.confirmPassword = ''
  } finally {
    loading.value = false
  }
}

const isValidEmail = (email) => {
  const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
  return re.test(email)
}

const isStrongPassword = (password) => {
  // Check for uppercase, lowercase, and number
  return /[A-Z]/.test(password) && /[a-z]/.test(password) && /[0-9]/.test(password)
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
