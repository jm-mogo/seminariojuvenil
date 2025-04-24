<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    totalRegistrations: {
        type: Number, // Laravel pagination data is an object
        required: true,
    },
    newRegistrations: {
        type: Number, // Laravel pagination data is an object
        required: true,
    },
    recurrentRegistrations: {
        type: Number, // Laravel pagination data is an object
        required: true,
    },
    pendingRegistrations: {
        type: Number, // Laravel pagination data is an object
        required: true,
    },
    pendingScheduledInterviews: {
        type: Number, // Laravel pagination data is an object
        required: true,
    },
    scheduledInterviews: {
        type: Number, // Laravel pagination data is an object
        required: true,
    },
});

console.log('totalRegistrations', props.totalRegistrations);

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Panel',
        href: '/admin',
    },
];
</script>

<template>
    <Head title="Panel" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <div
                    class="border-sidebar-border/70 dark:border-sidebar-border relative flex aspect-video items-center justify-center overflow-hidden rounded-xl border bg-gray-100 text-lg font-semibold text-gray-700 dark:bg-gray-800 dark:text-gray-300"
                >
                    Total de Incripciones: {{ props.totalRegistrations }}
                </div>
                <div
                    class="border-sidebar-border/70 dark:border-sidebar-border relative flex aspect-video items-center justify-center overflow-hidden rounded-xl border bg-gray-100 text-lg font-semibold text-gray-700 dark:bg-gray-800 dark:text-gray-300"
                >
                    Nuevos: {{ props.newRegistrations }}
                </div>
                <div
                    class="border-sidebar-border/70 dark:border-sidebar-border relative flex aspect-video items-center justify-center overflow-hidden rounded-xl border bg-gray-100 text-lg font-semibold text-gray-700 dark:bg-gray-800 dark:text-gray-300"
                >
                    Re-inscripción: {{ props.recurrentRegistrations }}
                </div>
                <div
                    class="border-sidebar-border/70 dark:border-sidebar-border relative flex aspect-video items-center justify-center overflow-hidden rounded-xl border bg-gray-100 text-lg font-semibold text-gray-700 dark:bg-gray-800 dark:text-gray-300"
                >
                    Por revisar: {{ props.pendingRegistrations }}
                </div>
                <div
                    class="border-sidebar-border/70 dark:border-sidebar-border relative flex aspect-video items-center justify-center overflow-hidden rounded-xl border bg-gray-100 text-lg font-semibold text-gray-700 dark:bg-gray-800 dark:text-gray-300"
                >
                    Por agendar entrevistas: {{ props.pendingScheduledInterviews }}
                </div>
                <div
                    class="border-sidebar-border/70 dark:border-sidebar-border relative flex aspect-video items-center justify-center overflow-hidden rounded-xl border bg-gray-100 text-lg font-semibold text-gray-700 dark:bg-gray-800 dark:text-gray-300"
                >
                    Entrevistas programadas: {{ props.scheduledInterviews }}
                </div>
            </div>
        </div>
    </AppLayout>
</template>
