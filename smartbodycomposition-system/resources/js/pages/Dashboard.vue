<template>
  <div class="space-y-6">
    <!-- Header with Time Range Selector -->
    <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg shadow border border-green-200">
      <div class="p-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
          <div>
            <h1 class="text-3xl font-bold text-green-900">Health Dashboard</h1>
            <p class="text-gray-600 text-sm">Track your progress and health metrics over time</p>
          </div>
          <div class="flex gap-2 flex-wrap">
            <button
              v-for="range in ['1W', '1M', '3M', '6M']"
              :key="range"
              @click="timeRange = range"
              :class="[
                'px-4 py-2 rounded-lg font-semibold min-w-16 transition-colors duration-200',
                timeRange === range
                  ? 'bg-gradient-to-r from-green-600 to-emerald-600 text-white'
                  : 'border border-green-200 text-green-700 hover:bg-green-50'
              ]"
            >
              {{ range }}
            </button>
          </div>
        </div>
      </div>
    </div>

      <!-- Key Metrics Cards Grid -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Weight Card -->
        <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-gray-700">Weight</h3>
            <div :class="[
              'p-2 rounded-lg',
              currentData.weight < previousData.weight ? 'bg-green-100' : 'bg-orange-100'
            ]">
              <svg v-if="currentData.weight < previousData.weight" class="h-4 w-4 text-green-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                <polyline points="17 18 23 18 23 12"></polyline>
              </svg>
              <svg v-else class="h-4 w-4 text-orange-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                <polyline points="17 6 23 6 23 12"></polyline>
              </svg>
            </div>
          </div>
          <div class="space-y-2">
            <div class="flex items-baseline gap-1">
              <span class="text-3xl font-bold text-gray-900">{{ currentData.weight }}</span>
              <span class="text-gray-500 text-sm">kg</span>
            </div>
            <div class="flex items-center gap-2">
              <span :class="[
                'text-sm font-medium',
                weightChange <= 0 ? 'text-green-600' : 'text-orange-600'
              ]">
                {{ weightChange <= 0 ? '-' : '+' }}{{ Math.abs(weightChange) }} kg
              </span>
              <span class="text-xs text-gray-500">({{ Math.abs(weightChangePercent).toFixed(1) }}%)</span>
            </div>
          </div>
        </div>

        <!-- BMI Card -->
        <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-gray-700">BMI</h3>
            <div class="p-2 bg-blue-100 rounded-lg">
              <svg class="h-4 w-4 text-blue-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
              </svg>
            </div>
          </div>
          <div class="space-y-2">
            <div class="flex items-baseline gap-1">
              <span class="text-3xl font-bold text-gray-900">{{ currentData.bmi }}</span>
              <span class="text-gray-500 text-sm">kg/m²</span>
            </div>
            <span :class="[
              'inline-block px-3 py-1 rounded-full text-xs font-semibold text-white',
              bmiZone.badgeColor
            ]">
              {{ bmiZone.label }}
            </span>
          </div>
        </div>

        <!-- Body Fat Card -->
        <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-gray-700">Body Fat</h3>
            <div class="p-2 bg-yellow-100 rounded-lg">
              <svg class="h-4 w-4 text-yellow-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="1"></circle>
                <path d="M12 1v6m4.22-4.22-4.24 4.24M12 23v-6m4.22 4.22-4.24-4.24M23 12h-6m4.22 4.22-4.24-4.24M1 12h6M4.22 16.22l4.24-4.24"></path>
              </svg>
            </div>
          </div>
          <div class="space-y-2">
            <div class="flex items-baseline gap-1">
              <span class="text-3xl font-bold text-gray-900">{{ currentData.bodyFat }}</span>
              <span class="text-gray-500 text-sm">%</span>
            </div>
            <div class="flex items-center gap-2">
              <span class="text-sm font-medium text-yellow-600">
                {{ bodyFatChange <= 0 ? '-' : '+' }}{{ Math.abs(bodyFatChange).toFixed(1) }}%
              </span>
              <span :class="[
                'inline-block px-3 py-1 rounded-full text-xs font-semibold text-white',
                bodyFatZone.badgeColor
              ]">
                {{ bodyFatZone.label }}
              </span>
            </div>
          </div>
        </div>

        <!-- Muscle Mass Card -->
        <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-gray-700">Muscle Mass</h3>
            <div class="p-2 bg-green-100 rounded-lg">
              <svg class="h-4 w-4 text-green-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M6 4h12v16H6z"></path>
                <circle cx="6" cy="4" r="1"></circle>
                <circle cx="18" cy="4" r="1"></circle>
              </svg>
            </div>
          </div>
          <div class="space-y-2">
            <div class="flex items-baseline gap-1">
              <span class="text-3xl font-bold text-gray-900">{{ currentData.muscleMass }}</span>
              <span class="text-gray-500 text-sm">kg</span>
            </div>
            <div class="flex items-center gap-2">
              <span class="text-sm font-medium text-green-600">
                {{ muscleMassChange >= 0 ? '+' : '-' }}{{ Math.abs(muscleMassChange).toFixed(1) }} kg
              </span>
            </div>
          </div>
        </div>
      </div>

      <!-- Charts Grid -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Weight Trend Chart -->
        <div class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden">
          <div class="border-b border-gray-200 p-6">
            <div class="flex items-center gap-3">
              <div class="p-2 bg-green-100 rounded-lg">
                <svg class="h-5 w-5 text-green-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                  <polyline points="17 18 23 18 23 12"></polyline>
                </svg>
              </div>
              <div>
                <h3 class="font-bold text-gray-900">Weight Trend</h3>
                <p class="text-sm text-gray-600">Weight progress over selected period</p>
              </div>
            </div>
          </div>
          <div class="p-6">
            <div class="h-64 flex items-center justify-center bg-gray-50 rounded-lg border border-gray-200">
              <div class="text-center">
                <p class="text-gray-500 text-sm">📊 Chart will display here</p>
                <p class="text-gray-400 text-xs mt-2">(Install chart library for visualization)</p>
              </div>
            </div>
          </div>
        </div>

        <!-- BMI Trend Chart -->
        <div class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden">
          <div class="border-b border-gray-200 p-6">
            <div class="flex items-center gap-3">
              <div class="p-2 bg-blue-100 rounded-lg">
                <svg class="h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                </svg>
              </div>
              <div>
                <h3 class="font-bold text-gray-900">BMI Trend</h3>
                <p class="text-sm text-gray-600">Body Mass Index progression</p>
              </div>
            </div>
          </div>
          <div class="p-6">
            <div class="h-64 flex items-center justify-center bg-gray-50 rounded-lg border border-gray-200">
              <div class="text-center">
                <p class="text-gray-500 text-sm">📊 Chart will display here</p>
                <p class="text-gray-400 text-xs mt-2">(Install chart library for visualization)</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Muscle Mass Trend -->
        <div class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden">
          <div class="border-b border-gray-200 p-6">
            <div class="flex items-center gap-3">
              <div class="p-2 bg-green-100 rounded-lg">
                <svg class="h-5 w-5 text-green-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M6 4h12v16H6z"></path>
                </svg>
              </div>
              <div>
                <h3 class="font-bold text-gray-900">Muscle Mass</h3>
                <p class="text-sm text-gray-600">Lean muscle growth tracking</p>
              </div>
            </div>
          </div>
          <div class="p-6">
            <div class="h-56 flex items-center justify-center bg-gray-50 rounded-lg border border-gray-200 mb-4">
              <div class="text-center">
                <p class="text-gray-500 text-sm">📊 Chart will display here</p>
                <p class="text-gray-400 text-xs mt-2">(Install chart library for visualization)</p>
              </div>
            </div>
            <div class="flex justify-between text-sm">
              <span class="text-gray-600">Physical Rating</span>
              <span class="font-medium text-green-600">Excellent</span>
            </div>
          </div>
        </div>

        <!-- Body Fat Percentage Chart -->
        <div class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden">
          <div class="border-b border-gray-200 p-6">
            <div class="flex items-center gap-3">
              <div class="p-2 bg-yellow-100 rounded-lg">
                <svg class="h-5 w-5 text-yellow-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <circle cx="12" cy="12" r="1"></circle>
                  <path d="M12 1v6m4.22-4.22-4.24 4.24M12 23v-6m4.22 4.22-4.24-4.24M23 12h-6m4.22 4.22-4.24-4.24M1 12h6M4.22 16.22l4.24-4.24"></path>
                </svg>
              </div>
              <div>
                <h3 class="font-bold text-gray-900">Body Fat %</h3>
                <p class="text-sm text-gray-600">Fat composition trend</p>
              </div>
            </div>
          </div>
          <div class="p-6">
            <div class="h-64 flex items-center justify-center bg-gray-50 rounded-lg border border-gray-200">
              <div class="text-center">
                <p class="text-gray-500 text-sm">📊 Chart will display here</p>
                <p class="text-gray-400 text-xs mt-2">(Install chart library for visualization)</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Health Zones Reference -->
        <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
          <h3 class="font-bold text-gray-900 mb-1">Health Zones</h3>
          <p class="text-sm text-gray-600 mb-6">Your current status across metrics</p>

          <div class="space-y-6">
            <!-- BMI Zone Indicator -->
            <div class="space-y-2">
              <div class="flex justify-between items-center">
                <span class="text-sm font-medium text-gray-700">BMI Status</span>
                <span :class="[
                  'inline-block px-3 py-1 rounded-full text-xs font-semibold text-white',
                  bmiZone.badgeColor
                ]">
                  {{ bmiZone.label }}
                </span>
              </div>
              <div class="w-full bg-gray-200 rounded-full h-2">
                <div :class="bmiZone.barColor + ' h-2 rounded-full transition-all duration-300'"
                  :style="{ width: `${Math.min((currentData.bmi / 30) * 100, 100)}%` }">
                </div>
              </div>
              <div class="flex justify-between text-xs text-gray-600">
                <span>Underweight</span>
                <span>Normal</span>
                <span>Overweight</span>
              </div>
            </div>

            <!-- Body Fat Zone Indicator -->
            <div class="space-y-2">
              <div class="flex justify-between items-center">
                <span class="text-sm font-medium text-gray-700">Body Fat Status</span>
                <span :class="[
                  'inline-block px-3 py-1 rounded-full text-xs font-semibold text-white',
                  bodyFatZone.badgeColor
                ]">
                  {{ bodyFatZone.label }}
                </span>
              </div>
              <div class="relative">
                <div class="w-full h-3 bg-gray-200 rounded-full overflow-hidden flex">
                  <div class="h-full bg-blue-500" style="width: 20%"></div>
                  <div class="h-full bg-green-500" style="width: 20%"></div>
                  <div class="h-full bg-yellow-500" style="width: 25%"></div>
                  <div class="h-full bg-orange-500" style="width: 35%"></div>
                </div>
                <div
                  class="absolute top-1/2 -translate-y-1/2 w-4 h-4 bg-white border-2 border-gray-800 rounded-full shadow-md transition-all"
                  :style="{ left: `calc(${Math.min((currentData.bodyFat / 30) * 100, 100)}% - 8px)` }"
                  :title="`${currentData.bodyFat}%`"
                ></div>
              </div>
              <div class="flex justify-between text-xs text-gray-600">
                <span>&lt;14%</span>
                <span>14-18%</span>
                <span>18-25%</span>
                <span>&gt;25%</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Personal Data Export -->
      <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-lg shadow border border-green-200">
        <div class="border-b border-green-200 p-6">
          <h3 class="font-bold text-gray-900 mb-1">Export Your Data</h3>
          <p class="text-sm text-gray-600">Download your personal body composition records</p>
        </div>

        <div class="p-6 space-y-6">
          <!-- Privacy Notice -->
          <div class="p-4 bg-white rounded-lg border border-green-100">
            <p class="text-sm text-green-700">
              <span class="font-semibold">Personal Data Only:</span> You can only export your own body composition records. This data is private and personal to you.
            </p>
          </div>

          <!-- Date Range Selection -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="space-y-2">
              <label class="text-sm font-medium text-gray-700">Start Date</label>
              <input
                v-model="startDate"
                type="date"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500 bg-white"
              />
            </div>
            <div class="space-y-2">
              <label class="text-sm font-medium text-gray-700">End Date</label>
              <input
                v-model="endDate"
                type="date"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500 bg-white"
              />
            </div>
          </div>

          <!-- Export Format Options -->
          <div>
            <p class="text-sm font-medium text-gray-700 mb-3">Select Download Format</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
              <button
                @click="handleExport('csv')"
                class="flex items-center justify-center gap-2 px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-semibold rounded-lg transition-all duration-200"
              >
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                  <polyline points="7 10 12 15 17 10"></polyline>
                  <line x1="12" y1="15" x2="12" y2="3"></line>
                </svg>
                Download as CSV
              </button>
              <button
                @click="handleExport('pdf')"
                class="flex items-center justify-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700 text-white font-semibold rounded-lg transition-all duration-200"
              >
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path>
                  <polyline points="13 2 13 9 20 9"></polyline>
                </svg>
                Print as PDF
              </button>
            </div>
          </div>

          <!-- Records Preview -->
          <div class="border-t border-green-200 pt-4">
            <p class="text-sm font-medium text-gray-700 mb-3">Records in Date Range</p>
            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
              <div class="overflow-x-auto">
                <table class="w-full text-sm">
                  <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                      <th class="text-left py-3 px-4 font-semibold text-gray-700">Date</th>
                      <th class="text-left py-3 px-4 font-semibold text-gray-700">Weight</th>
                      <th class="text-left py-3 px-4 font-semibold text-gray-700">BMI</th>
                      <th class="text-left py-3 px-4 font-semibold text-gray-700">Body Fat %</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="record in filteredRecords.slice(0, 5)" :key="record.id" class="border-b border-gray-200 hover:bg-gray-50">
                      <td class="py-3 px-4">{{ record.date }}</td>
                      <td class="py-3 px-4">{{ record.weight }} kg</td>
                      <td class="py-3 px-4">{{ record.bmi }}</td>
                      <td class="py-3 px-4">{{ record.bodyFat }}%</td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div class="p-3 bg-gray-50 text-xs text-gray-600 border-t border-gray-200">
                {{ filteredRecords.length }} records found in selected date range
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Health Recommendations -->
      <div class="bg-gradient-to-br from-green-50 to-blue-50 rounded-lg shadow border border-green-200">
        <div class="border-b border-green-200 p-6">
          <div class="flex items-center gap-3">
            <div class="p-2 bg-green-100 rounded-lg">
              <svg class="h-5 w-5 text-green-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
              </svg>
            </div>
            <div>
              <h3 class="font-bold text-gray-900">Health Recommendations</h3>
              <p class="text-sm text-gray-600">Personalized suggestions based on your metrics</p>
            </div>
          </div>
        </div>

        <div class="p-6">
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
            <div class="flex items-start gap-3 p-4 bg-white rounded-lg border border-green-100 hover:shadow-sm transition-shadow">
              <div class="w-2 h-2 rounded-full bg-green-600 mt-2 flex-shrink-0"></div>
              <div>
                <p class="font-medium text-sm text-gray-900">Stay Hydrated</p>
                <p class="text-xs text-gray-600 mt-1">Aim for 8 glasses of water daily to maintain optimal body function.</p>
              </div>
            </div>
            <div class="flex items-start gap-3 p-4 bg-white rounded-lg border border-green-100 hover:shadow-sm transition-shadow">
              <div class="w-2 h-2 rounded-full bg-green-600 mt-2 flex-shrink-0"></div>
              <div>
                <p class="font-medium text-sm text-gray-900">Maintain Your Progress</p>
                <p class="text-xs text-gray-600 mt-1">You're on track with your weight goals. Keep up the great work!</p>
              </div>
            </div>
            <div class="flex items-start gap-3 p-4 bg-white rounded-lg border border-green-100 hover:shadow-sm transition-shadow">
              <div class="w-2 h-2 rounded-full bg-green-600 mt-2 flex-shrink-0"></div>
              <div>
                <p class="font-medium text-sm text-gray-900">Regular Exercise</p>
                <p class="text-xs text-gray-600 mt-1">30 minutes of moderate activity helps maintain muscle mass.</p>
              </div>
            </div>
            <div class="flex items-start gap-3 p-4 bg-white rounded-lg border border-blue-100 hover:shadow-sm transition-shadow">
              <div class="w-2 h-2 rounded-full bg-blue-600 mt-2 flex-shrink-0"></div>
              <div>
                <p class="font-medium text-sm text-gray-900">Sleep Quality</p>
                <p class="text-xs text-gray-600 mt-1">Aim for 7-9 hours of quality sleep to support recovery.</p>
              </div>
            </div>
            <div class="flex items-start gap-3 p-4 bg-white rounded-lg border border-blue-100 hover:shadow-sm transition-shadow">
              <div class="w-2 h-2 rounded-full bg-blue-600 mt-2 flex-shrink-0"></div>
              <div>
                <p class="font-medium text-sm text-gray-900">Balanced Nutrition</p>
                <p class="text-xs text-gray-600 mt-1">Focus on whole foods, proteins, and healthy carbohydrates.</p>
              </div>
            </div>
            <div class="flex items-start gap-3 p-4 bg-white rounded-lg border border-blue-100 hover:shadow-sm transition-shadow">
              <div class="w-2 h-2 rounded-full bg-blue-600 mt-2 flex-shrink-0"></div>
              <div>
                <p class="font-medium text-sm text-gray-900">Track Progress</p>
                <p class="text-xs text-gray-600 mt-1">Update your measurements weekly for better insights.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const timeRange = ref('1M')
