<template>
  <div class="space-y-6">
    <!-- Page Header -->
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-gray-900">Profile & Settings</h1>
      <p class="text-gray-600 mt-2">
        Manage your personal information and system preferences
      </p>
    </div>

    <!-- Tab Navigation -->
    <div class="flex gap-4 border-b border-gray-200">
      <button
        @click="activeTab = 'personal'"
        :class="[
          'px-4 py-2 font-medium border-b-2 transition-colors',
          activeTab === 'personal'
            ? 'border-green-600 text-green-600'
            : 'border-transparent text-gray-600 hover:text-green-600'
        ]"
      >
        <div class="flex items-center gap-2">
          <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
          Personal Information
        </div>
      </button>
      <button
        @click="activeTab = 'system'"
        :class="[
          'px-4 py-2 font-medium border-b-2 transition-colors',
          activeTab === 'system'
            ? 'border-green-600 text-green-600'
            : 'border-transparent text-gray-600 hover:text-green-600'
        ]"
      >
        <div class="flex items-center gap-2">
          <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
          System Preferences
        </div>
      </button>
    </div>

    <!-- Personal Information Tab -->
    <div v-show="activeTab === 'personal'" class="space-y-6">
      <!-- Alert Messages -->
      <Transition name="fade">
        <div v-if="successMessage" class="p-4 bg-green-50 border border-green-200 rounded-lg flex items-start gap-3">
          <svg class="h-5 w-5 text-green-600 flex-shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
            <polyline points="22 4 12 14.01 9 11.01"></polyline>
          </svg>
          <p class="text-green-800 text-sm">{{ successMessage }}</p>
        </div>
      </Transition>

      <Transition name="fade">
        <div v-if="errorMessage" class="p-4 bg-red-50 border border-red-200 rounded-lg flex items-start gap-3">
          <svg class="h-5 w-5 text-red-600 flex-shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"></circle>
            <line x1="12" y1="8" x2="12" y2="12"></line>
            <line x1="12" y1="16" x2="12.01" y2="16"></line>
          </svg>
          <p class="text-red-800 text-sm">{{ errorMessage }}</p>
        </div>
      </Transition>


      <!-- Basic Information -->
      <div class="bg-white rounded-lg shadow border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
          <h3 class="text-lg font-semibold text-gray-900">Basic Information</h3>
          <p class="text-sm text-gray-600 mt-1">Update your personal details</p>
        </div>
        <div class="px-6 py-6 space-y-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Full Name -->
            <div class="space-y-2">
              <label class="block text-sm font-medium text-gray-900">Full Name</label>
              <input
                v-model="personalInfo.fullName"
                type="text"
                placeholder="Enter your full name"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                :class="{ 'border-red-500 bg-red-50': personalErrors.fullName }"
              />
              <p v-if="personalErrors.fullName" class="text-xs text-red-600">{{ personalErrors.fullName }}</p>
            </div>

            <!-- Email -->
            <div class="space-y-2">
              <label class="block text-sm font-medium text-gray-900">Email Address</label>
              <input
                v-model="personalInfo.email"
                type="email"
                placeholder="Enter your email"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                :class="{ 'border-red-500 bg-red-50': personalErrors.email }"
              />
              <p v-if="personalErrors.email" class="text-xs text-red-600">{{ personalErrors.email }}</p>
            </div>

            <!-- Age -->
            <div class="space-y-2">
              <label class="block text-sm font-medium text-gray-900">Age</label>
              <input
                v-model.number="personalInfo.age"
                type="number"
                min="1"
                max="150"
                placeholder="Enter your age"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
              />
            </div>

            <!-- Gender -->
            <div class="space-y-2">
              <label class="block text-sm font-medium text-gray-900">Gender</label>
              <select
                v-model="personalInfo.gender"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
              >
                <option value="">Select Gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
              </select>
            </div>

            <!-- Height -->
            <div class="space-y-2">
              <label class="block text-sm font-medium text-gray-900">Height ({{ unitStore.heightLabel }})</label>
              <input
                v-model.number="personalInfo.height_cm"
                type="number"
                min="50"
                max="250"
                step="1"
                placeholder="Enter your height in centimeters"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
              />
            </div>
          </div>
                <!-- Save Button -->
      <div class="flex justify-end gap-4">
        <button
          @click="savePersonalInfo"
          :disabled="savingPersonal"
          class="px-6 py-2 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 disabled:bg-green-400 transition-colors flex items-center gap-2"
        >
          <svg v-if="savingPersonal" class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          {{ savingPersonal ? 'Saving...' : 'Save Changes' }}
        </button>
      </div>
        </div>
      </div>

      <!-- Password Section -->
      <div class="bg-yellow-50 rounded-lg shadow border border-yellow-200">
        <div class="px-6 py-4 border-b border-yellow-200">
          <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
            <svg class="h-5 w-5 text-yellow-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="15" rx="2" ry="2"></rect><path d="M16 4V2a1 1 0 0 0-1-1h-2a1 1 0 0 0-1 1v2"></path></svg>
            Change Password
          </h3>
          <p class="text-sm text-gray-600 mt-1">Update your account password</p>
        </div>
        <div class="px-6 py-6 space-y-4">
          <div class="space-y-2">
            <label class="block text-sm font-medium text-gray-900">Current Password</label>
            <div class="relative">
              <input
                v-model="passwords.current"
                type="password"
                placeholder="Enter current password"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                :class="{ 'border-red-500 bg-red-50': passwordErrors.current }"
              />
            </div>
            <p v-if="passwordErrors.current" class="text-xs text-red-600">{{ passwordErrors.current }}</p>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="space-y-2">
              <label class="block text-sm font-medium text-gray-900">New Password</label>
              <input
                v-model="passwords.new"
                type="password"
                placeholder="Enter new password"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                :class="{ 'border-red-500 bg-red-50': passwordErrors.new }"
              />
              <p v-if="passwordErrors.new" class="text-xs text-red-600">{{ passwordErrors.new }}</p>
            </div>
            <div class="space-y-2">
              <label class="block text-sm font-medium text-gray-900">Confirm Password</label>
              <input
                v-model="passwords.confirm"
                type="password"
                placeholder="Confirm new password"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                :class="{ 'border-red-500 bg-red-50': passwordErrors.confirm }"
              />
              <p v-if="passwordErrors.confirm" class="text-xs text-red-600">{{ passwordErrors.confirm }}</p>
            </div>
          </div>
                <!-- Password Save Button -->
      <div class="flex justify-end gap-4">
        <button
          @click="passwords.current = ''; passwords.new = ''; passwords.confirm = ''; passwordErrors = {}"
          type="button"
          class="px-6 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors"
        >
          Clear
        </button>
        <button
          @click="savePasswordChange"
          :disabled="savingPassword"
          class="px-6 py-2 bg-yellow-600 text-white font-medium rounded-lg hover:bg-yellow-700 disabled:bg-yellow-400 transition-colors flex items-center gap-2"
        >
          <svg v-if="savingPassword" class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          {{ savingPassword ? 'Saving...' : 'Change Password' }}
        </button>
      </div>
        </div>
      </div>

    </div>

    <!-- System Preferences Tab -->
    <div v-show="activeTab === 'system'" class="space-y-6">
      <!-- Measurement Units -->
      <div class="bg-white rounded-lg shadow border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
          <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
            <svg class="h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
            Measurement Units
          </h3>
          <p class="text-sm text-gray-600 mt-1">Choose your preferred measurement system</p>
        </div>
        <div class="px-6 py-6 space-y-4">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Weight Unit -->
            <div class="space-y-2">
              <label class="block text-sm font-medium text-gray-900">Weight Unit</label>
              <select
                v-model="systemPrefs.weightUnit"
                @change="unitStore.setUnits(systemPrefs.weightUnit, systemPrefs.heightUnit)"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
              >
                <option value="kg">Kilograms (kg)</option>
                <option value="lb">Pounds (lb)</option>
              </select>
              <p class="text-xs text-gray-600 mt-1">Used for weight tracking and goals</p>
            </div>

            <!-- Height Unit -->
            <div class="space-y-2">
              <label class="block text-sm font-medium text-gray-900">Height Unit</label>
              <select
                v-model="systemPrefs.heightUnit"
                @change="unitStore.setUnits(systemPrefs.weightUnit, systemPrefs.heightUnit)"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
              >
                <option value="cm">Centimeters (cm)</option>
                <option value="in">Inches (in)</option>
              </select>
              <p class="text-xs text-gray-600 mt-1">Used for BMI calculations</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Notification Settings -->
      <div class="bg-blue-50 rounded-lg shadow border border-blue-200">
        <div class="px-6 py-4 border-b border-blue-200">
          <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
            <svg class="h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
            Notification Settings
          </h3>
          <p class="text-sm text-gray-600 mt-1">Manage how you receive updates</p>
        </div>
        <div class="px-6 py-4 space-y-3">
          <!-- Push Notifications -->
          <div class="flex items-center justify-between p-4 bg-white rounded-lg border border-blue-100">
            <div>
              <p class="font-medium text-sm text-gray-900">Push Notifications</p>
              <p class="text-xs text-gray-600">Receive push notifications on your device</p>
            </div>
            <label class="flex items-center cursor-pointer">
              <input
                v-model="systemPrefs.notifications"
                type="checkbox"
                class="w-4 h-4"
              />
            </label>
          </div>

          <!-- Email Alerts -->
          <div class="flex items-center justify-between p-4 bg-white rounded-lg border border-blue-100">
            <div>
              <p class="font-medium text-sm text-gray-900">Email Alerts</p>
              <p class="text-xs text-gray-600">Receive important updates via email</p>
            </div>
            <label class="flex items-center cursor-pointer">
              <input
                v-model="systemPrefs.emailAlerts"
                type="checkbox"
                class="w-4 h-4"
              />
            </label>
          </div>

          <!-- Weekly Report -->
          <div class="flex items-center justify-between p-4 bg-white rounded-lg border border-blue-100">
            <div>
              <p class="font-medium text-sm text-gray-900">Weekly Reports</p>
              <p class="text-xs text-gray-600">Get weekly health summary reports</p>
            </div>
            <label class="flex items-center cursor-pointer">
              <input
                v-model="systemPrefs.weeklyReport"
                type="checkbox"
                class="w-4 h-4"
              />
            </label>
          </div>

          <!-- Goal Reminders -->
          <div class="flex items-center justify-between p-4 bg-white rounded-lg border border-blue-100">
            <div>
              <p class="font-medium text-sm text-gray-900">Goal Reminders</p>
              <p class="text-xs text-gray-600">Receive reminders to log measurements</p>
            </div>
            <label class="flex items-center cursor-pointer">
              <input
                v-model="systemPrefs.goalReminders"
                type="checkbox"
                class="w-4 h-4"
              />
            </label>
          </div>
        </div>
      </div>

      <!-- Account Information -->
      <div class="bg-white rounded-lg shadow border border-gray-300">
        <div class="px-6 py-4 border-b border-gray-200">
          <h3 class="text-lg font-semibold text-gray-900">Account Information</h3>
          <p class="text-sm text-gray-600 mt-1">Your account details</p>
        </div>
        <div class="px-6 py-4 space-y-3">
          <div class="flex justify-between items-center py-2 border-b border-gray-200">
            <span class="text-sm text-gray-600">Member Since</span>
            <span class="font-medium text-gray-900">{{ memberSince }}</span>

          </div>
          <div class="flex justify-between items-center py-2">
            <span class="text-sm text-gray-600">Account Status</span>
            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-medium">Active</span>
          </div>
        </div>
      </div>

      <!-- Danger Zone -->
      <div class="bg-red-50 rounded-lg shadow border border-red-200">
        <div class="px-6 py-4 border-b border-red-200">
          <h3 class="text-lg font-semibold text-red-600">Delete Account</h3>
          <p class="text-sm text-gray-600 mt-1">Irreversible actions</p>
        </div>
        <div class="px-6 py-4 space-y-3">
          <button
            @click="openDeleteModal"
            class="w-full px-4 py-2 border border-red-300 text-red-600 rounded-lg hover:bg-red-100 transition-colors font-medium"
          >
            Delete Account
          </button>
          <p class="text-xs text-gray-600 text-center">
            Deleting your account is permanent and cannot be undone.
          </p>
        </div>
      </div>

      <!-- Delete Account Confirmation Modal -->
      <div v-if="showDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md mx-4 p-6">
          <h3 class="text-lg font-bold text-red-600 mb-2">Delete Account</h3>
          <p class="text-sm text-gray-600 mb-4">
            This action is <strong>permanent</strong> and cannot be undone. All your data will be deleted. Enter your password to confirm.
          </p>
          <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
          <input
            v-model="deletePassword"
            type="password"
            placeholder="Enter your password"
            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-400 mb-2"
            @keydown.enter="confirmDeleteAccount"
          />
          <p v-if="deleteError" class="text-sm text-red-600 mb-3">{{ deleteError }}</p>
          <div class="flex gap-3 mt-4">
            <button
              @click="closeDeleteModal"
              class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors"
              :disabled="deletingAccount"
            >
              Cancel
            </button>
            <button
              @click="confirmDeleteAccount"
              class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium disabled:opacity-50"
              :disabled="deletingAccount"
            >
              {{ deletingAccount ? 'Deleting...' : 'Delete My Account' }}
            </button>
          </div>
        </div>
      </div>


    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/authPiniaStore'
