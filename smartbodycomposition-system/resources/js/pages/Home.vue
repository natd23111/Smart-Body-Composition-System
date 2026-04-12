<template>
  <div class="space-y-6">

    <!-- Welcome Banner -->
    <div class="bg-gradient-to-r from-green-500 via-emerald-500 to-teal-500 rounded-xl p-8 text-white shadow-lg">
      <div class="max-w-3xl">
        <h1 class="text-3xl font-bold mb-1">Welcome back, {{ firstName }}!</h1>
        <p class="text-green-100 text-base mb-6">
          {{ welcomeMessage }}
        </p>
        <div class="flex flex-col sm:flex-row gap-3">
          <router-link
            to="/body-composition"
            class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-white text-green-700 font-semibold rounded-lg hover:bg-green-50 transition-colors"
          >
            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            Log Measurements
          </router-link>
          <router-link
            to="/dashboard"
            class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-white/20 border border-white/40 text-white font-semibold rounded-lg hover:bg-white/30 transition-colors"
          >
            View Dashboard
          </router-link>
        </div>
      </div>
    </div>

    <!-- Recent Activity & Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

      <!-- Recent Activity -->
      <div id="recent-activity" class="lg:col-span-2 bg-white rounded-lg shadow border border-gray-200 scroll-mt-24">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between gap-4">
          <div class="flex items-center gap-2">
            <svg class="h-5 w-5 text-green-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
            <div>
              <h3 class="font-semibold text-gray-900">Recent Activity</h3>
              <p class="text-xs text-gray-500">
                <span v-if="loadingActivity">Loading your updates...</span>
                <span v-else-if="recentActivity.length === 0">No activity recorded yet</span>
                <span v-else>
                  {{ recentActivity.length }} recent update{{ recentActivity.length !== 1 ? 's' : '' }}
                  <span v-if="visibleUnreadActivityCount > 0"> · {{ visibleUnreadActivityCount }} new</span>
                </span>
              </p>
            </div>
          </div>
          <button
            v-if="!loadingActivity && totalUnreadActivityCount > 0"
            @click="markAllActivitySeen"
            :disabled="markingActivitySeen"
            class="px-3 py-1.5 border border-green-600 text-green-700 rounded-lg text-xs font-medium hover:bg-green-50 transition-colors disabled:opacity-50"
          >
            {{ markingActivitySeen ? 'Updating...' : 'Mark All as Seen' }}
          </button>
        </div>
        <div class="px-6 py-4">
          <!-- Loading -->
          <div v-if="loadingActivity" class="space-y-4">
            <div v-for="n in 3" :key="n" class="flex items-start gap-4 pb-4 border-b last:border-b-0 last:pb-0 animate-pulse">
              <div class="w-9 h-9 bg-gray-200 rounded-lg flex-shrink-0"></div>
              <div class="flex-1 space-y-2">
                <div class="h-3 bg-gray-200 rounded w-1/3"></div>
                <div class="h-3 bg-gray-200 rounded w-2/3"></div>
                <div class="h-2 bg-gray-100 rounded w-1/4 mt-1"></div>
              </div>
            </div>
          </div>

          <!-- No data -->
          <div v-else-if="recentActivity.length === 0" class="py-8 text-center">
            <svg class="h-10 w-10 text-gray-300 mx-auto mb-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>
            <p class="text-gray-500 text-sm">No activity yet. Start by logging your first measurement!</p>
            <router-link to="/body-composition" class="mt-3 inline-block text-sm text-green-600 hover:text-green-700 font-medium">Log a measurement &rarr;</router-link>
          </div>

          <!-- Activity list -->
          <div v-else class="space-y-4">
            <div
              v-for="activity in recentActivity"
              :key="activity.id"
              :class="activity.isNew ? 'bg-green-50/40 rounded-lg px-3 py-3 -mx-3' : ''"
              class="flex items-start gap-4 pb-4 border-b border-gray-100 last:border-b-0 last:pb-0"
            >
              <div :class="['p-2 rounded-lg flex-shrink-0', activity.iconBg]">
                <svg :class="['h-5 w-5', activity.iconColor]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" v-html="activity.iconPath"></svg>
              </div>
              <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 flex-wrap">
                  <p class="font-medium text-sm text-gray-900">{{ activity.title }}</p>
                  <span v-if="activity.isNew" class="px-2 py-0.5 rounded-full text-[11px] font-semibold bg-green-100 text-green-700">New</span>
                </div>
                <p class="text-sm text-gray-500 truncate">{{ activity.description }}</p>
                <div class="flex items-center gap-3 mt-0.5 flex-wrap">
                  <p class="text-xs text-gray-400">{{ activity.time }}</p>
                  <router-link v-if="activity.actionUrl" :to="activity.actionUrl" class="text-xs font-medium text-green-600 hover:text-green-700">
                    Open
                  </router-link>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Quick Actions -->
      <div class="bg-white rounded-lg shadow border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
          <h3 class="font-semibold text-gray-900">Quick Actions</h3>
          <p class="text-xs text-gray-500">Access key features</p>
        </div>
        <div class="px-6 py-4 space-y-2">
          <router-link
            v-for="action in quickActions"
            :key="action.path"
            :to="action.path"
            class="w-full flex items-center gap-3 px-4 py-3 border border-gray-200 rounded-lg text-sm font-medium text-gray-700 hover:bg-green-50 hover:border-green-200 hover:text-green-700 transition-colors"
          >
            <svg class="h-4 w-4 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" v-html="action.iconPath"></svg>
            {{ action.label }}
          </router-link>
        </div>
      </div>
    </div>

    <!-- Your Goals -->
    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg border border-blue-100 shadow">
      <div class="px-6 py-4 border-b border-blue-100 flex items-center justify-between">
        <div class="flex items-center gap-2">
          <svg class="h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><circle cx="12" cy="12" r="6"></circle><circle cx="12" cy="12" r="2"></circle></svg>
          <div>
            <h3 class="font-semibold text-gray-900">Your Goals</h3>
            <p class="text-xs text-gray-500">Track your progress toward health objectives</p>
          </div>
        </div>
        <div class="flex items-center gap-3">
          <router-link to="/goals" class="text-sm text-blue-600 hover:text-blue-700 font-medium">View all &rarr;</router-link>
        </div>
      </div>

      <!-- Loading skeleton -->
      <div v-if="loadingGoals" class="px-6 py-4 space-y-1">
        <div v-for="n in 3" :key="n" class="animate-pulse py-3 border-b border-blue-100 last:border-b-0 space-y-2">
          <div class="flex justify-between">
            <div class="h-3 bg-blue-200 rounded w-1/3"></div>
            <div class="h-3 bg-blue-200 rounded w-1/5"></div>
          </div>
          <div class="h-2 bg-blue-200 rounded-full w-full"></div>
          <div class="h-2 bg-blue-100 rounded w-1/4"></div>
        </div>
      </div>

      <!-- Empty -->
      <div v-else-if="activeGoals.length === 0" class="px-6 py-10 text-center">
        <svg class="h-10 w-10 text-blue-300 mx-auto mb-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><circle cx="12" cy="12" r="6"></circle><circle cx="12" cy="12" r="2"></circle></svg>
        <p class="text-gray-500 text-sm mb-3">No active goals yet. Set your first health goal!</p>
        <button
          @click="openGoalModal"
          class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 text-white text-xs font-semibold rounded-lg hover:bg-blue-700 transition-colors"
        >
          <svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
          Add your first goal
        </button>
      </div>

      <!-- Goals list -->
      <div v-else class="px-6 py-4 divide-y divide-blue-100">
        <div
          v-for="goal in activeGoals.slice(0, 5)"
          :key="goal.id"
          class="py-3 first:pt-0 last:pb-0"
        >
          <div class="flex items-center justify-between mb-1.5">
            <span class="text-sm font-medium text-gray-800">
              Target {{ goal.metric_label }}: {{ goal.target_value }}{{ goal.metric_unit ? ' ' + goal.metric_unit : '' }}
            </span>
            <span class="text-sm font-semibold text-blue-600">
              {{ goal.progress !== null ? goal.progress + '% Complete' : '—' }}
            </span>
          </div>
          <div class="w-full bg-blue-100 rounded-full h-2 mb-1">
            <div
              :style="{ width: (goal.progress !== null ? Math.min(goal.progress, 100) : 0) + '%' }"
              class="bg-blue-500 h-2 rounded-full transition-all duration-500"
            ></div>
          </div>
          <p class="text-xs text-gray-400">{{ goalHintText(goal) }}</p>
        </div>
      </div>
    </div>

    <!-- Health Tips -->
    <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-lg border border-green-100 shadow">
      <div class="px-6 py-4 border-b border-green-100 flex items-center justify-between">
        <div class="flex items-center gap-2">
          <svg class="h-5 w-5 text-green-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
          <div>
            <h3 class="font-semibold text-gray-900">Health Recommendations</h3>
            <p class="text-xs text-gray-500">
              <span v-if="loadingTips">Loading recommendations...</span>
              <span v-else-if="tipsFromBackend.length > 0">Based on your latest measurements</span>
              <span v-else>General wellness tips &mdash; log a measurement for personalised guidance</span>
            </p>
          </div>
        </div>
        <router-link v-if="tipsFromBackend.length > 0" to="/recommendations" class="text-sm text-green-600 hover:text-green-700 font-medium">View all &rarr;</router-link>
      </div>

      <!-- Loading skeleton -->
      <div v-if="loadingTips" class="px-6 py-4 grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div v-for="n in 3" :key="n" class="flex items-start gap-3 p-4 bg-white rounded-lg border border-green-100 animate-pulse">
          <div class="w-5 h-5 bg-gray-200 rounded flex-shrink-0 mt-0.5"></div>
          <div class="flex-1 space-y-2">
            <div class="h-3 bg-gray-200 rounded w-2/3"></div>
            <div class="h-3 bg-gray-100 rounded w-full"></div>
            <div class="h-3 bg-gray-100 rounded w-4/5"></div>
          </div>
        </div>
      </div>

      <!-- Tips list -->
      <div v-else class="px-6 py-4 grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div
          v-for="tip in displayedTips"
          :key="tip.title"
          class="flex items-start gap-3 p-4 bg-white rounded-lg border border-green-100"
        >
          <svg class="h-5 w-5 text-green-600 flex-shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" v-html="ICON_PATHS[tip.icon] ?? ICON_PATHS.heart"></svg>
          <div>
            <p class="font-medium text-sm text-gray-900">{{ tip.title }}</p>
            <p class="text-xs text-gray-500 mt-0.5">{{ tip.description }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Add Goal Modal -->
    <Teleport to="body">
      <div v-if="showGoalModal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="showGoalModal = false"></div>
        <div class="relative w-full max-w-md bg-white rounded-xl shadow-xl">
          <!-- Header -->
          <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Add a New Goal</h3>
            <button @click="showGoalModal = false" class="text-gray-400 hover:text-gray-600 transition-colors">
              <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
          </div>
          <!-- Body -->
          <div class="px-6 py-5 space-y-4">
            <p v-if="goalFormErrors.general" class="text-sm text-red-600 bg-red-50 border border-red-200 rounded-lg px-3 py-2">{{ goalFormErrors.general }}</p>
            <!-- Metric -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Health Metric</label>
              <select v-model="goalForm.metric" :class="['w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500', goalFormErrors.metric ? 'border-red-400' : 'border-gray-300']">
                <option value="" disabled>Select a metric</option>
                <option v-for="m in GOAL_METRICS" :key="m.value" :value="m.value">{{ m.label }}{{ m.unit ? ' (' + m.unit + ')' : '' }}</option>
              </select>
              <p v-if="goalFormErrors.metric" class="text-xs text-red-500 mt-1">{{ goalFormErrors.metric }}</p>
            </div>
            <!-- Target Value -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">
                Target Value<span v-if="selectedGoalUnit" class="text-gray-400 font-normal"> ({{ selectedGoalUnit }})</span>
              </label>
              <input
                v-model="goalForm.target_value"
                type="number"
                step="0.1"
                :class="['w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500', goalFormErrors.target_value ? 'border-red-400' : 'border-gray-300']"
              />
              <p v-if="goalFormErrors.target_value" class="text-xs text-red-500 mt-1">{{ goalFormErrors.target_value }}</p>
            </div>
            <!-- Deadline (optional) -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Target Date <span class="text-gray-400 font-normal">(optional)</span></label>
              <input
                v-model="goalForm.deadline"
                type="date"
                :min="tomorrowDate"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>
            <!-- Notes (optional) -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Notes <span class="text-gray-400 font-normal">(optional)</span></label>
              <textarea
                v-model="goalForm.notes"
                rows="2"
                placeholder="e.g. Goal for my birthday..."
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
              ></textarea>
            </div>
          </div>
          <!-- Footer -->
          <div class="flex justify-end gap-3 px-6 py-4 border-t border-gray-200">
            <button @click="showGoalModal = false" class="px-4 py-2 text-sm font-medium text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">Cancel</button>
            <button
              @click="saveGoal"
              :disabled="savingGoal"
              class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 disabled:opacity-50 transition-colors"
            >
              {{ savingGoal ? 'Saving...' : 'Save Goal' }}
            </button>
          </div>
        </div>
      </div>
    </Teleport>

  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useAuthStore } from '@/stores/authPiniaStore'
