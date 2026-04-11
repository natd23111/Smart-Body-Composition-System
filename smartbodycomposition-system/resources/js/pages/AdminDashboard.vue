<template>
  <div class="space-y-8">

    <!-- Loading -->
    <div v-if="loading" class="flex items-center justify-center py-16">
      <svg class="animate-spin h-8 w-8 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
      </svg>
    </div>

    <template v-else>
    <div>
      <h1 class="text-3xl font-bold text-gray-900">Admin Dashboard</h1>
      <p class="text-gray-500 mt-1">System usage overview and administrative information</p>
    </div>

    <!-- Key Metrics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

      <!-- Total Users -->
      <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
          <p class="text-sm font-semibold text-gray-700">Total Users</p>
          <div class="p-2 bg-blue-100 rounded-lg">
            <svg class="h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
            </svg>
          </div>
        </div>
        <p class="text-3xl font-bold text-gray-900">{{ stats.totalUsers }}</p>
        <div class="flex items-center gap-1 mt-1">
          <svg class="h-4 w-4 text-green-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="18 15 12 9 6 15"></polyline></svg>
          <span class="text-sm font-medium text-green-600">+12% this month</span>
        </div>
      </div>

      <!-- New Registrations -->
      <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
          <p class="text-sm font-semibold text-gray-700">New Registrations</p>
          <div class="p-2 bg-green-100 rounded-lg">
            <svg class="h-5 w-5 text-green-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline>
            </svg>
          </div>
        </div>
        <div class="flex items-baseline gap-1">
          <p class="text-3xl font-bold text-gray-900">{{ stats.newRegistrations }}</p>
          <span class="text-sm text-gray-500">this month</span>
        </div>
        <div class="flex items-center gap-1 mt-1">
          <svg class="h-4 w-4 text-green-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="18 15 12 9 6 15"></polyline></svg>
          <span class="text-sm font-medium text-green-600">+8% growth</span>
        </div>
      </div>

      <!-- Active Users -->
      <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
          <p class="text-sm font-semibold text-gray-700">Active Users</p>
          <div class="p-2 bg-yellow-100 rounded-lg">
            <svg class="h-5 w-5 text-yellow-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
            </svg>
          </div>
        </div>
        <div class="flex items-baseline gap-1">
          <p class="text-3xl font-bold text-gray-900">{{ stats.activeUsers }}</p>
          <span class="text-sm text-gray-500">this month</span>
        </div>
        <p class="text-sm text-gray-500 mt-1">{{ activityRate }}% activity rate</p>
      </div>

      <!-- System Records -->
      <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
          <p class="text-sm font-semibold text-gray-700">System Records</p>
          <div class="p-2 bg-purple-100 rounded-lg">
            <svg class="h-5 w-5 text-purple-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <ellipse cx="12" cy="5" rx="9" ry="3"></ellipse><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"></path><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path>
            </svg>
          </div>
        </div>
        <div class="flex items-baseline gap-1">
          <p class="text-3xl font-bold text-gray-900">{{ stats.totalRecords.toLocaleString() }}</p>
          <span class="text-sm text-gray-500">submissions</span>
        </div>
        <div class="flex items-center gap-1 mt-1">
          <svg class="h-4 w-4 text-green-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="18 15 12 9 6 15"></polyline></svg>
          <span class="text-sm font-medium text-green-600">+{{ stats.recordsThisMonth }} this month</span>
        </div>
      </div>
    </div>

    <!-- Charts Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

      <!-- User Growth Chart -->
      <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
        <h3 class="text-base font-semibold text-gray-900 mb-1">User Growth Trend</h3>
        <p class="text-sm text-gray-500 mb-4">Monthly user registration growth</p>
        <div style="height: 260px; position: relative;">
          <Line :data="userGrowthChartData" :options="lineChartOptions" />
        </div>
      </div>

      <!-- System Activity Chart -->
      <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
        <h3 class="text-base font-semibold text-gray-900 mb-1">System Activity</h3>
        <p class="text-sm text-gray-500 mb-4">Monthly login activity across the system</p>
        <div style="height: 260px; position: relative;">
          <Bar :data="activityChartData" :options="barChartOptions" />
        </div>
      </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white rounded-lg shadow border border-gray-200">
      <div class="px-6 pt-6 pb-3 border-b border-gray-100">
        <h3 class="text-base font-semibold text-gray-900">Recent System Activity</h3>
        <p class="text-sm text-gray-500">Latest account-related events (no health data access)</p>
      </div>
      <div class="divide-y divide-gray-100">
        <div v-for="log in stats.recentActivity" :key="log.id" class="flex items-start gap-4 px-6 py-4">
          <div class="p-2 bg-blue-100 rounded-lg flex-shrink-0">
            <svg class="h-4 w-4 text-blue-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
            </svg>
          </div>
          <div class="flex-1 min-w-0">
            <p class="text-sm text-gray-800">
              <span class="font-semibold">{{ log.user }}</span> {{ log.action }}
            </p>
            <p class="text-xs text-gray-400 mt-0.5">{{ log.time }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- System Status -->
    <div class="bg-gradient-to-br from-green-50 to-emerald-50 border border-green-200 rounded-lg p-6">
      <div class="flex items-center gap-2 mb-4">
        <div class="w-3 h-3 bg-green-600 rounded-full animate-pulse"></div>
        <h3 class="text-base font-semibold text-gray-900">System Status</h3>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="flex items-center justify-between p-3 bg-white rounded-lg border border-green-100">
          <span class="text-sm font-medium text-gray-700">Server Status</span>
          <span class="text-xs font-semibold text-green-600 bg-green-100 px-2 py-1 rounded">Operational</span>
        </div>
        <div class="flex items-center justify-between p-3 bg-white rounded-lg border border-green-100">
          <span class="text-sm font-medium text-gray-700">Database</span>
          <span class="text-xs font-semibold text-green-600 bg-green-100 px-2 py-1 rounded">Connected</span>
        </div>
        <div class="flex items-center justify-between p-3 bg-white rounded-lg border border-green-100">
          <span class="text-sm font-medium text-gray-700">API Response</span>
          <span class="text-xs font-semibold text-green-600 bg-green-100 px-2 py-1 rounded">&lt;200ms</span>
        </div>
      </div>
    </div>

    </template><!-- v-else -->
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { Line, Bar } from 'vue-chartjs'
import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  BarElement,
  Filler,
  Tooltip,
  Legend,
} from 'chart.js'

