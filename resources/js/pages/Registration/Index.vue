<template>
    <Head title="Registrations" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <RegistrationTable :registrations="registrations" />
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, type Registration } from '@/types';
import { Head } from '@inertiajs/vue3';
import RegistrationTable from './RegistrationTable.vue';

interface Props {
    registrations: Registration[];
}

const props = defineProps<Props>();
const registrations = props.registrations;
console.log('registrations', registrations);

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Inscripciones',
        href: '/admin/registrations',
    },
];
</script>