import { useUnitStore } from '@/stores/unitStore'
import { activityService, bodyCompositionService, healthRecommendationService, goalService } from '@/services/api'

const authStore = useAuthStore()
const unitStore = useUnitStore()
const MAX_ACTIVITY_ITEMS = 4

const loadingActivity = ref(true)
const loadingTips = ref(true)
const loadingGoals = ref(true)
const markingActivitySeen = ref(false)
const recentRecords = ref([])
const recentNotifications = ref([])
const tipsFromBackend = ref([])
const goalsData = ref([])
const totalUnreadActivityCount = ref(0)

// ─── Computed ──────────────────────────────────────────────────────────────

const firstName = computed(() => {
  const name = authStore.user?.name || ''
  return name.split(' ')[0] || 'there'
})

const welcomeMessage = computed(() => {
  const hour = new Date().getHours()
  if (hour < 12) return 'Good morning! Ready to track your health today?'
  if (hour < 17) return 'Good afternoon! Keep up the great work on your health journey.'
  return 'Good evening! Don\'t forget to log today\'s measurements.'
})

// Records sorted newest measurement_date first (backend sorts by created_at, not measurement_date)
const sortedRecords = computed(() =>
  [...recentRecords.value].sort((a, b) =>
    parseLocalDate(b.measurement_date || b.created_at) - parseLocalDate(a.measurement_date || a.created_at)
  )
)

