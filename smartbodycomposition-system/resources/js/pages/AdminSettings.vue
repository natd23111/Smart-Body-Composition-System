<template>
  <div class="space-y-6">

    <!-- Page Header -->
    <div>
      <h1 class="text-3xl font-bold text-gray-900">System Settings</h1>
      <p class="text-gray-500 mt-1">Configure application-wide preferences and access controls</p>
    </div>

    <!-- Save Success Banner -->
    <div v-if="saved" class="flex items-center gap-3 p-4 bg-green-50 border border-green-200 rounded-lg text-green-700 text-sm">
      <svg class="h-5 w-5 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <polyline points="20 6 9 17 4 12"></polyline>
      </svg>
      Settings saved successfully.
    </div>


    <!-- Email Configuration -->
    <section class="bg-white rounded-lg shadow border border-gray-200 p-6 space-y-4">
      <h2 class="text-lg font-semibold text-gray-900">Email Configuration (SMTP)</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">SMTP Host</label>
          <input v-model="form.smtp.host" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="smtp.mailtrap.io" />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">SMTP Port</label>
          <input v-model="form.smtp.port" type="number" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="587" />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">SMTP Username</label>
          <input v-model="form.smtp.username" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="smtp-user" />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">SMTP Password</label>
          <input v-model="form.smtp.password" type="password" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="••••••••" />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">From Address</label>
          <input v-model="form.smtp.from" type="email" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="noreply@smartbody.app" />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">From Name</label>
          <input v-model="form.smtp.from_name" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Smart Body Composition" />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Encryption</label>
          <select v-model="form.smtp.encryption" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
            <option value="tls">TLS</option>
            <option value="ssl">SSL</option>
            <option value="none">None</option>
          </select>
        </div>
      </div>
    </section>

    <!-- Session & Security -->
    <section class="bg-white rounded-lg shadow border border-gray-200 p-6 space-y-4">
      <h2 class="text-lg font-semibold text-gray-900">Session &amp; Security</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Session Timeout (minutes)</label>
          <input v-model="form.sessionTimeout" type="number" min="5" max="1440" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500" />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Max Login Attempts</label>
          <input v-model="form.maxLoginAttempts" type="number" min="3" max="20" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500" />
        </div>
      </div>
    </section>

    <!-- Action Buttons -->
    <div class="flex items-center gap-3 pb-8">
      <button
        @click="saveSettings"
        class="px-6 py-2 bg-green-600 text-white rounded-lg text-sm font-medium hover:bg-green-700 transition-colors"
      >
        Save Settings
      </button>
      <button
        @click="resetSettings"
        class="px-6 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors"
      >
        Reset to Defaults
      </button>
    </div>

  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import { adminService } from '@/services/api'

const saved = ref(false)
const loading = ref(true)
const error = ref('')

const defaults = {
  weightUnit: 'kg',
  heightUnit: 'cm',
  language: 'en',
  timezone: 'Asia/Kuala_Lumpur',
  notifications: {
    emailOnRegister: true,
    emailOnGoalAchieved: true,
    emailOnInactivity: false,
    weeklyDigest: false,
  },
  smtp: {
    host: 'smtp.mailtrap.io',
    port: 587,
    username: '',
    password: '',
    from: 'noreply@smartbody.app',
    from_name: 'Smart Body Composition',
    encryption: 'tls',
  },
  sessionTimeout: 120,
  maxLoginAttempts: 5,
  maintenanceMode: false,
}

const form = ref(JSON.parse(JSON.stringify(defaults)))

const roles = [
  { value: 'admin', label: 'Administrator', description: 'Full system access — can manage users, templates, and settings.' },
  { value: 'user', label: 'Regular User', description: 'Can record body composition data, view trends, and manage their own goals.' },
]

const notificationOptions = [
  { key: 'emailOnRegister', label: 'Send a welcome email on new user registration' },
  { key: 'emailOnGoalAchieved', label: 'Notify users when a goal is achieved' },
  { key: 'emailOnInactivity', label: 'Remind users after 14 days of inactivity' },
  { key: 'weeklyDigest', label: 'Send a weekly progress digest email' },
]

function saveSettings() {
  persistSettings()
}

function resetSettings() {
  form.value = JSON.parse(JSON.stringify(defaults))
  saved.value = false
  error.value = ''
}

async function loadSettings() {
  loading.value = true
  error.value = ''

  try {
    const response = await adminService.getSettings()
    const data = response.data
    defaults.notifications = { ...defaults.notifications, ...(data.notifications ?? {}) }
    defaults.smtp = { ...defaults.smtp, ...(data.smtp ?? {}) }
    defaults.sessionTimeout = data.sessionTimeout ?? defaults.sessionTimeout
    defaults.maxLoginAttempts = data.maxLoginAttempts ?? defaults.maxLoginAttempts
    defaults.maintenanceMode = data.maintenanceMode ?? defaults.maintenanceMode
    form.value = JSON.parse(JSON.stringify(defaults))
  } catch (requestError) {
    error.value = requestError.response?.data?.message || requestError.message || 'Failed to load settings.'
  } finally {
    loading.value = false
  }
}

async function persistSettings() {
  error.value = ''

  try {
    const response = await adminService.updateSettings(form.value)
    const data = response.data.data
    defaults.notifications = { ...defaults.notifications, ...(data.notifications ?? {}) }
    defaults.smtp = { ...defaults.smtp, ...(data.smtp ?? {}) }
    defaults.sessionTimeout = data.sessionTimeout ?? defaults.sessionTimeout
    defaults.maxLoginAttempts = data.maxLoginAttempts ?? defaults.maxLoginAttempts
    defaults.maintenanceMode = data.maintenanceMode ?? defaults.maintenanceMode
    form.value = JSON.parse(JSON.stringify(defaults))
    saved.value = true
    setTimeout(() => { saved.value = false }, 3000)
  } catch (requestError) {
    error.value = requestError.response?.data?.message || requestError.message || 'Failed to save settings.'
  }
}

onMounted(loadSettings)
</script>