import { useUnitStore } from '@/stores/unitStore'
import { getUserProfile, updateUserProfile, changePassword, deleteAccount } from '@/services/authService'

const authStore = useAuthStore()
const unitStore = useUnitStore()
const router = useRouter()

const activeTab = ref('personal')
const showPassword = ref(false)
const savingPersonal = ref(false)
const savingPassword = ref(false)
const showDeleteModal = ref(false)
const deletingAccount = ref(false)
const deletePassword = ref('')
const deleteError = ref('')
const loadingProfile = ref(true)

const successMessage = ref('')
const errorMessage = ref('')

const personalInfo = ref({
  fullName: '',
  email: '',
  age: '',
  gender: '',
  height_cm: '',
})

const memberSince = ref('-')

const personalErrors = ref({})

const passwords = ref({
  current: '',
  new: '',
  confirm: '',
})

const passwordErrors = ref({})

const systemPrefs = ref({
  weightUnit: 'kg',
  heightUnit: 'cm',
  notifications: true,
  emailAlerts: true,
  weeklyReport: true,
  goalReminders: true,
  language: 'English',
})

// Load user profile on mount
onMounted(async () => {
  // Sync unit preferences from store
  systemPrefs.value.weightUnit = unitStore.weightUnit
  systemPrefs.value.heightUnit = unitStore.heightUnit
  try {
    const profile = await getUserProfile()
    personalInfo.value = {
      fullName: profile.name || '',
      email: profile.email || '',
      age: profile.age || '',
      gender: profile.gender || '',
      height_cm: profile.height_cm ? unitStore.convertHeight(profile.height_cm) : '',
    }
    if (profile.created_at) {
      memberSince.value = new Date(profile.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })
    }
  } catch (error) {
    console.error('Failed to load profile:', error)
    errorMessage.value = 'Failed to load your profile'
  } finally {
    loadingProfile.value = false
  }
})