const activeGoals = computed(() => goalsData.value.filter(g => g.status === 'active'))

// ─── Goal Management (inline) ─────────────────────────────────────────────

const GOAL_METRICS = [
  { value: 'weight_kg',          label: 'Weight',       unit: 'kg'    },
  { value: 'body_fat_percent',   label: 'Body Fat',     unit: '%'     },
  { value: 'muscle_mass',        label: 'Muscle Mass',  unit: 'kg'    },
  { value: 'bmi',                label: 'BMI',          unit: 'kg/m²' },
  { value: 'visceral_fat',       label: 'Visceral Fat', unit: ''      },
  { value: 'body_water_percent', label: 'Body Water',   unit: '%'     },
]

const showGoalModal  = ref(false)
const goalForm       = ref({ metric: '', target_value: '', deadline: '', notes: '' })
const goalFormErrors = ref({})
const savingGoal     = ref(false)

const selectedGoalUnit = computed(() =>
  GOAL_METRICS.find(m => m.value === goalForm.value.metric)?.unit ?? ''
)

const tomorrowDate = computed(() => {
  const d = new Date()
  d.setDate(d.getDate() + 1)
  return d.toISOString().split('T')[0]
})

function openGoalModal() {
  goalForm.value       = { metric: '', target_value: '', deadline: '', notes: '' }
  goalFormErrors.value = {}
  showGoalModal.value  = true
}

