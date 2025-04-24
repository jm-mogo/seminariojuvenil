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
                <TableHead> <a :href="'registrations/' + registration.id">Ver detalles</a></TableHead>
            </TableRow>
        </TableBody>
    </Table>
</template>

<script setup lang="ts">
import { Table, TableBody, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { type Registration } from '@/types';

interface Props {
    registrations: Registration[];
}

const props = defineProps<Props>();
const registrations = props.registrations;

const formatDateTime = (dateTimeString: string | null | undefined): string | null => {
    if (!dateTimeString) return null;
    try {
        // 'undefined' usará la configuración regional del navegador.
        // Para forzar español: new Date(dateTimeString).toLocaleString('es-ES', { ... })
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