// Reset form to original values
const resetForm = () => {
  personalInfo.value = {
    fullName: '',
    email: '',
    age: '',
    gender: '',
  }
  passwords.value = {
    current: '',
    new: '',
    confirm: '',
  }
  personalErrors.value = {}
  passwordErrors.value = {}
  successMessage.value = ''
  errorMessage.value = ''
}

// Validate and save personal info
const savePersonalInfo = async () => {
  personalErrors.value = {}
  errorMessage.value = ''
  successMessage.value = ''

  // Validation
  if (!personalInfo.value.fullName.trim()) {
    personalErrors.value.fullName = 'Full name is required'
  } else if (personalInfo.value.fullName.trim().length < 2) {
    personalErrors.value.fullName = 'Full name must be at least 2 characters'
  }

  if (!personalInfo.value.email) {
    personalErrors.value.email = 'Email is required'
  } else if (!isValidEmail(personalInfo.value.email)) {
    personalErrors.value.email = 'Please enter a valid email'
  }

  if (Object.keys(personalErrors.value).length > 0) {
    return
  }

  savingPersonal.value = true

  try {
    const updated = await updateUserProfile({
      fullName: personalInfo.value.fullName.trim(),
      email: personalInfo.value.email.trim().toLowerCase(),
      age: personalInfo.value.age,
      gender: personalInfo.value.gender,
      height_cm: unitStore.toCm(personalInfo.value.height_cm),
    })

    // Update auth store and localStorage with full user object
    authStore.user = updated
    localStorage.setItem('user', JSON.stringify(updated))

    successMessage.value = 'Profile updated successfully!'
    setTimeout(() => {
      successMessage.value = ''
    }, 3000)
  } catch (error) {
    errorMessage.value = error.message || 'Failed to update profile. Please try again.'
  } finally {
    savingPersonal.value = false
  }
}

