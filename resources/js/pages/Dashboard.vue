<template>
  <div class="space-y-6">
    <!-- Header with Time Range Selector -->
    <div class="bg-gradient-to-r from-gray-50 rounded-lg shadow border border-gray-200">
      <div class="p-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
          <div>
            <h1 class="text-3xl font-bold text-gray-900">Health Dashboard</h1>
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
              currentData.weight_kg < previousData.weight_kg ? 'bg-green-100' : 'bg-orange-100'
            ]">
              <svg
                class="h-5 w-5"
                :class="currentData.weight_kg < previousData.weight_kg ? 'text-green-600' : 'text-orange-600'"
                viewBox="0 0 48 48"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path
                  fill-rule="evenodd"
                  clip-rule="evenodd"
                  d="M35.8513 16.2506C36.5933 17.0688 36.3659 18.3263 35.4616 18.9606L30.5893 22.3781C29.685 23.0124 28.4495 22.7564 27.556 22.1071C27.3273 21.9408 27.0865 21.7904 26.8353 21.6571C25.9922 21.2096 25.0551 20.9681 24.1007 20.9522C23.1463 20.9363 22.2016 21.1466 21.3441 21.5657C21.0885 21.6906 20.8429 21.8329 20.6088 21.9915C19.6942 22.6108 18.4509 22.8255 17.5682 22.1615L12.8123 18.5837C11.9296 17.9197 11.7441 16.6553 12.513 15.8623C13.7981 14.5369 15.3066 13.4401 16.9744 12.625C19.2429 11.5163 21.7416 10.9602 24.2662 11.0022C26.7908 11.0442 29.2697 11.6831 31.5 12.8667C33.1397 13.7368 34.6109 14.8832 35.8513 16.2506ZM34.1956 17.4057C33.1476 16.2921 31.9212 15.3544 30.5625 14.6334C28.611 13.5977 26.442 13.0387 24.2329 13.0019C22.0239 12.9652 19.8375 13.4518 17.8526 14.4219C16.4707 15.0973 15.2137 15.9937 14.1293 17.0718L18.7666 20.5603C18.7816 20.5652 18.8205 20.5727 18.8976 20.5657C19.0544 20.5516 19.2744 20.4796 19.4875 20.3354C19.799 20.1244 20.1259 19.935 20.4658 19.7688C21.607 19.2111 22.864 18.9314 24.1339 18.9525C24.185 18.9533 24.236 18.9547 24.2869 18.9565C24.3251 18.8764 24.3746 18.8 24.4357 18.7298L27.1821 15.5719C27.5446 15.1552 28.1762 15.1112 28.5929 15.4736C29.0097 15.8361 29.0537 16.4677 28.6912 16.8844L26.5368 19.3616C26.9615 19.5026 27.375 19.6793 27.7728 19.8904C28.1071 20.0678 28.4275 20.268 28.7318 20.4892C28.9399 20.6405 29.1575 20.7198 29.3137 20.739C29.3905 20.7485 29.4297 20.7423 29.4448 20.7379L34.1956 17.4057ZM29.4525 20.7347C29.4528 20.7349 29.45 20.7364 29.4448 20.7379C29.4492 20.7354 29.4523 20.7344 29.4525 20.7347ZM18.759 20.5567C18.7592 20.5565 18.7623 20.5576 18.7666 20.5603C18.7615 20.5585 18.7587 20.557 18.759 20.5567Z"
                  fill="currentColor"
                />
                <path
                  fill-rule="evenodd"
                  clip-rule="evenodd"
                  d="M36.2161 42C38.339 42 40.0921 40.3416 40.2099 38.2219L41.7655 10.2219C41.8928 7.92908 40.068 6 37.7716 6L10.2284 6C7.93206 6 6.10718 7.92907 6.23456 10.2219L7.79011 38.2219C7.90787 40.3415 9.66102 42 11.7839 42L36.2161 42ZM11.7839 40L36.2161 40C37.2775 40 38.1541 39.1708 38.213 38.1109L39.7685 10.1109C39.8322 8.96454 38.9198 8 37.7716 8L10.2284 8C9.08023 8 8.16779 8.96454 8.23148 10.1109L9.78703 38.1109C9.84591 39.1708 10.7225 40 11.7839 40Z"
                  fill="currentColor"
                />
              </svg>
            </div>
          </div>
          <div class="space-y-2">
            <div class="flex items-baseline gap-1">
              <span class="text-3xl font-bold text-gray-900">{{ displayWeight ?? '-' }}</span>
              <span class="text-gray-500 text-sm">{{ unitStore.weightLabel }}</span>
            </div>
            <div class="flex items-center gap-2">
              <span :class="[
                'text-sm font-medium',
                weightChange <= 0 ? 'text-green-600' : 'text-orange-600'
              ]">
                {{ weightChange <= 0 ? '-' : '+' }}{{ displayWeightChange }} {{ unitStore.weightLabel }}
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
              <svg class="h-5 w-5 text-blue-600" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M39 8H9C8.44771 8 8 8.44772 8 9V39C8 39.5523 8.44772 40 9 40H39C39.5523 40 40 39.5523 40 39V9C40 8.44771 39.5523 8 39 8ZM9 6C7.34315 6 6 7.34315 6 9V39C6 40.6569 7.34315 42 9 42H39C40.6569 42 42 40.6569 42 39V9C42 7.34315 40.6569 6 39 6H9Z" fill="currentColor"/>
                <path fill-rule="evenodd" clip-rule="evenodd" d="M11 20C11 19.4477 11.4477 19 12 19H15.125C16.6467 19 18 20.1674 18 21.75C18 22.4298 17.7503 23.033 17.3448 23.5C17.7503 23.967 18 24.5702 18 25.25C18 26.8326 16.6467 28 15.125 28H12C11.7348 28 11.4804 27.8946 11.2929 27.7071C11.1054 27.5196 11 27.2652 11 27L11 20ZM15.125 22.5C15.6744 22.5 16 22.1004 16 21.75C16 21.3996 15.6744 21 15.125 21H13V22.5H15.125ZM13 24.5H15.125C15.6744 24.5 16 24.8996 16 25.25C16 25.6004 15.6744 26 15.125 26H13V24.5Z" fill="currentColor"/>
                <path fill-rule="evenodd" clip-rule="evenodd" d="M30 20C30 19.4477 30.4477 19 31 19H36C36.5523 19 37 19.4477 37 20C37 20.5523 36.5523 21 36 21H34.5V26H36C36.5523 26 37 26.4477 37 27C37 27.5523 36.5523 28 36 28H31C30.4477 28 30 27.5523 30 27C30 26.4477 30.4477 26 31 26H32.5V21H31C30.4477 21 30 20.5523 30 20Z" fill="currentColor"/>
                <path fill-rule="evenodd" clip-rule="evenodd" d="M22 22.7033L22 27C22 27.5523 21.5523 28 21 28C20.4477 28 20 27.5523 20 27L20 20C20 19.5815 20.2606 19.2073 20.6531 19.0621C21.0455 18.9169 21.4869 19.0315 21.7593 19.3492L24 21.9634L26.2407 19.3492C26.5131 19.0315 26.9545 18.9169 27.3469 19.0621C27.7394 19.2073 28 19.5815 28 20L28 27C28 27.5523 27.5523 28 27 28C26.4477 28 26 27.5523 26 27L26 22.7033L24.7593 24.1508C24.5693 24.3724 24.2919 24.5 24 24.5C23.7081 24.5 23.4307 24.3724 23.2407 24.1508L22 22.7033Z" fill="currentColor"/>
              </svg>
            </div>
          </div>
          <div class="space-y-2">
            <div class="flex items-baseline gap-1">
              <span class="text-3xl font-bold text-gray-900">{{ computedBMI.toFixed(1) }}</span>
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
            <h3 class="text-sm font-semibold text-gray-700">Body Fat %</h3>
            <div class="p-2 bg-yellow-100 rounded-lg">
              <svg class="h-5 w-5 text-yellow-600" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M24 12C25.6569 12 27 10.6569 27 9C27 7.34315 25.6569 6 24 6C22.3431 6 21 7.34315 21 9C21 10.6569 22.3431 12 24 12ZM24 14C26.7614 14 29 11.7614 29 9C29 6.23858 26.7614 4 24 4C21.2386 4 19 6.23858 19 9C19 11.7614 21.2386 14 24 14Z" fill="currentColor"/>
                <path fill-rule="evenodd" clip-rule="evenodd" d="M17.374 18.3144C18.3174 18.494 19 19.3188 19 20.2792L19 41C19 41.5523 19.4477 42 20 42H20.0868C20.604 42 21.0359 41.6056 21.0827 41.0905L22 31C22.0029 29.8975 22.8975 29.0052 24 29.0052C25.1025 29.0052 25.9971 29.8974 26 30.9999L26.9173 41.0905C26.9641 41.6056 27.396 42 27.9132 42H28C28.5523 42 29 41.5523 29 41V20.3169C29 19.3564 29.6828 18.5316 30.6264 18.3521C32.3824 18.0182 34.2392 17.5548 36.2798 16.9601C36.8101 16.8056 37.1146 16.2505 36.9601 15.7202C36.8056 15.19 36.2505 14.8855 35.7202 15.04C31.0861 16.3906 27.5307 17.0156 24.0043 16.9998C20.4743 16.9839 16.9146 16.3259 12.2674 15.0365C11.7352 14.8888 11.1841 15.2005 11.0364 15.7327C10.8888 16.2649 11.2005 16.816 11.7327 16.9636C13.7658 17.5278 15.6186 17.9803 17.374 18.3144ZM22.9779 41.8025C22.6245 43.0798 21.4552 44 20.0868 44H20C18.3431 44 17 42.6569 17 41L17 20.2792C15.1742 19.9317 13.2653 19.4645 11.1979 18.8908C9.60138 18.4478 8.66625 16.7945 9.10925 15.1979C9.55225 13.6014 11.2056 12.6663 12.8021 13.1093C17.3715 14.3772 20.7371 14.9851 24.0132 14.9998C27.2788 15.0144 30.6314 14.4399 35.1606 13.1199C36.7513 12.6563 38.4166 13.57 38.8802 15.1606C39.3438 16.7513 38.4301 18.4166 36.8394 18.8802C34.7552 19.4876 32.8345 19.9681 31 20.3169V41C31 42.6569 29.6569 44 28 44H27.9132C26.5448 44 25.3755 43.0798 25.0221 41.8025C24.9897 41.6851 24.9641 41.5647 24.9458 41.4417C24.9375 41.3856 24.9307 41.3288 24.9255 41.2716L24.0082 31.1811C24.0029 31.1223 24.0002 31.0637 24 31.0052C23.9998 31.0637 23.9971 31.1223 23.9918 31.1811L23.0745 41.2716C23.0693 41.3288 23.0625 41.3856 23.0542 41.4417C23.0359 41.5647 23.0103 41.6851 22.9779 41.8025Z" fill="currentColor"/>
              </svg>
            </div>
          </div>
          <div class="space-y-2">
            <div class="flex items-baseline gap-1">
              <span class="text-3xl font-bold text-gray-900">{{ (currentData.body_fat_percent || 0).toFixed(1) }}</span>
              <span class="text-gray-500 text-sm">%</span>
            </div>
            <div class="flex items-center gap-2">
              <span class="text-sm font-medium text-yellow-600">
                {{ bodyFatChange <= 0 ? '-' : '+' }}{{ Math.abs(bodyFatChange).toFixed(1) }}%
              </span>
            </div>
          </div>
        </div>

        <!-- Muscle Mass Card -->
        <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-gray-700">Muscle Mass</h3>
            <div class="p-2 bg-green-100 rounded-lg">
              <svg class="h-5 w-5 text-green-600" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M31 14C31 13.4477 31.4477 13 32 13H36C36.5523 13 37 13.4477 37 14V16H40C40.5523 16 41 16.4477 41 17V23H44V25H41V31C41 31.5523 40.5523 32 40 32H37V34C37 34.5523 36.5523 35 36 35H32C31.4477 35 31 34.5523 31 34V25H17V34C17 34.5523 16.5523 35 16 35H12C11.4477 35 11 34.5523 11 34V32H8C7.44772 32 7 31.5523 7 31V25H4V23H7V17C7 16.4477 7.44772 16 8 16H11V14C11 13.4477 11.4477 13 12 13H16C16.5523 13 17 13.4477 17 14V23H31V14ZM13 33H15V15H13V33ZM11 18H9V30H11V18ZM37 30V18H39V30H37ZM35 15V33H33V15H35Z" fill="currentColor"/>
              </svg>
            </div>
          </div>
          <div class="space-y-2">
            <div class="flex items-baseline gap-1">
              <span class="text-3xl font-bold text-gray-900">{{ displayMuscle ?? '-' }}</span>
              <span class="text-gray-500 text-sm">{{ unitStore.weightLabel }}</span>
            </div>
            <div class="flex items-center gap-2">
              <span class="text-sm font-medium text-green-600">
                {{ muscleMassChange >= 0 ? '+' : '-' }}{{ displayMuscleMassChange }} {{ unitStore.weightLabel }}
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
              <div :class="[
                'p-2 rounded-lg',
                weightChange <= 0 ? 'bg-green-100' : 'bg-orange-100'
              ]">
                <svg
                  v-if="weightChange <= 0"
                  class="h-5 w-5 text-green-600"
                  xmlns="http://www.w3.org/2000/svg"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                >
                  <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                  <polyline points="17 18 23 18 23 12"></polyline>
                </svg>
                <svg
                  v-else
                  class="h-5 w-5 text-orange-600"
                  xmlns="http://www.w3.org/2000/svg"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                >
                  <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                  <polyline points="17 6 23 6 23 12"></polyline>
                </svg>
              </div>
              <div>
                <h3 class="font-bold text-gray-900">Weight Trend</h3>
                <p class="text-sm text-gray-600">Weight progress over selected period</p>
              </div>
            </div>
          </div>
          <div class="p-6">
            <MetricLineChart
              :labels="weightChart.labels"
              :values="weightChart.values"
              label="Weight"
              color="#16a34a"
              fill-color="rgba(34, 197, 94, 0.18)"
              :unit="unitStore.weightLabel"
              empty-title="No weight records in this time range"
              empty-subtitle="Add more measurements to see your weight trend"
            />
          </div>
        </div>

        <!-- BMI Trend Chart removed as per user request -->

        <!-- Muscle Mass Trend -->
        <div class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden">
          <div class="border-b border-gray-200 p-6">
            <div class="flex items-center gap-3">
            <div class="p-2 bg-green-100 rounded-lg">
              <svg class="h-5 w-5 text-green-600" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M31 14C31 13.4477 31.4477 13 32 13H36C36.5523 13 37 13.4477 37 14V16H40C40.5523 16 41 16.4477 41 17V23H44V25H41V31C41 31.5523 40.5523 32 40 32H37V34C37 34.5523 36.5523 35 36 35H32C31.4477 35 31 34.5523 31 34V25H17V34C17 34.5523 16.5523 35 16 35H12C11.4477 35 11 34.5523 11 34V32H8C7.44772 32 7 31.5523 7 31V25H4V23H7V17C7 16.4477 7.44772 16 8 16H11V14C11 13.4477 11.4477 13 12 13H16C16.5523 13 17 13.4477 17 14V23H31V14ZM13 33H15V15H13V33ZM11 18H9V30H11V18ZM37 30V18H39V30H37ZM35 15V33H33V15H35Z" fill="currentColor"/>
              </svg>
            </div>
              <div>
                <h3 class="font-bold text-gray-900">Muscle Mass</h3>
                <p class="text-sm text-gray-600">Lean muscle growth tracking</p>
              </div>
            </div>
          </div>
          <div class="p-6">
            <MetricLineChart
              :labels="muscleChart.labels"
              :values="muscleChart.values"
              label="Muscle Mass"
              color="#059669"
              fill-color="rgba(16, 185, 129, 0.18)"
              :unit="unitStore.weightLabel"
              empty-title="No muscle mass records in this time range"
              empty-subtitle="Log muscle measurements to view this chart"
            />
            <div class="flex justify-between text-sm">
              <span class="text-gray-600">Physical Rating</span>
              <span class="font-medium text-green-600">{{ currentData.physical_rating || '-' }}</span>
            </div>
          </div>
        </div>

        <!-- Body Fat Percentage Chart -->
        <div class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden">
          <div class="border-b border-gray-200 p-6">
            <div class="flex items-center gap-3">
            <div class="p-2 bg-yellow-100 rounded-lg">
              <svg class="h-5 w-5 text-yellow-600" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M24 12C25.6569 12 27 10.6569 27 9C27 7.34315 25.6569 6 24 6C22.3431 6 21 7.34315 21 9C21 10.6569 22.3431 12 24 12ZM24 14C26.7614 14 29 11.7614 29 9C29 6.23858 26.7614 4 24 4C21.2386 4 19 6.23858 19 9C19 11.7614 21.2386 14 24 14Z" fill="currentColor"/>
                <path fill-rule="evenodd" clip-rule="evenodd" d="M17.374 18.3144C18.3174 18.494 19 19.3188 19 20.2792L19 41C19 41.5523 19.4477 42 20 42H20.0868C20.604 42 21.0359 41.6056 21.0827 41.0905L22 31C22.0029 29.8975 22.8975 29.0052 24 29.0052C25.1025 29.0052 25.9971 29.8974 26 30.9999L26.9173 41.0905C26.9641 41.6056 27.396 42 27.9132 42H28C28.5523 42 29 41.5523 29 41V20.3169C29 19.3564 29.6828 18.5316 30.6264 18.3521C32.3824 18.0182 34.2392 17.5548 36.2798 16.9601C36.8101 16.8056 37.1146 16.2505 36.9601 15.7202C36.8056 15.19 36.2505 14.8855 35.7202 15.04C31.0861 16.3906 27.5307 17.0156 24.0043 16.9998C20.4743 16.9839 16.9146 16.3259 12.2674 15.0365C11.7352 14.8888 11.1841 15.2005 11.0364 15.7327C10.8888 16.2649 11.2005 16.816 11.7327 16.9636C13.7658 17.5278 15.6186 17.9803 17.374 18.3144ZM22.9779 41.8025C22.6245 43.0798 21.4552 44 20.0868 44H20C18.3431 44 17 42.6569 17 41L17 20.2792C15.1742 19.9317 13.2653 19.4645 11.1979 18.8908C9.60138 18.4478 8.66625 16.7945 9.10925 15.1979C9.55225 13.6014 11.2056 12.6663 12.8021 13.1093C17.3715 14.3772 20.7371 14.9851 24.0132 14.9998C27.2788 15.0144 30.6314 14.4399 35.1606 13.1199C36.7513 12.6563 38.4166 13.57 38.8802 15.1606C39.3438 16.7513 38.4301 18.4166 36.8394 18.8802C34.7552 19.4876 32.8345 19.9681 31 20.3169V41C31 42.6569 29.6569 44 28 44H27.9132C26.5448 44 25.3755 43.0798 25.0221 41.8025C24.9897 41.6851 24.9641 41.5647 24.9458 41.4417C24.9375 41.3856 24.9307 41.3288 24.9255 41.2716L24.0082 31.1811C24.0029 31.1223 24.0002 31.0637 24 31.0052C23.9998 31.0637 23.9971 31.1223 23.9918 31.1811L23.0745 41.2716C23.0693 41.3288 23.0625 41.3856 23.0542 41.4417C23.0359 41.5647 23.0103 41.6851 22.9779 41.8025Z" fill="currentColor"/>
              </svg>
            </div>
              <div>
                <h3 class="font-bold text-gray-900">Body Fat %</h3>
                <p class="text-sm text-gray-600">Fat composition trend</p>
              </div>
            </div>
          </div>
          <div class="p-6">
            <MetricLineChart
              :labels="bodyFatChart.labels"
              :values="bodyFatChart.values"
              label="Body Fat"
              color="#d97706"
              fill-color="rgba(245, 158, 11, 0.18)"
              unit="%"
              empty-title="No body fat records in this time range"
              empty-subtitle="Record body fat measurements to view the trend"
            />
          </div>
        </div>

        <!-- Health Zones Reference -->
        <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
          <div class="flex items-center gap-3 mb-6">
            <div class="p-2 bg-sky-100 rounded-lg">
              <svg class="h-5 w-5 text-sky-600" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M19 15V18H16V20H19V23H21V20H24V18H21V15H19Z" fill="currentColor"/>
                <path d="M17 26C16.4477 26 16 26.4477 16 27C16 27.5523 16.4477 28 17 28H31C31.5523 28 32 27.5523 32 27C32 26.4477 31.5523 26 31 26H17Z" fill="currentColor"/>
                <path d="M16 32C16 31.4477 16.4477 31 17 31H31C31.5523 31 32 31.4477 32 32C32 32.5523 31.5523 33 31 33H17C16.4477 33 16 32.5523 16 32Z" fill="currentColor"/>
                <path d="M17 36C16.4477 36 16 36.4477 16 37C16 37.5523 16.4477 38 17 38H31C31.5523 38 32 37.5523 32 37C32 36.4477 31.5523 36 31 36H17Z" fill="currentColor"/>
                <path fill-rule="evenodd" clip-rule="evenodd" d="M17 7C17 5.34315 18.3431 4 20 4H28C29.6569 4 31 5.34315 31 7H35C36.6569 7 38 8.34315 38 10V41C38 42.6569 36.6569 44 35 44H13C11.3431 44 10 42.6569 10 41V10C10 8.34315 11.3431 7 13 7H17ZM28 12C29.6569 12 31 10.6569 31 9H35C35.5523 9 36 9.44772 36 10V41C36 41.5523 35.5523 42 35 42H13C12.4477 42 12 41.5523 12 41V10C12 9.44772 12.4477 9 13 9H17C17 10.6569 18.3431 12 20 12H28ZM20 6C19.4477 6 19 6.44772 19 7V9C19 9.55228 19.4477 10 20 10H28C28.5523 10 29 9.55228 29 9V7C29 6.44772 28.5523 6 28 6H20Z" fill="currentColor"/>
              </svg>
            </div>
            <div>
              <h3 class="font-bold text-gray-900 mb-1">Health Zones</h3>
              <p class="text-sm text-gray-600">Your current status across metrics</p>
            </div>
          </div>

          <div class="space-y-6">
            <!-- BMI Zone Indicator -->
            <div class="space-y-2">
              <div class="flex justify-between items-center">
                <span class="text-sm font-medium text-gray-700">BMI Status</span>
                <div class="flex items-center gap-3">
                  <span class="text-sm font-semibold" :class="bmiZone.textColor">
                    {{ computedBMI.toFixed(1) }} kg/m²
                  </span>
                  <span :class="[
                    'inline-block px-3 py-1 rounded-full text-xs font-semibold text-white',
                    bmiZone.badgeColor
                  ]">
                    {{ bmiZone.label }}
                  </span>
                </div>
              </div>
              <div class="relative">
                <div class="w-full h-3 bg-gray-200 rounded-full overflow-hidden flex">
                  <div
                    v-for="segment in bmiBarSegments"
                    :key="segment.label"
                    class="h-full"
                    :class="segment.color"
                    :style="{ width: segment.width }"
                  ></div>
                  <div
                    class="absolute top-1/2 -translate-y-1/2 w-4 h-4 bg-white border-2 rounded-full shadow-md transition-all"
                    :class="bmiZone.borderColor"
                    :style="{ left: `calc(${bmiMarkerPosition}% - 8px)` }"
                    :title="`BMI: ${computedBMI.toFixed(1)}`"
                  ></div>
                </div>
              </div>
              <div class="flex justify-between text-xs text-gray-600">
                <span
                  v-for="zone in bmiZones"
                  :key="zone.label"
                  :class="[
                    'transition-colors',
                    zone.label === bmiZone.label ? ['font-bold', zone.textColor] : 'text-gray-500'
                  ]"
                >
                  {{ zone.shortLabel }}
                </span>
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
                      <th class="text-left py-3 px-4 font-semibold text-gray-700">Weight ({{ unitStore.weightLabel }})</th>
                      <th class="text-left py-3 px-4 font-semibold text-gray-700">Body Fat %</th>
                      <th class="text-left py-3 px-4 font-semibold text-gray-700">Muscle Mass</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="record in filteredRecords.slice(0, 5)" :key="record.id" class="border-b border-gray-200 hover:bg-gray-50">
                      <td class="py-3 px-4">{{ record.measurement_date }}</td>
                      <td class="py-3 px-4">{{ unitStore.convertWeight(record.weight_kg)?.toFixed(1) || '-' }} {{ unitStore.weightLabel }}</td>
                      <td class="py-3 px-4">{{ record.body_fat_percent?.toFixed(1) || '-' }}%</td>
                      <td class="py-3 px-4">{{ unitStore.convertWeight(record.muscle_mass)?.toFixed(1) || '-' }} {{ unitStore.weightLabel }}</td>
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
    </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import MetricLineChart from '../components/MetricLineChart.vue'
