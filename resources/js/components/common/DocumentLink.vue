// src/components/common/DocumentLink.vue
<template>
    <div v-if="path">
        <span class="text-muted-foreground text-sm font-medium">{{ label }}:</span>
        <a :href="getDocumentUrl(path)" target="_blank" rel="noopener noreferrer" class="ml-2 text-sm break-all text-blue-600 hover:underline">
            View/Download
            <!-- Or display filename if you pass it -->
        </a>
    </div>
    <!-- Optionally show placeholder if path is null -->
    <!-- <div v-else>
         <span class="text-sm font-medium text-muted-foreground">{{ label }}:</span>
         <span class="text-sm text-muted-foreground italic ml-2">Not Uploaded</span>
    </div> -->
</template>

<script setup lang="ts">
const props = defineProps<{
    label: string;
    path: string | null;
}>();

// IMPORTANT: Adjust this function based on how your files are served!
// This assumes files are publicly accessible under /storage/
const getDocumentUrl = (filePath: string | null): string => {
    if (!filePath) return '#';
    // Remove leading slashes if they exist to prevent double slashes
    const cleanedPath = filePath.startsWith('/') ? filePath.substring(1) : filePath;
    // Common pattern: prefix with /storage/ if using Laravel's public disk symlink
    return `/storage/${cleanedPath}`;
    // Alternative: If you have a dedicated download route:
    // return route('documents.download', { path: filePath }); // Requires Ziggy
};
</script>
