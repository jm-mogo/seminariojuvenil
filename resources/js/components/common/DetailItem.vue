<template>
    <div
        v-if="(value !== null && value !== undefined && value !== '') || !hideIfEmpty"
        :class="isBlock ? 'flex flex-col' : 'flex flex-col sm:flex-row sm:items-center sm:justify-between'"
    >
        <span class="text-muted-foreground mb-1 text-sm font-medium sm:mb-0">{{ label }}:</span>
        <span v-if="value !== null && value !== undefined && value !== ''" class="text-sm" :class="{ 'mt-1 whitespace-pre-wrap': isBlock }">
            <!-- Added whitespace-pre-wrap for block notes -->
            <slot>{{ value }}</slot>
            <!-- Allow slot override for custom rendering (like badges) -->
        </span>
        <span v-else class="text-muted-foreground text-sm italic">
            {{ placeholder }}
        </span>
    </div>
</template>

<script setup lang="ts">
defineProps<{
    label: string;
    value?: string | number | null;
    placeholder?: string;
    isBlock?: boolean; // For multi-line values like notes
    hideIfEmpty?: boolean; // Option to completely hide if value is empty
}>();
</script>
