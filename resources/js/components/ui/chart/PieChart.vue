<script setup lang="ts">
import { computed } from 'vue'
import { Pie } from 'vue-chartjs'
import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  ArcElement,
  type ChartOptions
} from 'chart.js'
import { useColorMode } from '@vueuse/core'

ChartJS.register(Title, Tooltip, Legend, ArcElement)

const props = defineProps<{
  chartData: any
}>()

const mode = useColorMode()

// Define high-contrast foreground for tooltip text
const FgColor = 'hsl(var(--foreground))'
// Keep muted for the less important legend text
const MutedFgColor = 'hsl(var(--muted-foreground))'
const BackgroundColor = 'hsl(var(--background))'
const BorderColor = 'hsl(var(--border))'

// Create computed chart options that react to theme changes
const chartOptions = computed((): ChartOptions<'pie'> => {
  return {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      // Re-enable the default legend, as we're removing the custom one
      legend: {
        display: false,
        position: 'bottom',
        labels: {
          color: MutedFgColor, // Muted color is fine for the legend
          padding: 20,
        },
      },
      // FIX: Use the high-contrast color for tooltip text
      tooltip: {
        backgroundColor: BackgroundColor,
        titleColor: FgColor, // <-- This is the fix
        bodyColor: FgColor,  // <-- This is the fix
        borderColor: BorderColor,
        borderWidth: 1,
      },
    },
  }
})
</script>

<template>
  <Pie
    :key="mode"
    :data="props.chartData"
    :options="chartOptions"
  />
</template>