const startDate = ref('2025-01-10')
const endDate = ref('2025-01-15')

// Data for different time ranges
const dataByRange = {
  '1W': [
    { date: 'Mon', weight: 72.8, bmi: 23.5, bodyFat: 18.2, muscleMass: 54.0 },
    { date: 'Tue', weight: 72.7, bmi: 23.4, bodyFat: 18.1, muscleMass: 54.1 },
    { date: 'Wed', weight: 72.6, bmi: 23.4, bodyFat: 18.0, muscleMass: 54.2 },
    { date: 'Thu', weight: 72.5, bmi: 23.3, bodyFat: 17.9, muscleMass: 54.2 },
    { date: 'Fri', weight: 72.5, bmi: 23.3, bodyFat: 17.9, muscleMass: 54.3 },
    { date: 'Sat', weight: 72.4, bmi: 23.3, bodyFat: 17.8, muscleMass: 54.3 },
    { date: 'Sun', weight: 72.3, bmi: 23.2, bodyFat: 17.8, muscleMass: 54.4 },
  ],
  '1M': [
    { date: 'Week 1', weight: 75.0, bmi: 24.1, bodyFat: 19.5, muscleMass: 53.0 },
    { date: 'Week 2', weight: 74.2, bmi: 23.9, bodyFat: 19.0, muscleMass: 53.5 },
    { date: 'Week 3', weight: 73.5, bmi: 23.6, bodyFat: 18.5, muscleMass: 53.8 },
    { date: 'Week 4', weight: 72.3, bmi: 23.2, bodyFat: 17.8, muscleMass: 54.4 },
  ],
  '3M': [
    { date: 'Oct', weight: 78.0, bmi: 25.1, bodyFat: 21.5, muscleMass: 51.5 },
    { date: 'Nov', weight: 76.0, bmi: 24.5, bodyFat: 20.2, muscleMass: 52.5 },
    { date: 'Dec', weight: 74.0, bmi: 23.8, bodyFat: 19.0, muscleMass: 53.5 },
    { date: 'Jan', weight: 72.3, bmi: 23.2, bodyFat: 17.8, muscleMass: 54.4 },
  ],
  '6M': [
    { date: 'Aug', weight: 82.0, bmi: 26.4, bodyFat: 24.0, muscleMass: 50.0 },
    { date: 'Sep', weight: 80.0, bmi: 25.7, bodyFat: 22.8, muscleMass: 51.0 },
    { date: 'Oct', weight: 78.0, bmi: 25.1, bodyFat: 21.5, muscleMass: 51.5 },
    { date: 'Nov', weight: 76.0, bmi: 24.5, bodyFat: 20.2, muscleMass: 52.5 },
    { date: 'Dec', weight: 74.0, bmi: 23.8, bodyFat: 19.0, muscleMass: 53.5 },
    { date: 'Jan', weight: 72.3, bmi: 23.2, bodyFat: 17.8, muscleMass: 54.4 },
  ],
}