async function saveGoal() {
  goalFormErrors.value = {}
  const errors = {}
  if (!goalForm.value.metric) errors.metric = 'Select a metric'
  if (!goalForm.value.target_value || isNaN(Number(goalForm.value.target_value))) errors.target_value = 'Enter a valid target'
  if (Object.keys(errors).length) { goalFormErrors.value = errors; return }

  savingGoal.value = true
  try {
    const payload = {
      metric:       goalForm.value.metric,
      target_value: Number(goalForm.value.target_value),
      deadline:     goalForm.value.deadline || null,
      notes:        goalForm.value.notes    || null,
    }
    const res = await goalService.create(payload)
    goalsData.value.unshift(res.data.data ?? res.data)
    showGoalModal.value = false
  } catch {
    goalFormErrors.value.general = 'Failed to save goal. Please try again.'
  } finally {
    savingGoal.value = false
  }
}

function goalHintText(goal) {
  if (goal.progress !== null && goal.progress >= 100) return 'Already achieved!'
  if (goal.current_value === null || goal.current_value === undefined) return 'Log a measurement to track progress'
  const gap = Math.abs(Number(goal.current_value) - Number(goal.target_value)).toFixed(1)
  return `${gap}${goal.metric_unit ? ' ' + goal.metric_unit : ''} to go`
}

