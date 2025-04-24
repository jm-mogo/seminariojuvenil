// src/components/common/SectionCard.vue
<template>
    <div class="bg-card text-card-foreground rounded-lg border p-4 shadow-sm md:p-6">
        <h2 class="mb-4 text-lg font-semibold">{{ title }}</h2>
        <div class="space-y-3">
            <slot />
        </div>
    </div>
</template>

<script setup lang="ts">
defineProps<{ title: string }>();
</script>