import { getUserProfile } from '../services/authService'
import { useUnitStore } from '../stores/unitStore'

const timeRange = ref('1M')
const startDate = ref('')
const endDate = ref('')
const measurements = ref([])
const loading = ref(true)
const userHeight = ref(null) // in cm

const unitStore = useUnitStore()

// Computed properties
const data = computed(() => {
  const currentDate = new Date()
  let startDateObj

  switch (timeRange.value) {
    case '1W':
      startDateObj = new Date(currentDate)
      startDateObj.setDate(currentDate.getDate() - 7)
      break
    case '1M':
      startDateObj = new Date(currentDate)
      startDateObj.setMonth(currentDate.getMonth() - 1)
      break
    case '3M':
      startDateObj = new Date(currentDate)
      startDateObj.setMonth(currentDate.getMonth() - 3)
      break
    case '6M':
      startDateObj = new Date(currentDate)
      startDateObj.setMonth(currentDate.getMonth() - 6)
      break
    default:
      startDateObj = new Date(currentDate)
      startDateObj.setMonth(currentDate.getMonth() - 1)
  }

  return measurements.value.filter(m => {
    const measurementDate = new Date(m.measurement_date)
    return measurementDate >= startDateObj && measurementDate <= currentDate
  }).sort((a, b) => new Date(a.measurement_date) - new Date(b.measurement_date))
})