const recentActivity = computed(() => {
  const measurementItems = sortedRecords.value.slice(0, MAX_ACTIVITY_ITEMS).map(r => ({
      id:          `rec-${r.id}`,
      iconBg:      'bg-green-100',
      iconColor:   'text-green-600',
      iconPath:    '<polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>',
      title:       'Measurement Recorded',
      description: r.weight_kg
        ? `Weight: ${unitStore.convertWeight(r.weight_kg).toFixed(1)} ${unitStore.weightLabel}${r.body_fat_percent != null ? ` · Body fat: ${r.body_fat_percent.toFixed(1)}%` : ''}${r.muscle_mass != null ? ` · Muscle: ${unitStore.convertWeight(r.muscle_mass).toFixed(1)} ${unitStore.weightLabel}` : ''}`
        : 'Body composition logged',
      time: formatRelativeTime(r.measurement_date || r.created_at),
      timestamp:    parseLocalDate(r.measurement_date || r.created_at),
      isNew:        false,
      actionUrl:    '/body-composition',
    }))

  const notificationItems = [...recentNotifications.value]
    .sort((a, b) => parseLocalDate(b.created_at) - parseLocalDate(a.created_at))
    .slice(0, MAX_ACTIVITY_ITEMS)
    .map(notification => {
    const icon = notificationKindIcon(notification.kind)

    return {
      id: `notif-${notification.id}`,
      iconBg: icon.iconBg,
      iconColor: icon.iconColor,
      iconPath: icon.iconPath,
      title: notification.title,
      description: notification.message,
      time: formatRelativeTime(notification.created_at),
      timestamp: parseLocalDate(notification.created_at),
      isNew: !notification.is_read,
      actionUrl: notification.action_url,
    }
  })

  const selected = []

  const addIfMissing = (item) => {
    if (!item || selected.some(existing => existing.id === item.id)) {
      return
    }

    selected.push(item)
  }

  addIfMissing(notificationItems.find(item => item.isNew) ?? notificationItems[0])
  addIfMissing(measurementItems[0])

  const remainingItems = [...notificationItems, ...measurementItems]
    .filter(item => !selected.some(existing => existing.id === item.id))
    .sort((a, b) => {
      if (a.isNew !== b.isNew) {
        return a.isNew ? -1 : 1
      }

      return b.timestamp - a.timestamp
    })

  for (const item of remainingItems) {
    if (selected.length >= MAX_ACTIVITY_ITEMS) {
      break
    }

    addIfMissing(item)
  }

  return selected
    .sort((a, b) => b.timestamp - a.timestamp)
    .slice(0, MAX_ACTIVITY_ITEMS)
})

const visibleUnreadActivityCount = computed(() =>
  recentActivity.value.filter(activity => activity.isNew).length
)

const ICON_PATHS = {
  heart:       '<path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>',
  droplet:     '<path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z"></path>',
  apple:       '<path d="M17 8C8 10 5.9 16.17 3.82 19.11a1 1 0 0 0 1.71 1.05l.12-.18C7 17 9 15 12 15c3 0 5 2 7 5l.17.28a1 1 0 0 0 1.71-1.05C18.83 16.17 17 11 17 8z"></path><path d="M12 2a2 2 0 0 1 2 2"></path>',
  activity:    '<polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>',
  'trending-up': '<polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline>',
}

const STATIC_TIPS = [
  { title: 'Stay Hydrated',    description: 'Drink 8 glasses of water daily for optimal body function and metabolism.', icon: 'droplet' },
  { title: 'Regular Exercise', description: '30 minutes of moderate activity keeps muscles strong and burns fat.',        icon: 'activity' },
  { title: 'Quality Sleep',    description: 'Get 7–9 hours nightly for recovery, hormone balance, and wellness.',          icon: 'heart' },
]

const displayedTips = computed(() =>
  tipsFromBackend.value.length > 0
    ? tipsFromBackend.value.slice(0, 3).map(r => ({ title: r.title, description: r.summary, icon: r.icon ?? 'heart' }))
    : STATIC_TIPS
)

const quickActions = [
  {
    label: 'Full Dashboard',
    path: '/dashboard',
    iconPath: '<rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect>',
  },
  {
    label: 'Log Measurements',
    path: '/body-composition',
    iconPath: '<line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line>',
  },
  {
    label: 'Health Recommendations',
    path: '/recommendations',
    iconPath: '<path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>',
  },
  {
    label: 'Health Trends',
    path: '/trends',
    iconPath: '<polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline>',
  },
  {
    label: 'My Goals',
    path: '/goals',
    iconPath: '<circle cx="12" cy="12" r="10"></circle><circle cx="12" cy="12" r="6"></circle><circle cx="12" cy="12" r="2"></circle>',
  },
  {
    label: 'AI Tips',
    path: '/ai-tips',
    iconPath: '<path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"></path>',
  },
  {
    label: 'Settings',
    path: '/settings',
    iconPath: '<circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"></path>',
  },
]