ChartJS.register(CategoryScale, LinearScale, PointElement, LineElement, BarElement, Filler, Tooltip, Legend)

const loading = ref(true)

const stats = ref({
  totalUsers: 0,
  newRegistrations: 0,
  activeUsers: 0,
  totalRecords: 0,
  recordsThisMonth: 0,
  months: [],
  userGrowth: [],
  monthlyRecords: [],
  recentActivity: [],
})

const activityRate = computed(() => {
  if (!stats.value.totalUsers) return '0.0'
  return ((stats.value.activeUsers / stats.value.totalUsers) * 100).toFixed(1)
})

const userGrowthChartData = ref({
  labels: [],
  datasets: [{
    label: 'Users',
    data: [],
    fill: true,
    backgroundColor: 'rgba(59,130,246,0.08)',
    borderColor: 'rgba(59,130,246,0.8)',
    borderWidth: 2,
    pointBackgroundColor: 'rgba(59,130,246,1)',
    pointRadius: 4,
    tension: 0.4,
  }],
})

const activityChartData = ref({
  labels: [],
  datasets: [{
    label: 'Records Submitted',
    data: [],
    backgroundColor: 'rgba(16,185,129,0.75)',
    borderRadius: 6,
  }],
})

const lineChartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: { legend: { display: false }, tooltip: { mode: 'index', intersect: false } },
  scales: {
    x: { grid: { display: false }, ticks: { font: { size: 11 } } },
    y: { grid: { color: 'rgba(0,0,0,0.05)' }, ticks: { font: { size: 11 } } },
  },
}

const barChartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: { legend: { display: false }, tooltip: { mode: 'index', intersect: false } },
  scales: {
    x: { grid: { display: false }, ticks: { font: { size: 11 } } },
    y: { grid: { color: 'rgba(0,0,0,0.05)' }, ticks: { font: { size: 11 } } },
  },
}

async function fetchDashboard() {
  try {
    const token = localStorage.getItem('auth_token')
    const res = await fetch('/api/admin/dashboard', {
      headers: {
        'Accept': 'application/json',
        'Authorization': `Bearer ${token}`,
      },
    })
    const data = await res.json()

    stats.value = {
      totalUsers: data.total_users,
      newRegistrations: data.new_registrations,
      activeUsers: data.active_users,
      totalRecords: data.total_records,
      recordsThisMonth: data.records_this_month,
      months: data.months,
      userGrowth: data.user_growth,
      monthlyRecords: data.monthly_records,
      recentActivity: data.recent_activity,
    }

    userGrowthChartData.value = {
      labels: data.months,
      datasets: [{
        label: 'Users',
        data: data.user_growth,
        fill: true,
        backgroundColor: 'rgba(59,130,246,0.08)',
        borderColor: 'rgba(59,130,246,0.8)',
        borderWidth: 2,
        pointBackgroundColor: 'rgba(59,130,246,1)',
        pointRadius: 4,
        tension: 0.4,
      }],
    }

    activityChartData.value = {
      labels: data.months,
      datasets: [{
        label: 'Records Submitted',
        data: data.monthly_records,
        backgroundColor: 'rgba(16,185,129,0.75)',
        borderRadius: 6,
      }],
    }
  } catch (e) {
    console.error('Failed to load dashboard data:', e)
  } finally {
    loading.value = false
  }
}

onMounted(fetchDashboard)
</script>