// Mock body composition records
const bodyCompositionRecords = [
  { id: 1, date: '2025-01-15', weight: 72.3, bmi: 23.2, bodyFat: 17.8, muscleMass: 54.4, visceralFat: 8.0 },
  { id: 2, date: '2025-01-14', weight: 72.5, bmi: 23.3, bodyFat: 17.9, muscleMass: 54.3, visceralFat: 8.2 },
  { id: 3, date: '2025-01-13', weight: 72.6, bmi: 23.4, bodyFat: 18.0, muscleMass: 54.2, visceralFat: 8.3 },
  { id: 4, date: '2025-01-12', weight: 72.8, bmi: 23.5, bodyFat: 18.2, muscleMass: 54.0, visceralFat: 8.5 },
  { id: 5, date: '2025-01-11', weight: 73.0, bmi: 23.6, bodyFat: 18.3, muscleMass: 53.9, visceralFat: 8.6 },
  { id: 6, date: '2025-01-10', weight: 73.1, bmi: 23.7, bodyFat: 18.4, muscleMass: 53.8, visceralFat: 8.7 },
]

// Computed properties
const data = computed(() => dataByRange[timeRange.value])
const currentData = computed(() => data.value[data.value.length - 1])
const previousData = computed(() => data.value[0])

const weightChange = computed(() => {
  const curr = currentData.value.weight
  const prev = previousData.value.weight
  return parseFloat((curr - prev).toFixed(2))
})

