<template>
  <div class="space-y-6">
    <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
      <h2 class="text-2xl font-bold text-gray-900 mb-2">AI-Powered Tips</h2>
      <p class="text-gray-600">Intelligent insights tailored to your progress</p>
    </div>

    <div class="bg-gradient-to-r from-blue-600 to-cyan-600 rounded-lg shadow p-6 text-white">
      <h3 class="text-xl font-bold mb-2">Weekly AI Analysis</h3>
      <p class="mb-4">Based on your metrics, here's what's working well for you this week.</p>
      <button class="bg-white text-blue-600 font-semibold px-4 py-2 rounded-lg hover:bg-gray-100 transition-colors">
        View Full Analysis
      </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">💡 Smart Insights</h3>
        <ul class="space-y-3">
          <li class="flex items-start gap-3">
            <span class="text-green-600 font-bold">✓</span>
            <span class="text-sm text-gray-700">Your muscle growth is on track. Keep consistent with strength training.</span>
          </li>
          <li class="flex items-start gap-3">
            <span class="text-green-600 font-bold">✓</span>
            <span class="text-sm text-gray-700">Body fat is decreasing steadily. Current pace: 0.5% per week.</span>
          </li>
          <li class="flex items-start gap-3">
            <span class="text-yellow-600 font-bold">!</span>
            <span class="text-sm text-gray-700">Weight plateau detected. Consider adjusting caloric intake or exercise routine.</span>
          </li>
        </ul>
      </div>

      <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">🎯 Predictions</h3>
        <div class="space-y-4">
          <div>
            <p class="text-sm text-gray-600 mb-2">At current pace, you'll reach:</p>
            <p class="text-sm font-semibold text-green-600">Target Weight: 70kg in ~4 weeks</p>
          </div>
          <div>
            <p class="text-sm text-gray-600 mb-2">Body Fat Goal: 16% in ~8 weeks</p>
            <p class="text-sm font-semibold text-green-600">Confidence: 87%</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
</script>
