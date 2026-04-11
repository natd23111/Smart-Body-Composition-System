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

    <!-- Measurement Units -->
    <section class="bg-white rounded-lg shadow border border-gray-200 p-6 space-y-4">
      <h2 class="text-lg font-semibold text-gray-900">Measurement Units</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Weight Unit</label>
          <select v-model="form.weightUnit" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
            <option value="kg">Kilograms (kg)</option>
            <option value="lbs">Pounds (lbs)</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Height Unit</label>
          <select v-model="form.heightUnit" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
            <option value="cm">Centimetres (cm)</option>
            <option value="ft">Feet &amp; Inches (ft/in)</option>
          </select>
        </div>
      </div>
    </section>

    <!-- Language & Localization -->
    <section class="bg-white rounded-lg shadow border border-gray-200 p-6 space-y-4">
      <h2 class="text-lg font-semibold text-gray-900">Language &amp; Localization</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Language</label>
          <select v-model="form.language" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
            <option value="en">English</option>
            <option value="ms">Bahasa Malaysia</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Timezone</label>
          <select v-model="form.timezone" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
            <option value="Asia/Kuala_Lumpur">Asia/Kuala Lumpur (UTC+8)</option>
            <option value="UTC">UTC</option>
          </select>
        </div>
      </div>
    </section>

    <!-- Access Control Roles -->
    <section class="bg-white rounded-lg shadow border border-gray-200 p-6 space-y-4">
      <h2 class="text-lg font-semibold text-gray-900">Access Control Roles</h2>
      <div class="space-y-3">
        <div v-for="role in roles" :key="role.value" class="flex items-center justify-between py-2 border-b border-gray-100 last:border-0">
          <div>
            <p class="text-sm font-medium text-gray-800">{{ role.label }}</p>
            <p class="text-xs text-gray-500">{{ role.description }}</p>
          </div>
          <span :class="['px-2 py-0.5 rounded-full text-xs font-semibold', role.value === 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-green-100 text-green-700']">
            {{ role.value === 'admin' ? 'Admin' : 'User' }}
          </span>
        </div>
      </div>
    </section>

    <!-- Notification Settings -->
    <section class="bg-white rounded-lg shadow border border-gray-200 p-6 space-y-4">
      <h2 class="text-lg font-semibold text-gray-900">Notification Settings</h2>
      <div class="space-y-3">
        <label v-for="notif in notificationOptions" :key="notif.key" class="flex items-center gap-3 cursor-pointer">
          <input
            type="checkbox"
            v-model="form.notifications[notif.key]"
            class="h-4 w-4 rounded text-green-600 border-gray-300 focus:ring-green-500"
          />
          <span class="text-sm text-gray-700">{{ notif.label }}</span>
        </label>
      </div>
    </section>

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
          <label class="block text-sm font-medium text-gray-700 mb-1">From Address</label>
          <input v-model="form.smtp.from" type="email" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="noreply@smartbody.app" />
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

    <!-- Maintenance Mode -->
    <section class="bg-white rounded-lg shadow border border-gray-200 p-6 space-y-4">
      <div class="flex items-center justify-between">
        <div>
          <h2 class="text-lg font-semibold text-gray-900">Maintenance Mode</h2>
          <p class="text-sm text-gray-500">Enabling this will show a maintenance page to all non-admin users.</p>
        </div>
        <button
          @click="form.maintenanceMode = !form.maintenanceMode"
          :class="['relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none', form.maintenanceMode ? 'bg-red-500' : 'bg-gray-300']"
        >
          <span :class="['inline-block h-4 w-4 transform rounded-full bg-white transition-transform', form.maintenanceMode ? 'translate-x-6' : 'translate-x-1']"></span>
        </button>
      </div>
      <div v-if="form.maintenanceMode" class="p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700">
        Maintenance mode is <strong>enabled</strong>. Regular users will not be able to access the system.
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

    <!-- Info Box -->
    <div class="p-4 bg-amber-50 border border-amber-200 rounded-lg text-sm text-amber-800">
      <p><span class="font-semibold">Admin only:</span> These settings affect all users in the system. Changes take effect immediately unless stated otherwise. Always test changes in a staging environment if possible.</p>
    </div>

  </div>
</template>

<script setup>
import { ref } from 'vue'

const saved = ref(false)

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
    from: 'noreply@smartbody.app',
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
  saved.value = true
  setTimeout(() => { saved.value = false }, 3000)
}

function resetSettings() {
  form.value = JSON.parse(JSON.stringify(defaults))
  saved.value = false
}
</script>