const weightChangePercent = computed(() => {
  return ((weightChange.value / previousData.value.weight) * 100).toFixed(1)
})

const bmiChange = computed(() => {
  const curr = currentData.value.bmi
  const prev = previousData.value.bmi
  return parseFloat((curr - prev).toFixed(2))
})

const bodyFatChange = computed(() => {
  const curr = currentData.value.bodyFat
  const prev = previousData.value.bodyFat
  return parseFloat((curr - prev).toFixed(2))
})

const muscleMassChange = computed(() => {
  const curr = currentData.value.muscleMass
  const prev = previousData.value.muscleMass
  return parseFloat((curr - prev).toFixed(2))
})

const getBMIZone = (bmi) => {
  if (bmi < 18.5) return { label: 'Underweight', badgeColor: 'bg-blue-600', barColor: 'bg-blue-500' }
  if (bmi < 25) return { label: 'Normal Weight', badgeColor: 'bg-green-600', barColor: 'bg-green-500' }
  if (bmi < 30) return { label: 'Overweight', badgeColor: 'bg-yellow-600', barColor: 'bg-yellow-500' }
  return { label: 'Obese', badgeColor: 'bg-red-600', barColor: 'bg-red-500' }
}

const getBodyFatZone = (bodyFat) => {
  if (bodyFat < 14) return { label: 'Essential', badgeColor: 'bg-blue-600' }
  if (bodyFat < 18) return { label: 'Athletes', badgeColor: 'bg-green-600' }
  if (bodyFat < 25) return { label: 'Fitness', badgeColor: 'bg-green-600' }
  return { label: 'Average', badgeColor: 'bg-yellow-600' }
}

