<template>
  <div class="rounded-lg border border-gray-200 bg-white/80 p-4">
    <div v-if="hasData" class="h-full min-h-56">
      <Line :data="chartData" :options="chartOptions" />
    </div>
    <div v-else class="flex h-56 items-center justify-center rounded-lg bg-gray-50 text-center">
      <div>
        <p class="text-sm text-gray-500">{{ emptyTitle }}</p>
        <p class="mt-2 text-xs text-gray-400">{{ emptySubtitle }}</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { Line } from 'vue-chartjs'
import {
  CategoryScale,
  Chart as ChartJS,
  Filler,
  Legend,
  LineElement,
  LinearScale,
  PointElement,
  Title,
  Tooltip,
} from 'chart.js'

ChartJS.register(CategoryScale, LinearScale, PointElement, LineElement, Title, Tooltip, Legend, Filler)

const props = defineProps({
  labels: {
    type: Array,
    default: () => [],
  },
  values: {
    type: Array,
    default: () => [],
  },
  label: {
    type: String,
    required: true,
  },
  color: {
    type: String,
    required: true,
  },
  fillColor: {
    type: String,
    required: true,
  },
  unit: {
    type: String,
    default: '',
  },
  emptyTitle: {
    type: String,
    default: 'No data available',
  },
  emptySubtitle: {
    type: String,
    default: 'Add more records to see the chart',
  },
})

const hasData = computed(() => props.labels.length > 0 && props.values.length > 0)

const chartData = computed(() => ({
  labels: props.labels,
  datasets: [
    {
      label: props.label,
      data: props.values,
      borderColor: props.color,
      backgroundColor: props.fillColor,
      pointBackgroundColor: props.color,
      pointBorderColor: '#ffffff',
      pointBorderWidth: 2,
      pointRadius: 4,
      pointHoverRadius: 6,
      tension: 0.35,
      fill: true,
      borderWidth: 3,
    },
  ],
}))

const chartOptions = computed(() => ({
  responsive: true,
  maintainAspectRatio: false,
  interaction: {
    mode: 'index',
    intersect: false,
  },
  plugins: {
    legend: {
      display: false,
    },
    tooltip: {
      backgroundColor: '#111827',
      titleColor: '#f9fafb',
      bodyColor: '#f9fafb',
      displayColors: false,
      callbacks: {
        label: (context) => `${context.formattedValue}${props.unit ? ` ${props.unit}` : ''}`,
      },
    },
  },
  scales: {
    x: {
      grid: {
        display: false,
      },
      ticks: {
        color: '#6b7280',
        maxRotation: 0,
      },
      border: {
        display: false,
      },
    },
    y: {
      grid: {
        color: '#e5e7eb',
        drawBorder: false,
      },
      ticks: {
        color: '#6b7280',
      },
      border: {
        display: false,
      },
    },
  },
}))
</script>