const currentData = computed(() => {
  if (data.value.length === 0) {
    return { weight_kg: 0, body_fat_percent: 0, muscle_mass: 0 }
  }
  return data.value[data.value.length - 1]
})

// Compute BMI dynamically using latest weight and user height
const computedBMI = computed(() => {
  const weight = currentData.value.weight_kg || 0
  const heightCm = userHeight.value
  if (!weight || !heightCm) return 0
  const heightM = heightCm / 100
  return weight / (heightM * heightM)
})

const previousData = computed(() => {
  if (data.value.length === 0) {
    return { weight_kg: 0, body_fat_percent: 0, muscle_mass: 0, bmi: 0 }
  }
  return data.value[0]
})

const weightChange = computed(() => {
  const curr = currentData.value.weight_kg
  const prev = previousData.value.weight_kg
  return parseFloat((curr - prev).toFixed(2))
})

const weightChangePercent = computed(() => {
  if (previousData.value.weight_kg === 0) return 0
  return ((weightChange.value / previousData.value.weight_kg) * 100).toFixed(1)
})

// Display-unit computeds
const displayWeight = computed(() => unitStore.convertWeight(currentData.value.weight_kg))
const displayMuscle = computed(() => unitStore.convertWeight(currentData.value.muscle_mass))
const displayWeightChange = computed(() => unitStore.convertWeight(Math.abs(weightChange.value)))
const displayMuscleMassChange = computed(() => unitStore.convertWeight(Math.abs(muscleMassChange.value)))

