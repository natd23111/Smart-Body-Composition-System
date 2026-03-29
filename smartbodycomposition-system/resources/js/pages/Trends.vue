<template>
  <div class="space-y-6">
    <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
      <h2 class="text-2xl font-bold text-gray-900 mb-2">Trends</h2>
      <p class="text-gray-600">View your long-term health and fitness trends</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
      <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
        <p class="text-sm text-gray-600 mb-2">Weight Trend</p>
        <p class="text-2xl font-bold text-green-600">↓ 9.7 kg</p>
        <p class="text-xs text-gray-500 mt-1">6-month change</p>
      </div>

      <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
        <p class="text-sm text-gray-600 mb-2">Body Fat Trend</p>
        <p class="text-2xl font-bold text-green-600">↓ 6.4%</p>
        <p class="text-xs text-gray-500 mt-1">6-month change</p>
      </div>

      <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
        <p class="text-sm text-gray-600 mb-2">Muscle Mass Trend</p>
        <p class="text-2xl font-bold text-blue-600">↑ 3.4 kg</p>
        <p class="text-xs text-gray-500 mt-1">6-month change</p>
      </div>

      <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
        <p class="text-sm text-gray-600 mb-2">BMI Trend</p>
        <p class="text-2xl font-bold text-green-600">↓ 3.1</p>
        <p class="text-xs text-gray-500 mt-1">6-month change</p>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Weight Progress (6 months)</h3>
        <div class="h-64 flex items-center justify-center bg-gray-50 rounded-lg border border-gray-200">
          <div class="text-center">
            <p class="text-gray-500 text-sm">📊 Chart will display here</p>
            <p class="text-gray-400 text-xs mt-2">(Install chart library for visualization)</p>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Body Composition Changes</h3>
        <div class="space-y-4">
          <div>
            <div class="flex justify-between mb-2">
              <span class="text-sm font-medium text-gray-700">Fat Mass Reduction</span>
              <span class="text-sm text-gray-600">-6.4 kg (54%)</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
              <div class="bg-red-500 h-2 rounded-full" style="width: 54%"></div>
            </div>
          </div>
          <div>
            <div class="flex justify-between mb-2">
              <span class="text-sm font-medium text-gray-700">Muscle Gain</span>
              <span class="text-sm text-gray-600">+3.4 kg (35%)</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
              <div class="bg-green-500 h-2 rounded-full" style="width: 35%"></div>
            </div>
          </div>
          <div>
            <div class="flex justify-between mb-2">
              <span class="text-sm font-medium text-gray-700">Other Changes</span>
              <span class="text-sm text-gray-600">+0.3 kg (3%)</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
              <div class="bg-blue-500 h-2 rounded-full" style="width: 3%"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
</script>
