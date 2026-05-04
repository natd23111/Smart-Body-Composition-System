<template>
  <div class="space-y-6">

    <!-- Page Header -->
    <div>
      <h1 class="text-3xl font-bold text-gray-900">Data Management</h1>
      <p class="text-gray-500 mt-1">Monitor system data integrity (non-sensitive metadata only)</p>
    </div>

    <!-- Summary Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
        <p class="text-sm text-gray-500 mb-1">Total Records</p>
        <p class="text-2xl font-bold text-gray-900">{{ loading ? '…' : records.length }}</p>
      </div>
      <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
        <p class="text-sm text-gray-500 mb-1">Submitted Today</p>
        <p class="text-2xl font-bold text-gray-900">{{ loading ? '…' : submittedToday }}</p>
      </div>
      <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
        <p class="text-sm text-gray-500 mb-1">System Status</p>
        <p class="text-2xl font-bold text-green-600">Operational</p>
      </div>
    </div>

    <!-- Search and Sort -->
    <div class="bg-white rounded-lg shadow border border-gray-200 p-4">
      <div class="flex flex-col md:flex-row gap-3">
        <!-- Search -->
        <div class="flex-1 relative">
          <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line>
          </svg>
          <input
            v-model="searchTerm"
            type="text"
            placeholder="Search by record ID..."
            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500"
          />
        </div>
        <!-- Sort -->
        <select
          v-model="sortBy"
          class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500"
        >
          <option value="timestamp">Sort by Timestamp</option>
          <option value="recordId">Sort by Record ID</option>
        </select>
        <!-- Order toggle -->
        <button
          @click="sortOrder = sortOrder === 'asc' ? 'desc' : 'asc'"
          class="inline-flex items-center gap-2 px-3 py-2 border border-gray-300 rounded-lg text-sm hover:bg-gray-50 transition-colors"
        >
          <svg class="h-4 w-4 text-gray-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline>
          </svg>
          {{ sortOrder === 'asc' ? 'Ascending' : 'Descending' }}
        </button>
      </div>
    </div>

    <!-- Records Table -->
    <div class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden">
      <div class="px-6 py-4 border-b border-gray-100">
        <h3 class="font-semibold text-gray-900">System Records ({{ sortedRecords.length }})</h3>
        <p class="text-sm text-gray-500">Record identifiers and submission timestamps (personal health data not accessible)</p>
      </div>
      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
              <th class="text-left py-3 px-4 font-semibold text-gray-700">Record ID</th>
              <th class="text-left py-3 px-4 font-semibold text-gray-700">Submission Timestamp</th>
              <th class="text-left py-3 px-4 font-semibold text-gray-700">Status</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            <tr v-if="loading">
              <td colspan="3" class="py-10 text-center text-gray-400 text-sm">Loading records…</td>
            </tr>
            <tr v-else v-for="record in sortedRecords" :key="record.id" class="hover:bg-gray-50 transition-colors">
              <td class="py-3 px-4 font-mono font-medium text-blue-600">{{ record.recordId }}</td>
              <td class="py-3 px-4 text-gray-600">{{ record.timestamp }}</td>
              <td class="py-3 px-4">
                <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                  {{ record.status }}
                </span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Privacy Note -->
      <div class="mx-6 my-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
        <p class="text-sm text-blue-700">
          <span class="font-semibold">Privacy Note:</span> Detailed personal health information (body composition values) is not accessible to administrators and remains visible only to individual users.
        </p>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'

const searchTerm = ref('')
const sortBy = ref('timestamp')
const sortOrder = ref('desc')
const loading = ref(true)

const records = ref([])

async function fetchRecords() {
  try {
    const token = localStorage.getItem('auth_token')
    const res = await fetch('/api/admin/records', {
      headers: {
        'Accept': 'application/json',
        'Authorization': `Bearer ${token}`,
      },
    })
    records.value = await res.json()
  } catch (e) {
    console.error('Failed to load records:', e)
  } finally {
    loading.value = false
  }
}

onMounted(fetchRecords)

const submittedToday = computed(() => {
  const today = new Date().toISOString().slice(0, 10)
  return records.value.filter(r => r.timestamp.startsWith(today)).length
})

const filteredRecords = computed(() =>
  records.value.filter(r => r.recordId.toLowerCase().includes(searchTerm.value.toLowerCase()))
)

const sortedRecords = computed(() => {
  return [...filteredRecords.value].sort((a, b) => {
    const cmp = sortBy.value === 'timestamp'
      ? new Date(a.timestamp).getTime() - new Date(b.timestamp).getTime()
      : a.recordId.localeCompare(b.recordId)
    return sortOrder.value === 'asc' ? cmp : -cmp
  })
})
</script>
