<template>
  <div class="space-y-6">
    <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
      <h2 class="text-2xl font-bold text-gray-900 mb-2">Recommendations</h2>
      <p class="text-gray-600">Personalized health recommendations based on your metrics</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-lg shadow border border-green-200 p-6">
        <div class="flex items-start gap-3 mb-3">
          <div class="p-2 bg-green-100 rounded-lg">
            <svg class="h-5 w-5 text-green-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
            </svg>
          </div>
          <h3 class="text-lg font-semibold text-green-900">Nutrition</h3>
        </div>
        <p class="text-sm text-green-700">Focus on whole foods and balanced macronutrients. Aim for 1.2-1.6g protein per kg of body weight daily.</p>
      </div>

      <div class="bg-gradient-to-br from-blue-50 to-cyan-50 rounded-lg shadow border border-blue-200 p-6">
        <div class="flex items-start gap-3 mb-3">
          <div class="p-2 bg-blue-100 rounded-lg">
            <svg class="h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="1"></circle>
            </svg>
          </div>
          <h3 class="text-lg font-semibold text-blue-900">Exercise</h3>
        </div>
        <p class="text-sm text-blue-700">Combine strength training 3-4x per week with 150 minutes of cardio for optimal results.</p>
      </div>

      <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-lg shadow border border-purple-200 p-6">
        <div class="flex items-start gap-3 mb-3">
          <div class="p-2 bg-purple-100 rounded-lg">
            <svg class="h-5 w-5 text-purple-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="3"></circle>
            </svg>
          </div>
          <h3 class="text-lg font-semibold text-purple-900">Sleep</h3>
        </div>
        <p class="text-sm text-purple-700">Aim for 7-9 hours of quality sleep nightly to support muscle recovery and fat loss.</p>
      </div>
    </div>
  </div>
</template>

<script setup>
</script>