const bodyFatChange = computed(() => {
  const curr = currentData.value.body_fat_percent
  const prev = previousData.value.body_fat_percent
  return parseFloat((curr - prev).toFixed(2))
})

const muscleMassChange = computed(() => {
  const curr = currentData.value.muscle_mass
  const prev = previousData.value.muscle_mass
  return parseFloat((curr - prev).toFixed(2))
})

const bmiZones = [
  { label: 'Underweight', shortLabel: 'Underweight', min: 0, max: 18.5, color: 'bg-blue-500', badgeColor: 'bg-blue-600', textColor: 'text-blue-700', borderColor: 'border-blue-600' },
  { label: 'Normal Weight', shortLabel: 'Normal', min: 18.5, max: 25, color: 'bg-green-500', badgeColor: 'bg-green-600', textColor: 'text-green-700', borderColor: 'border-green-600' },
  { label: 'Overweight', shortLabel: 'Overweight', min: 25, max: 30, color: 'bg-yellow-500', badgeColor: 'bg-yellow-600', textColor: 'text-yellow-700', borderColor: 'border-yellow-600' },
  { label: 'Obese', shortLabel: 'Obese', min: 30, max: 40, color: 'bg-red-500', badgeColor: 'bg-red-600', textColor: 'text-red-700', borderColor: 'border-red-600' },
]