const bmiZone = computed(() => getBMIZone(currentData.value.bmi))
const bodyFatZone = computed(() => getBodyFatZone(currentData.value.bodyFat))

const filteredRecords = computed(() => {
  return bodyCompositionRecords.filter(record => {
    return record.date >= startDate.value && record.date <= endDate.value
  })
})

// Methods
const handleExport = (format) => {
  if (filteredRecords.value.length === 0) {
    alert('No records found in the selected date range.')
    return
  }

  if (format === 'csv') {
    exportAsCSV(filteredRecords.value)
  } else if (format === 'pdf') {
    exportAsPDF(filteredRecords.value)
  }
}

const exportAsCSV = (records) => {
  const headers = ['Date', 'Weight (kg)', 'BMI (kg/m²)', 'Body Fat (%)', 'Muscle Mass (kg)', 'Visceral Fat']
  const rows = records.map(record => [
    record.date,
    record.weight,
    record.bmi,
    record.bodyFat,
    record.muscleMass,
    record.visceralFat
  ])

  const csvContent = [
    headers.join(','),
    ...rows.map(row => row.join(','))
  ].join('\n')

  const blob = new Blob([csvContent], { type: 'text/csv' })
  const url = window.URL.createObjectURL(blob)
  const a = document.createElement('a')
  a.href = url
  a.download = `body-composition-${startDate.value}-to-${endDate.value}.csv`
  document.body.appendChild(a)
  a.click()
  window.URL.revokeObjectURL(url)
  document.body.removeChild(a)
}

