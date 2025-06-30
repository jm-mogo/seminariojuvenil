<script setup lang="ts">
import { type Registration } from '@/types';
import { Link } from '@inertiajs/vue3';
import { h } from 'vue'; // <-- Import Vue's h function

// --- Import your table components and column definition types ---
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import DataTable from '@/components/ui/data-table/DataTable.vue';
import type { ColumnDef } from '@tanstack/vue-table';
import { ArrowUpDown } from 'lucide-vue-next';

// --- Props ---
interface Props {
    registrations: Registration[];
}
const props = defineProps<Props>();
console.log(props);
// --- Helper Function (can stay here or be moved to a utils file) ---
const formatDateTime = (dateTimeString: string | null | undefined): string | undefined => {
    if (!dateTimeString) return 'N/A';
    try {
        return new Date(dateTimeString).toLocaleString(undefined, {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
            hour: 'numeric',
            minute: '2-digit',
        });
    } catch (e) {
        console.log(e);
        return dateTimeString;
    }
};

// --- Column Definitions (Now inside this component) ---
const columns: ColumnDef<Registration>[] = [
    {
        accessorKey: 'student_full_name',
        header: ({ column }) => {
            return h(
                Button,
                {
                    variant: 'ghost',
                    onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
                },
                () => ['Nombre Completo', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })],
            );
        },
        cell: ({ row }) => h('div', { class: 'font-medium' }, row.getValue('student_full_name')),
    },
    {
        accessorKey: 'created_at',
        header: ({ column }) => {
            return h(
                Button,
                {
                    variant: 'ghost',
                    onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
                },
                () => ['Fecha', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })],
            );
        },
        cell: ({ row }) => h('div', formatDateTime(row.getValue('created_at'))),
    },
    {
        accessorKey: 'interview_status',
        header: 'Estado',
        cell: ({ row }) => {
            const status = row.getValue('interview_status') as string;
            const variant = status === 'scheduled' ? 'default' : status === 'pending' ? 'destructive' : 'outline';
            return h(Badge, { variant }, () => (status === 'scheduled' ? 'Agendado' : status === 'pending' ? 'Por revisar' : 'Por agendar'));
        },
    },
    {
        id: 'actions',
        header: 'Acciones',
        enableHiding: false,
        cell: ({ row }) => {
            const registration = row.original;
            return h(
                Link,
                {
                    href: `registrations/${registration.id}`,
                    class: 'text-primary hover:underline',
                },
                () => 'Ver detalles',
            );
        },
    },
];
</script>

<template>
    <!-- The template remains unchanged, it's simple and clean -->
    <div>
        <h1 class="mb-4 text-2xl font-bold">Inscripciones</h1>
        <DataTable :data="registrations" :columns="columns" />
    </div>
</template>