const getZone = (value, zones) => {
  const matchedZone = zones.find(zone => value >= zone.min && value < zone.max)
  return matchedZone || zones[zones.length - 1]
}

const bmiZone = computed(() => getZone(computedBMI.value || 0, bmiZones))

const filteredRecords = computed(() => {
  if (!startDate.value || !endDate.value) return measurements.value
  return measurements.value.filter(record => {
    return record.measurement_date >= startDate.value && record.measurement_date <= endDate.value
  })
})

const formatChartLabel = (dateString) => {
  return new Date(dateString).toLocaleDateString('en-US', {
    month: 'short',
    day: 'numeric',
  })
}

const buildMetricChart = (key, convert = (v) => v) => {
  const chartRecords = data.value.filter(record => typeof record[key] === 'number' && !Number.isNaN(record[key]))

  return {
    labels: chartRecords.map(record => formatChartLabel(record.measurement_date)),
    values: chartRecords.map(record => Number(convert(record[key]).toFixed(1))),
  }
}

const weightChart = computed(() => buildMetricChart('weight_kg', (v) => unitStore.convertWeight(v)))
const muscleChart = computed(() => buildMetricChart('muscle_mass', (v) => unitStore.convertWeight(v)))
const bodyFatChart = computed(() => buildMetricChart('body_fat_percent'))

