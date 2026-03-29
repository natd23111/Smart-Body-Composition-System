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
      <!-- Profile Avatar Section -->
      <div class="bg-white rounded-lg shadow border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
          <h3 class="text-lg font-semibold text-gray-900">Profile Picture</h3>
          <p class="text-sm text-gray-600 mt-1">Update your profile avatar</p>
        </div>
        <div class="px-6 py-4 flex items-center gap-6">
          <div class="w-24 h-24 rounded-full bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center flex-shrink-0">
            <svg class="h-12 w-12 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
          </div>
          <button class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors flex items-center gap-2">
            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path><circle cx="12" cy="13" r="4"></circle></svg>
            Upload New Picture
          </button>
        </div>
      </div>

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
              />
            </div>

            <!-- Email -->
            <div class="space-y-2">
              <label class="block text-sm font-medium text-gray-900">Email Address</label>
              <input
                v-model="personalInfo.email"
                type="email"
                placeholder="Enter your email"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
              />
            </div>

            <!-- Date of Birth -->
            <div class="space-y-2">
              <label class="block text-sm font-medium text-gray-900">Date of Birth</label>
              <input
                v-model="personalInfo.dateOfBirth"
                type="date"
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
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
              </select>
            </div>

            <!-- Phone -->
            <div class="space-y-2">
              <label class="block text-sm font-medium text-gray-900">Phone Number</label>
              <input
                v-model="personalInfo.phone"
                type="tel"
                placeholder="Enter your phone number"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
              />
            </div>

            <!-- Location -->
            <div class="space-y-2">
              <label class="block text-sm font-medium text-gray-900">Location</label>
              <input
                v-model="personalInfo.location"
                type="text"
                placeholder="Enter your location"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
              />
            </div>
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
                :type="showPassword ? 'text' : 'password'"
                placeholder="Enter current password"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
              />
              <button
                @click="showPassword = !showPassword"
                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700"
              >
                <svg v-if="!showPassword" class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                <svg v-else class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
              </button>
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="space-y-2">
              <label class="block text-sm font-medium text-gray-900">New Password</label>
              <input
                v-model="passwords.new"
                type="password"
                placeholder="Enter new password"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
              />
            </div>
            <div class="space-y-2">
              <label class="block text-sm font-medium text-gray-900">Confirm Password</label>
              <input
                v-model="passwords.confirm"
                type="password"
                placeholder="Confirm new password"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
              />
            </div>
          </div>
        </div>
      </div>

      <!-- Save Button -->
      <div class="flex justify-end">
        <button class="px-6 py-2 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors flex items-center gap-2">
          <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
          Save Changes
        </button>
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

      <!-- Language Settings -->
      <div class="bg-white rounded-lg shadow border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
          <h3 class="text-lg font-semibold text-gray-900">Language & Region</h3>
          <p class="text-sm text-gray-600 mt-1">Set your preferred language</p>
        </div>
        <div class="px-6 py-6 space-y-4">
          <div class="space-y-2">
            <label class="block text-sm font-medium text-gray-900">Language</label>
            <select
              v-model="systemPrefs.language"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
            >
              <option value="English">English</option>
              <option value="Malay">Malay (Bahasa Malaysia)</option>
              <option value="Chinese">Chinese (中文)</option>
              <option value="Spanish">Spanish</option>
            </select>
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
            <span class="font-medium text-gray-900">January 15, 2025</span>
          </div>
          <div class="flex justify-between items-center py-2 border-b border-gray-200">
            <span class="text-sm text-gray-600">Last Login</span>
            <span class="font-medium text-gray-900">Today at 10:30 AM</span>
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
          <h3 class="text-lg font-semibold text-red-600">Danger Zone</h3>
          <p class="text-sm text-gray-600 mt-1">Irreversible actions</p>
        </div>
        <div class="px-6 py-4 space-y-3">
          <button class="w-full px-4 py-2 border border-red-300 text-red-600 rounded-lg hover:bg-red-50 transition-colors font-medium">
            Delete Account
          </button>
          <p class="text-xs text-gray-600 text-center">
            Deleting your account is permanent and cannot be undone.
          </p>
        </div>
      </div>

      <!-- Save Button -->
      <div class="flex justify-end">
        <button class="px-6 py-2 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors flex items-center gap-2">
          <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
          Save Preferences
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';

const activeTab = ref('personal');
const showPassword = ref(false);

const personalInfo = ref({
  fullName: 'John Doe',
  email: 'john.doe@example.com',
  phone: '+60-1111333266',
  dateOfBirth: '1990-05-15',
  gender: 'Male',
  location: 'Kuching, Sarawak',
});

const systemPrefs = ref({
  weightUnit: 'kg',
  heightUnit: 'cm',
  notifications: true,
  emailAlerts: true,
  weeklyReport: true,
  goalReminders: true,
  language: 'English',
});

const passwords = ref({
  current: '',
  new: '',
  confirm: '',
});
</script>
