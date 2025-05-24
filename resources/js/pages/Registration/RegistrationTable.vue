<template>
    <Table>
        <TableHeader>
            <TableRow>
                <TableHead>Nombre Completo</TableHead>
                <TableHead>Fecha</TableHead>
                <TableHead>Estado</TableHead>
                <TableHead>Ver todos los datos</TableHead>
            </TableRow>
        </TableHeader>
        <TableBody>
            <TableRow v-for="registration in registrations" :key="registration.id">
                <TableHead>{{ registration.student_full_name }}</TableHead>
                <TableHead>{{ formatDateTime(registration.created_at) }}</TableHead>
                <TableHead>{{ registration.interview_status }}</TableHead>
                <!-- Use Inertia's Link component for SPA navigation -->
                <TableHead>
                    <Link :href="'registrations/' + registration.id"> Ver detalles </Link>
                </TableHead>
            </TableRow>
        </TableBody>
    </Table>
</template>

<script setup lang="ts">
import { Table, TableBody, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { type Registration } from '@/types';
import { Link } from '@inertiajs/vue3'; // <-- Import Inertia Link

interface Props {
    registrations: Registration[];
}

const props = defineProps<Props>();
console.log('props', props);

const formatDateTime = (dateTimeString: string | null | undefined): string | null => {
    if (!dateTimeString) return null;
    try {
        return new Date(dateTimeString).toLocaleString(undefined, {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
            hour: 'numeric',
            minute: '2-digit',
        });
    } catch (e) {
        console.error('Error formateando fecha/hora:', dateTimeString, e);
        return dateTimeString;
    }
};
</script>