const buildZoneSegments = (zones) => {
  const max = zones[zones.length - 1].max

  return zones.map((zone) => ({
    label: zone.label,
    color: zone.color,
    width: `${((zone.max - zone.min) / max) * 100}%`,
  }))
}

const getMarkerPosition = (value, max) => {
  if (!value || value < 0) {
    return 0
  }

  return Math.min((value / max) * 100, 100)
}

const bmiBarSegments = computed(() => buildZoneSegments(bmiZones))

const bmiMarkerPosition = computed(() => getMarkerPosition(computedBMI.value, bmiZones[bmiZones.length - 1].max))

// Methods
const loadMeasurements = async () => {
  try {
    loading.value = true
    const response = await fetch('/api/body-compositions', {
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('auth_token')}`,
      },
    })
    const result = await response.json()
    measurements.value = (result.data || result || []).sort((a, b) =>
      new Date(a.measurement_date) - new Date(b.measurement_date)
    )

    // Set default date range to 6 months from oldest record
    if (measurements.value.length > 0) {
      const oldestDate = new Date(measurements.value[0].measurement_date)
      const today = new Date()

      endDate.value = today.toISOString().split('T')[0]
      startDate.value = oldestDate.toISOString().split('T')[0]
    } else {
      // Default to 6 months if no data
      const today = new Date()
      endDate.value = today.toISOString().split('T')[0]
      const sixMonthsAgo = new Date(today)
      sixMonthsAgo.setMonth(today.getMonth() - 6)
      startDate.value = sixMonthsAgo.toISOString().split('T')[0]
    }
  } catch (error) {
    console.error('Failed to load measurements:', error)
  } finally {
    loading.value = false
  }
}

// Load on mount
onMounted(async () => {
  loadMeasurements()
  try {
    const profile = await getUserProfile()
    userHeight.value = profile.height_cm
  } catch (e) {
    userHeight.value = null
  }
})

// Watch for time range changes
watch(() => timeRange.value, () => {
  // Data will be filtered automatically by computed property
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
  const headers = ['Date', 'Time', 'Weight (kg)', 'Body Fat (%)', 'Body Fat (kg)', 'Muscle (kg)', 'Bone Mass (kg)', 'Water (%)', 'Visceral Fat', 'Calories (kcal)', 'Body Age', 'Physical Rating']
  const rows = records.map(record => [
    record.measurement_date,
    record.measurement_time || '-',
    record.weight_kg?.toFixed(1) || '-',
    record.body_fat_percent?.toFixed(1) || '-',
    record.body_fat_kg?.toFixed(1) || '-',
    record.muscle_mass?.toFixed(1) || '-',
    record.bone_mass?.toFixed(1) || '-',
    record.body_water_percent?.toFixed(1) || '-',
    record.visceral_fat?.toFixed(1) || '-',
    record.kcal || '-',
    record.body_age || '-',
    record.physical_rating || '-'
  ])

  const csvContent = [
    headers.join(','),
    ...rows.map(row => row.map(cell => `"${cell}"`).join(','))
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
            <th>Time</th>
            <th>Weight (kg)</th>
            <th>Body Fat (%)</th>
            <th>Body Fat (kg)</th>
            <th>Muscle (kg)</th>
            <th>Bone Mass (kg)</th>
            <th>Water (%)</th>
            <th>Visceral Fat</th>
            <th>Calories (kcal)</th>
            <th>Body Age</th>
            <th>Physical Rating</th>
          </tr>
        </thead>
        <tbody>
          ${records.map(record => `
            <tr>
              <td>${record.measurement_date}</td>
              <td>${record.measurement_time || '-'}</td>
              <td>${record.weight_kg?.toFixed(1) || '-'}</td>
              <td>${record.body_fat_percent?.toFixed(1) || '-'}</td>
              <td>${record.body_fat_kg?.toFixed(1) || '-'}</td>
              <td>${record.muscle_mass?.toFixed(1) || '-'}</td>
              <td>${record.bone_mass?.toFixed(1) || '-'}</td>
              <td>${record.body_water_percent?.toFixed(1) || '-'}</td>
              <td>${record.visceral_fat?.toFixed(1) || '-'}</td>
              <td>${record.kcal || '-'}</td>
              <td>${record.body_age || '-'}</td>
              <td>${record.physical_rating || '-'}</td>
            </tr>
          `).join('')}
        </tbody>
      </table>
      <div class="footer">
        <p>This is your personal health record. Keep it private and secure.</p>
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
