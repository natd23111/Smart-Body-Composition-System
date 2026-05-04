<template>
  <div class="space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Your Goals</h1>
        <p class="text-sm text-gray-500 mt-0.5">Track your progress toward health objectives</p>
      </div>
      <button
        @click="openCreate"
        class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 transition-colors"
      >
        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line>
        </svg>
        New Goal
      </button>
    </div>

    <!-- Loading -->
    <div v-if="loading" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
      <div v-for="n in 3" :key="n" class="bg-white rounded-lg shadow border border-gray-200 p-5 animate-pulse space-y-3">
        <div class="h-4 bg-gray-200 rounded w-1/2"></div>
        <div class="h-3 bg-gray-100 rounded w-3/4"></div>
        <div class="h-2 bg-gray-200 rounded-full w-full"></div>
        <div class="h-3 bg-gray-100 rounded w-1/3"></div>
      </div>
    </div>

    <!-- Empty state -->
    <div v-else-if="goals.length === 0" class="bg-white rounded-lg shadow border border-gray-200 py-16 text-center">
      <svg class="h-14 w-14 text-gray-200 mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="12" r="10"></circle><circle cx="12" cy="12" r="6"></circle><circle cx="12" cy="12" r="2"></circle>
      </svg>
      <p class="text-gray-600 font-medium mb-1">No goals yet</p>
      <p class="text-gray-400 text-sm mb-4">Set your first health goal to start tracking progress.</p>
      <button @click="openCreate" class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 transition-colors">
        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line>
        </svg>
        Create a Goal
      </button>
    </div>

    <!-- Goals grid -->
    <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
      <div
        v-for="goal in goals"
        :key="goal.id"
        :class="[
          'bg-white rounded-lg shadow border p-5 space-y-4 transition-opacity',
          goal.status === 'abandoned' ? 'opacity-50 border-gray-200' :
          goal.status === 'achieved'  ? 'border-green-300 bg-green-50/30' :
                                        'border-gray-200'
        ]"
      >
        <!-- Title row -->
        <div class="flex items-start justify-between gap-2">
          <div>
            <div class="flex items-center gap-2 flex-wrap">
              <h3 class="font-semibold text-gray-900">{{ goal.metric_label }}</h3>
              <span :class="statusBadge(goal.status)" class="text-xs px-2 py-0.5 rounded-full font-medium">
                {{ goal.status.charAt(0).toUpperCase() + goal.status.slice(1) }}
              </span>
            </div>
            <p v-if="goal.deadline" class="text-xs text-gray-400 mt-0.5">
              Deadline: {{ formatDate(goal.deadline) }}
              <span v-if="daysLeft(goal.deadline) !== null" :class="daysLeft(goal.deadline) <= 7 ? 'text-red-500' : 'text-gray-400'">
                ({{ daysLeft(goal.deadline) > 0 ? daysLeft(goal.deadline) + ' days left' : 'overdue' }})
              </span>
            </p>
          </div>
          <!-- Actions -->
          <div class="flex items-center gap-1 flex-shrink-0">
            <button @click="openEdit(goal)" class="p-1.5 rounded hover:bg-gray-100 text-gray-400 hover:text-gray-600 transition-colors">
              <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
              </svg>
            </button>
            <button @click="confirmDelete(goal)" class="p-1.5 rounded hover:bg-red-50 text-gray-400 hover:text-red-500 transition-colors">
              <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="3 6 5 6 21 6"></polyline>
                <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"></path>
                <path d="M10 11v6"></path><path d="M14 11v6"></path>
                <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"></path>
              </svg>
            </button>
          </div>
        </div>

        <!-- Values row -->
        <div class="grid grid-cols-3 gap-2 text-center">
          <div>
            <p class="text-xs text-gray-400">Start</p>
            <p class="text-sm font-semibold text-gray-700">{{ goal.start_value != null ? goal.start_value + ' ' + goal.metric_unit : '—' }}</p>
          </div>
          <div>
            <p class="text-xs text-gray-400">Current</p>
            <p class="text-sm font-semibold" :class="progressColor(goal)">
              {{ goal.current_value != null ? Number(goal.current_value).toFixed(1) + ' ' + goal.metric_unit : '—' }}
            </p>
          </div>
          <div>
            <p class="text-xs text-gray-400">Target</p>
            <p class="text-sm font-semibold text-green-700">{{ goal.target_value }} {{ goal.metric_unit }}</p>
          </div>
        </div>

        <!-- Progress bar -->
        <div v-if="goal.progress !== null">
          <div class="flex justify-between text-xs text-gray-400 mb-1">
            <span>Progress</span>
            <span>{{ goal.progress }}%</span>
          </div>
          <div class="w-full bg-gray-100 rounded-full h-2">
            <div
              :style="{ width: goal.progress + '%' }"
              :class="[
                'h-2 rounded-full transition-all duration-500',
                goal.progress >= 100 ? 'bg-green-500' : goal.progress >= 50 ? 'bg-blue-500' : 'bg-amber-400'
              ]"
            ></div>
          </div>
        </div>
        <div v-else class="text-xs text-gray-400 italic">Log a measurement to see progress</div>

        <!-- Notes -->
        <p v-if="goal.notes" class="text-xs text-gray-500 border-t border-gray-100 pt-3 line-clamp-2">{{ goal.notes }}</p>

        <!-- Mark achieved / reactivate -->
        <div class="pt-1 flex gap-2">
          <button
            v-if="goal.status === 'active'"
            @click="updateStatus(goal, 'achieved')"
            class="flex-1 text-xs py-1.5 rounded border border-green-300 text-green-700 hover:bg-green-50 transition-colors font-medium"
          >
            Mark Achieved
          </button>
          <button
            v-if="goal.status === 'active'"
            @click="updateStatus(goal, 'abandoned')"
            class="flex-1 text-xs py-1.5 rounded border border-gray-200 text-gray-500 hover:bg-gray-50 transition-colors"
          >
            Abandon
          </button>
          <button
            v-if="goal.status !== 'active'"
            @click="updateStatus(goal, 'active')"
            class="flex-1 text-xs py-1.5 rounded border border-blue-300 text-blue-700 hover:bg-blue-50 transition-colors font-medium"
          >
            Reactivate
          </button>
        </div>
      </div>
    </div>

    <!-- Create / Edit Modal -->
    <Teleport to="body">
      <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50" @click.self="closeModal">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md">
          <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h2 class="font-semibold text-gray-900">{{ editingGoal ? 'Edit Goal' : 'New Goal' }}</h2>
            <button @click="closeModal" class="p-1.5 rounded hover:bg-gray-100 text-gray-400">
              <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
          </div>

          <form @submit.prevent="saveGoal" class="px-6 py-5 space-y-4">

            <!-- Validation errors -->
            <div v-if="Object.keys(formErrors).length > 0" class="bg-red-50 border border-red-200 rounded-lg px-4 py-3 text-sm text-red-700">
              <ul class="list-disc list-inside space-y-0.5">
                <li v-for="(msg, field) in formErrors" :key="field">{{ msg }}</li>
              </ul>
            </div>

            <!-- Metric -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Metric</label>
              <select
                v-model="form.metric"
                :disabled="!!editingGoal"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 disabled:bg-gray-50 disabled:text-gray-500"
              >
                <option value="">Select a metric</option>
                <option v-for="m in METRICS" :key="m.value" :value="m.value">{{ m.label }}</option>
              </select>
            </div>

            <!-- Target -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">
                Target Value <span v-if="selectedMetricUnit" class="text-gray-400">({{ selectedMetricUnit }})</span>
              </label>
              <input
                v-model="form.target_value"
                type="number"
                step="0.1"
                min="0"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500"
              />
            </div>

            <!-- Deadline -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Deadline <span class="text-gray-400">(optional)</span></label>
              <input
                v-model="form.deadline"
                type="date"
                :min="tomorrowDate"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500"
              />
            </div>

            <!-- Notes -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Notes <span class="text-gray-400">(optional)</span></label>
              <textarea
                v-model="form.notes"
                rows="2"
                maxlength="500"
                placeholder="Any additional context for this goal..."
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 resize-none"
              ></textarea>
            </div>

            <div class="flex gap-3 pt-1">
              <button type="button" @click="closeModal" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                Cancel
              </button>
              <button
                type="submit"
                :disabled="saving"
                class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-semibold hover:bg-green-700 disabled:opacity-60 transition-colors"
              >
                {{ saving ? 'Saving...' : (editingGoal ? 'Save Changes' : 'Create Goal') }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </Teleport>

    <!-- Delete confirmation -->
    <Teleport to="body">
      <div v-if="deletingGoal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50" @click.self="deletingGoal = null">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-sm p-6 space-y-4">
          <h2 class="font-semibold text-gray-900">Delete Goal?</h2>
          <p class="text-sm text-gray-500">
            This will permanently delete your <strong>{{ deletingGoal.metric_label }}</strong> goal. This cannot be undone.
          </p>
          <div class="flex gap-3">
            <button @click="deletingGoal = null" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">Cancel</button>
            <button @click="deleteGoal" :disabled="deleting" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-semibold hover:bg-red-700 disabled:opacity-60 transition-colors">
              {{ deleting ? 'Deleting...' : 'Delete' }}
            </button>
          </div>
        </div>
      </div>
    </Teleport>

  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { goalService } from '@/services/api'

const METRICS = [
  { value: 'weight_kg',          label: 'Weight',      unit: 'kg' },
  { value: 'body_fat_percent',   label: 'Body Fat',    unit: '%' },
  { value: 'muscle_mass',        label: 'Muscle Mass', unit: 'kg' },
  { value: 'bmi',                label: 'BMI',         unit: 'kg/m²' },
  { value: 'visceral_fat',       label: 'Visceral Fat', unit: '' },
  { value: 'body_water_percent', label: 'Body Water',  unit: '%' },
]

const loading    = ref(true)
const goals      = ref([])
const showModal  = ref(false)
const editingGoal = ref(null)
const saving     = ref(false)
const deletingGoal = ref(null)
const deleting   = ref(false)

const form = ref({ metric: '', target_value: '', deadline: '', notes: '' })
const formErrors = ref({})

const tomorrowDate = computed(() => {
  const d = new Date()
  d.setDate(d.getDate() + 1)
  return d.toISOString().slice(0, 10)
})

const selectedMetricUnit = computed(() =>
  METRICS.find(m => m.value === form.value.metric)?.unit ?? ''
)

// ─── Load ─────────────────────────────────────────────────────────────────

onMounted(() => fetchGoals())

async function fetchGoals() {
  loading.value = true
  try {
    const res = await goalService.getAll()
    const data = res.data
    goals.value = Array.isArray(data) ? data : (data.data ?? [])
  } catch {
    // silently degrade
  } finally {
    loading.value = false
  }
}

// ─── Modal ────────────────────────────────────────────────────────────────

function openCreate() {
  editingGoal.value = null
  form.value = { metric: '', target_value: '', deadline: '', notes: '' }
  formErrors.value = {}
  showModal.value = true
}

function openEdit(goal) {
  editingGoal.value = goal
  form.value = {
    metric:       goal.metric,
    target_value: goal.target_value,
    deadline:     goal.deadline ?? '',
    notes:        goal.notes ?? '',
  }
  formErrors.value = {}
  showModal.value = true
}

function closeModal() {
  showModal.value = false
  editingGoal.value = null
  formErrors.value = {}
}

// ─── Save ─────────────────────────────────────────────────────────────────

async function saveGoal() {
  formErrors.value = {}
  const errors = {}

  if (!form.value.metric) errors.metric = 'Please select a metric.'
  if (!form.value.target_value && form.value.target_value !== 0) errors.target_value = 'Please enter a target value.'
  else if (isNaN(Number(form.value.target_value)) || Number(form.value.target_value) < 0) errors.target_value = 'Target must be a positive number.'

  if (Object.keys(errors).length > 0) {
    formErrors.value = errors
    return
  }

  saving.value = true
  try {
    const payload = {
      metric:       form.value.metric,
      target_value: Number(form.value.target_value),
      deadline:     form.value.deadline || null,
      notes:        form.value.notes || null,
    }

    if (editingGoal.value) {
      const res = await goalService.update(editingGoal.value.id, payload)
      const updated = res.data.data
      const idx = goals.value.findIndex(g => g.id === updated.id)
      if (idx !== -1) goals.value[idx] = updated
    } else {
      const res = await goalService.create(payload)
      goals.value.unshift(res.data.data)
    }

    closeModal()
  } catch (e) {
    const serverErrors = e.response?.data?.errors
    if (serverErrors) formErrors.value = Object.fromEntries(Object.entries(serverErrors).map(([k, v]) => [k, Array.isArray(v) ? v[0] : v]))
    else formErrors.value = { general: e.response?.data?.message ?? 'Something went wrong.' }
  } finally {
    saving.value = false
  }
}

// ─── Status update ────────────────────────────────────────────────────────

async function updateStatus(goal, status) {
  try {
    const res = await goalService.update(goal.id, { status })
    const updated = res.data.data
    const idx = goals.value.findIndex(g => g.id === updated.id)
    if (idx !== -1) goals.value[idx] = updated
  } catch {
    // silently fail
  }
}

// ─── Delete ───────────────────────────────────────────────────────────────

function confirmDelete(goal) {
  deletingGoal.value = goal
}

async function deleteGoal() {
  if (!deletingGoal.value) return
  deleting.value = true
  try {
    await goalService.remove(deletingGoal.value.id)
    goals.value = goals.value.filter(g => g.id !== deletingGoal.value.id)
    deletingGoal.value = null
  } catch {
    // silently fail
  } finally {
    deleting.value = false
  }
}

// ─── Helpers ──────────────────────────────────────────────────────────────

function statusBadge(status) {
  return {
    active:    'bg-blue-100 text-blue-700',
    achieved:  'bg-green-100 text-green-700',
    abandoned: 'bg-gray-100 text-gray-500',
  }[status] ?? 'bg-gray-100 text-gray-500'
}

function progressColor(goal) {
  if (goal.current_value === null) return 'text-gray-400'
  const diff = goal.lower_is_better
    ? (goal.start_value ?? goal.current_value) - goal.current_value
    : goal.current_value - (goal.start_value ?? goal.current_value)
  if (diff > 0) return 'text-green-600'
  if (diff < 0) return 'text-red-500'
  return 'text-gray-600'
}

function formatDate(dateStr) {
  if (!dateStr) return ''
  const [y, m, d] = dateStr.split('-').map(Number)
  return new Date(y, m - 1, d).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })
}

function daysLeft(dateStr) {
  if (!dateStr) return null
  const [y, m, d] = dateStr.split('-').map(Number)
  const deadline = new Date(y, m - 1, d)
  const today = new Date(); today.setHours(0, 0, 0, 0)
  return Math.round((deadline - today) / 86400000)
}
</script>