// Validate and change password
const savePasswordChange = async () => {
  passwordErrors.value = {}
  errorMessage.value = ''
  successMessage.value = ''

  // Validation
  if (!passwords.value.current) {
    passwordErrors.value.current = 'Current password is required'
  }

  if (!passwords.value.new) {
    passwordErrors.value.new = 'New password is required'
  } else if (passwords.value.new.length < 8) {
    passwordErrors.value.new = 'Password must be at least 8 characters'
  }

  if (!passwords.value.confirm) {
    passwordErrors.value.confirm = 'Please confirm your password'
  } else if (passwords.value.new !== passwords.value.confirm) {
    passwordErrors.value.confirm = 'Passwords do not match'
  }

  if (Object.keys(passwordErrors.value).length > 0) {
    return
  }

  savingPassword.value = true

  try {
    await changePassword(passwords.value)
    successMessage.value = 'Password changed successfully!'
    passwords.value = {
      current: '',
      new: '',
      confirm: '',
    }
    setTimeout(() => {
      successMessage.value = ''
    }, 3000)
  } catch (error) {
    errorMessage.value = error.message || 'Failed to change password. Please try again.'
  } finally {
    savingPassword.value = false
  }
}

// Validate email format
const isValidEmail = (email) => {
  const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
  return re.test(email)
}

const openDeleteModal = () => {
  deletePassword.value = ''
  deleteError.value = ''
  showDeleteModal.value = true
}

const closeDeleteModal = () => {
  showDeleteModal.value = false
  deletePassword.value = ''
  deleteError.value = ''
}

const confirmDeleteAccount = async () => {
  if (!deletePassword.value) {
    deleteError.value = 'Please enter your password to confirm.'
    return
  }
  deletingAccount.value = true
  deleteError.value = ''
  try {
    await deleteAccount(deletePassword.value)
    authStore.user = null
    authStore.isAuthenticated = false
    router.push('/login')
  } catch (error) {
    deleteError.value = error.message || 'Failed to delete account. Please try again.'
  } finally {
    deletingAccount.value = false
  }
}
</script>