const exportAsPDF = (records) => {
  let pdfContent = `
  <html>
    <head>
      <title>Body Composition Report</title>
      <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { color: #16a34a; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background-color: #f3f4f6; padding: 10px; border: 1px solid #d1d5db; text-align: left; }
        td { padding: 10px; border: 1px solid #d1d5db; }
        .summary { background-color: #f0fdf4; padding: 15px; border-radius: 8px; margin-bottom: 20px; }
        .footer { margin-top: 30px; font-size: 12px; color: #6b7280; }
      </style>
    </head>
    <body>
      <h1>Body Composition Report</h1>
      <div class="summary">
        <p><strong>Report Period:</strong> ${startDate.value} to ${endDate.value}</p>
        <p><strong>Total Records:</strong> ${records.length}</p>
        <p><strong>Generated:</strong> ${new Date().toLocaleDateString()}</p>
      </div>
      <table>
        <thead>
          <tr>
            <th>Date</th>
            <th>Weight (kg)</th>
            <th>BMI (kg/m²)</th>
            <th>Body Fat (%)</th>
            <th>Muscle Mass (kg)</th>
            <th>Visceral Fat</th>
          </tr>
        </thead>
        <tbody>
          ${records.map(record => `
            <tr>
              <td>${record.date}</td>
              <td>${record.weight}</td>
              <td>${record.bmi}</td>
              <td>${record.bodyFat}</td>
              <td>${record.muscleMass}</td>
              <td>${record.visceralFat}</td>
            </tr>
          `).join('')}
        </tbody>
      </table>
      <div class="footer">
        <p>This is a personal health record. Keep it private and secure.</p>
      </div>
    </body>
  </html>
  `

  const printWindow = window.open('', '', 'height=400,width=800')
  if (printWindow) {
    printWindow.document.write(pdfContent)
    printWindow.document.close()
    printWindow.print()
  }
}
</script>

<style scoped>
/* Smooth transitions */
button {
  transition: all 0.2s ease;
}

/* Responsive adjustments */
@media (max-width: 640px) {
  :deep(.grid) {
    grid-template-columns: 1fr;
  }
}
</style>