// ─── Helpers ───────────────────────────────────────────────────────────────

function parseLocalDate(dateStr) {
  if (!dateStr) return 0
  // Date-only strings ("2026-04-10") must be parsed as local midnight, not UTC midnight,
  // otherwise timezone offsets cause off-by-one day errors in relative time display.
  if (/^\d{4}-\d{2}-\d{2}$/.test(dateStr)) {
    const [y, m, d] = dateStr.split('-').map(Number)
    return new Date(y, m - 1, d).getTime()
  }
  return new Date(dateStr).getTime()
}

function formatRelativeTime(dateStr) {
  if (!dateStr) return ''
  const ts   = parseLocalDate(dateStr)
  const diff  = Date.now() - ts
  const mins  = Math.floor(diff / 60000)
  const hours = Math.floor(diff / 3600000)
  const days  = Math.floor(diff / 86400000)
  if (mins < 1)   return 'Just now'
  if (mins < 60)  return `${mins} minute${mins !== 1 ? 's' : ''} ago`
  if (hours < 24) return `${hours} hour${hours !== 1 ? 's' : ''} ago`
  if (days === 1) return 'Yesterday'
  if (days < 7)   return `${days} days ago`
  return new Date(ts).toLocaleDateString('en-US', { month: 'short', day: 'numeric' })
}

function notificationKindIcon(kind) {
  return {
    recommendation_generated: {
      iconBg: 'bg-green-100',
      iconColor: 'text-green-600',
      iconPath: '<path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>',
    },
    goal_achieved: {
      iconBg: 'bg-blue-100',
      iconColor: 'text-blue-600',
      iconPath: '<circle cx="12" cy="12" r="10"></circle><circle cx="12" cy="12" r="6"></circle><circle cx="12" cy="12" r="2"></circle>',
    },
    weekly_report: {
      iconBg: 'bg-purple-100',
      iconColor: 'text-purple-600',
      iconPath: '<rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line>',
    },
    measurement_reminder: {
      iconBg: 'bg-yellow-100',
      iconColor: 'text-yellow-600',
      iconPath: '<path d="M12 8v4l3 3"></path><circle cx="12" cy="12" r="10"></circle>',
    },
    general: {
      iconBg: 'bg-gray-100',
      iconColor: 'text-gray-600',
      iconPath: '<polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>',
    },
  }[kind] ?? {
    iconBg: 'bg-gray-100',
    iconColor: 'text-gray-600',
    iconPath: '<polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>',
  }
}

function publishUnreadCount() {
  window.dispatchEvent(new CustomEvent('notifications-unread-count', {
    detail: { unreadCount: totalUnreadActivityCount.value },
  }))
}

async function markAllActivitySeen() {
  markingActivitySeen.value = true

  try {
    await activityService.markAllSeen()
    recentNotifications.value = recentNotifications.value.map(notification => ({ ...notification, is_read: true }))
    totalUnreadActivityCount.value = 0
    publishUnreadCount()
  } finally {
    markingActivitySeen.value = false
  }
}

// ─── Data Loading ──────────────────────────────────────────────────────────

onMounted(async () => {
  const [compRes, activityRes, recRes, goalRes] = await Promise.allSettled([
    bodyCompositionService.getAll({ limit: 5, sort: 'desc' }),
    activityService.getAll(),
    healthRecommendationService.getAll(),
    goalService.getAll(),
  ])

  if (compRes.status === 'fulfilled') {
    const data = compRes.value.data
    recentRecords.value = Array.isArray(data) ? data : (data.data ?? [])
  }

  if (activityRes.status === 'fulfilled') {
    const payload = activityRes.value.data
    recentNotifications.value = payload.data ?? []
    totalUnreadActivityCount.value = payload.meta?.unread_count ?? 0
    publishUnreadCount()
  }

  loadingActivity.value = false

  if (recRes.status === 'fulfilled') {
    const data = recRes.value.data
    tipsFromBackend.value = Array.isArray(data) ? data : (data.data ?? [])
  }
  loadingTips.value = false

  if (goalRes.status === 'fulfilled') {
    const data = goalRes.value.data
    goalsData.value = Array.isArray(data) ? data : (data.data ?? [])
  }
  loadingGoals.value = false
})
</script>
