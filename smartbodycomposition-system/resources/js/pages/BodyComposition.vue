<template>
  <div class="space-y-6">
    <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
      <h2 class="text-2xl font-bold text-gray-900 mb-2">Body Composition</h2>
      <p class="text-gray-600">Detailed body composition analysis and measurements</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Add New Measurement</h3>
        <form class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
            <input type="date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Weight (kg)</label>
            <input type="number" placeholder="72.5" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Body Fat (%)</label>
            <input type="number" placeholder="18.5" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500" />
          </div>
          <button type="submit" class="w-full bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-semibold py-2 rounded-lg transition-all duration-200">
            Record Measurement
          </button>
        </form>
      </div>

      <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Measurement History</h3>
        <div class="space-y-3">
          <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
            <span class="text-sm text-gray-600">2025-01-15</span>
            <span class="font-medium text-gray-900">72.3 kg</span>
          </div>
          <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
            <span class="text-sm text-gray-600">2025-01-14</span>
            <span class="font-medium text-gray-900">72.5 kg</span>
          </div>
          <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
            <span class="text-sm text-gray-600">2025-01-13</span>
            <span class="font-medium text-gray-900">72.6 kg</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const measurements = ref([
  { date: '2025-01-15', weight: 72.3 },
  { date: '2025-01-14', weight: 72.5 },
  { date: '2025-01-13', weight: 72.6 },
])
</script>
