<template>
  <div class="min-h-screen bg-gradient-to-br from-blue-50 to-blue-100 dark:from-slate-950 dark:to-slate-900 flex items-center justify-center px-4">
    <div class="w-full max-w-md">
      <!-- Header -->
      <div class="text-center mb-8">
        <h1 class="text-4xl font-bold text-slate-900 dark:text-white mb-2">
          Smart Body<br />Composition
        </h1>
        <p class="text-slate-600 dark:text-slate-400">Track, Analyze, Transform</p>
      </div>

      <!-- Login Card -->
      <div class="bg-white dark:bg-slate-800 rounded-lg shadow-xl p-8">
        <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-6">Login</h2>

        <!-- Error Alert -->
        <Transition name="fade">
          <div v-if="error" class="mb-4 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg text-red-600 dark:text-red-400 text-sm">
            {{ error }}
          </div>
        </Transition>

        <!-- Success Alert -->
        <Transition name="fade">
          <div v-if="success" class="mb-4 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg text-green-600 dark:text-green-400 text-sm">
            {{ success }}
          </div>
        </Transition>

        <!-- Form -->
        <form @submit.prevent="handleLogin" class="space-y-4">
          <!-- Email Field -->
          <div>
            <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
              Email Address
            </label>
            <input
              id="email"
              v-model="form.email"
              type="email"
              required
              placeholder="you@example.com"
              class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-700 dark:text-white transition"
              :disabled="loading"
            />
            <p v-if="errors.email" class="mt-1 text-xs text-red-600 dark:text-red-400">{{ errors.email }}</p>
          </div>

          <!-- Password Field -->
          <div>
            <label for="password" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
              Password
            </label>
            <input
              id="password"
              v-model="form.password"
              type="password"
              required
              placeholder="••••••••"
              class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-700 dark:text-white transition"
              :disabled="loading"
            />
            <p v-if="errors.password" class="mt-1 text-xs text-red-600 dark:text-red-400">{{ errors.password }}</p>
          </div>

          <!-- Remember Me -->
          <div class="flex items-center">
            <input
              id="remember"
              v-model="form.remember"
              type="checkbox"
              class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-2 focus:ring-blue-500 cursor-pointer"
              :disabled="loading"
            />
            <label for="remember" class="ml-2 text-sm text-slate-600 dark:text-slate-400 cursor-pointer">
              Remember me
            </label>
          </div>

          <!-- Submit Button -->
          <button
            type="submit"
            :disabled="loading"
            class="w-full py-2 px-4 bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 text-white font-semibold rounded-lg transition duration-200 flex items-center justify-center gap-2"
          >
            <svg v-if="loading" class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            {{ loading ? 'Logging in...' : 'Login' }}
          </button>
        </form>

        <!-- Divider -->
        <div class="my-6 flex items-center gap-4">
          <div class="flex-1 h-px bg-slate-300 dark:bg-slate-600"></div>
          <span class="text-sm text-slate-500 dark:text-slate-400">or continue with</span>
          <div class="flex-1 h-px bg-slate-300 dark:bg-slate-600"></div>
        </div>

        <!-- Social Logins (Optional for future) -->
        <div class="grid grid-cols-2 gap-3">
          <button type="button" class="py-2 px-4 border border-slate-300 dark:border-slate-600 rounded-lg text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 transition" disabled title="Coming soon">
            Google
          </button>
          <button type="button" class="py-2 px-4 border border-slate-300 dark:border-slate-600 rounded-lg text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 transition" disabled title="Coming soon">
            Microsoft
          </button>
        </div>
      </div>

      <!-- Footer -->
      <p class="text-center text-sm text-slate-600 dark:text-slate-400 mt-6">
        Don't have an account?
        <a href="#" class="font-medium text-blue-600 dark:text-blue-400 hover:underline">Sign up here</a>
      </p>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { authService } from '@/services/api'

const router = useRouter()

const form = ref({
  email: '',
  password: '',
  remember: false,
})

const loading = ref(false)
const error = ref('')
const success = ref('')
const errors = ref({})

const handleLogin = async () => {
  // Reset messages
  error.value = ''
  success.value = ''
  errors.value = {}

  // Basic validation
  if (!form.value.email || !form.value.password) {
    errors.value.email = form.value.email ? '' : 'Email is required'
    errors.value.password = form.value.password ? '' : 'Password is required'
    return
  }

  loading.value = true

  try {
    const response = await authService.login(form.value.email, form.value.password)

    const { token, user } = response.data

    // Store token and user data
    localStorage.setItem('auth_token', token)
    localStorage.setItem('user', JSON.stringify(user))

    success.value = 'Login successful! Redirecting...'

    // Redirect to dashboard after 1 second
    setTimeout(() => {
      router.push('/dashboard')
    }, 1000)
  } catch (err) {
    console.error('Login error:', err)

    if (err.response?.status === 401) {
      error.value = 'Invalid email or password'
    } else if (err.response?.data?.message) {
      error.value = err.response.data.message
    } else {
      error.value = 'An error occurred. Please try again.'
    }

    // Clear sensitive fields
    form.value.password = ''
  } finally {
    loading.value = false
  }
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
