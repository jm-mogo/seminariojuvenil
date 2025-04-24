<template>
    <div>
        <!-- Head and AppLayout remain the same -->
        <Head title="Gestión de Entrevistas" />
        <AppLayout :breadcrumbs="breadcrumbs">
            <!-- Flash Message Display remains the same -->
            <div
                v-if="page.props.flash?.success"
                class="m-4 rounded border border-green-300 bg-green-100 p-4 text-sm text-green-700 dark:border-green-600 dark:bg-green-900/30 dark:text-green-300"
            >
                {{ page.props.flash.success }}
            </div>
            <div
                v-if="page.props.flash?.error"
                class="m-4 rounded border border-red-300 bg-red-100 p-4 text-sm text-red-700 dark:border-red-600 dark:bg-red-900/30 dark:text-red-300"
            >
                {{ page.props.flash.error }}
            </div>
            <div
                v-if="page.props.flash?.warning"
                class="m-4 rounded border border-yellow-300 bg-yellow-100 p-4 text-sm text-yellow-700 dark:border-yellow-600 dark:bg-yellow-900/30 dark:text-yellow-300"
            >
                {{ page.props.flash.warning }}
            </div>

            <div class="max-w-7xl space-y-6 px-4 py-6 sm:px-6 lg:px-8">
                <!-- Header Section remains the same -->
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Gestión de Entrevistas</h1>
                    <Button @click="goToCreatePage"> Crear/Editar Disponibilidad </Button>
                </div>

                <!-- Section Title: All Slots by Date -->
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Disponibilidad por Fecha</h2>

                <!-- Display All Slots (Available and Booked) Grouped by Date -->
                <div v-if="hasSlots" class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    <Card v-for="(slots, date) in allSlotsGrouped" :key="date" class="flex flex-col">
                        <CardHeader>
                            <CardTitle class="text-lg">{{ formatDisplayDate(date) }}</CardTitle>
                            <CardDescription>{{ getDayOfWeek(date) }}</CardDescription>
                        </CardHeader>
                        <CardContent class="flex-grow">
                            <div v-if="slots.length > 0" class="flex flex-wrap gap-2">
                                <!-- Loop through slot objects { id, time, registration_id, registration_name? } -->
                                <template v-for="slot in slots" :key="slot.id">
                                    <!-- Booked Slot -->
                                    <div
                                        v-if="slot.registration_id"
                                        class="flex cursor-default items-center gap-1.5 rounded border border-blue-300 bg-blue-100 px-2.5 py-1 text-xs font-medium text-blue-800 dark:border-blue-700 dark:bg-blue-900/50 dark:text-blue-300"
                                        :title="`Reservado por Reg. ID: ${slot.registration_id}`"
                                    >
                                        <!-- Lock Icon -->
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="h-3.5 w-3.5">
                                            <path
                                                fill-rule="evenodd"
                                                d="M8 1a3.5 3.5 0 0 0-3.5 3.5V7H4a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-.5V4.5A3.5 3.5 0 0 0 8 1Zm2 6V4.5a2 2 0 1 0-4 0V7h4Z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>
                                        <span>{{ slot.time }}</span>
                                        <!-- Display Registrant Name if available -->
                                        <span
                                            v-if="slot.registration_name"
                                            class="ml-1 truncate italic opacity-80"
                                            :title="`Registrante: ${slot.registration_name}`"
                                        >
                                            ({{ slot.registration_name }})
                                        </span>
                                        <!-- Optional: Link to registration details -->
                                        <!-- <Link :href="route('admin.registrations.show', { registration: slot.registration_id })" class="ml-1 underline hover:no-underline"> Ver </Link> -->
                                    </div>
                                    <!-- Available Slot -->
                                    <Badge v-else variant="secondary" class="font-normal">
                                        {{ slot.time }}
                                    </Badge>
                                </template>
                            </div>
                            <p v-else class="text-muted-foreground text-sm">No hay horarios configurados para esta fecha.</p>
                        </CardContent>
                    </Card>
                </div>

                <!-- Empty State for All Slots -->
                <div v-else class="bg-muted/50 rounded-md border border-dashed p-8 text-center dark:bg-zinc-800/30">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">No hay disponibilidad creada</h3>
                    <p class="text-muted-foreground mt-1 text-sm">Usa el botón "Crear/Editar Disponibilidad" para añadir fechas y horarios.</p>
                </div>

                <!-- Section Title: Booked Appointments Overview -->
                <h2 class="mt-10 text-xl font-semibold text-gray-800 dark:text-gray-200">Entrevistas Reservadas</h2>

                <!-- List/Table of Booked Appointments -->
                <div v-if="bookedAppointments.length > 0" class="bg-card overflow-hidden rounded-md border shadow-sm">
                    <ul class="divide-border divide-y">
                        <li
                            v-for="appointment in bookedAppointments"
                            :key="appointment.id"
                            class="flex flex-col items-start justify-between gap-2 p-4 sm:flex-row sm:items-center"
                        >
                            <div class="flex items-center gap-3">
                                <span class="text-foreground font-medium">{{ formatDisplayDate(appointment.date) }}</span>
                                <Badge variant="outline">{{ appointment.time }}</Badge>
                            </div>
                            <div class="text-muted-foreground text-sm">
                                <span class="font-medium">Estudiante:</span>
                                {{ appointment.registration_name || 'N/A' }}
                                <span class="ml-2">(ID: {{ appointment.registration_id }})</span>
                                <!-- Optional: Link to registration -->
                                <!-- <Link :href="route('admin.registrations.show', { registration: appointment.registration_id })" class="text-primary hover:underline ml-2"> Ver Registro </Link> -->
                            </div>
                        </li>
                    </ul>
                </div>

                <!-- Empty State for Booked Appointments -->
                <div v-else class="bg-muted/50 rounded-md border border-dashed p-8 text-center dark:bg-zinc-800/30">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">No hay entrevistas reservadas</h3>
                    <p class="text-muted-foreground mt-1 text-sm">Cuando los usuarios reserven citas, aparecerán aquí.</p>
                </div>
            </div>
        </AppLayout>
    </div>
</template>

<script setup lang="ts">
import { Head, router, usePage } from '@inertiajs/vue3'; // Import Link and usePage
import { computed } from 'vue';

// Layout and Types
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';

// Shadcn-Vue Components
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';

// Define Props - Adjusted structure from Controller
interface SlotInfo {
    id: number;
    time: string; // "HH:mm"
    registration_id: string | null; // UUID or null
    registration_name?: string | null; // Optional
}
interface AllSlotsGrouped {
    [date: string]: SlotInfo[]; // Key: "YYYY-MM-DD", Value: Array of SlotInfo objects
}

const props = defineProps({
    // Renamed prop to reflect it contains all slots
    allSlotsGrouped: {
        type: Object as () => AllSlotsGrouped,
        required: true,
    },
    // Removed bookedSlotsGrouped as it's now derived
    // errors: Object, // Keep if needed
});

// Breadcrumbs - Using correct route name
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Gestión de Entrevistas',
        href: route('admin.appointments'), // Correct route name
    },
];

// Access the Inertia page object
const page = usePage();

// Computed property to check if there are any slots at all
const hasSlots = computed(() => {
    return props.allSlotsGrouped && Object.keys(props.allSlotsGrouped).length > 0;
});

// Computed property to get a flat list of only booked appointments
const bookedAppointments = computed(() => {
    if (!hasSlots.value) {
        return [];
    }
    const booked: (SlotInfo & { date: string })[] = []; // Add date property to the flat structure
    for (const date in props.allSlotsGrouped) {
        props.allSlotsGrouped[date].forEach((slot) => {
            if (slot.registration_id) {
                booked.push({ ...slot, date: date }); // Add the date back when flattening
            }
        });
    }
    // Sort booked appointments by date and time
    booked.sort((a, b) => {
        const dateTimeA = new Date(`${a.date}T${a.time}:00`);
        const dateTimeB = new Date(`${b.date}T${b.time}:00`);
        return dateTimeA.getTime() - dateTimeB.getTime();
    });
    return booked;
});

// --- Helper Functions (remain the same) ---
const spanishMonths = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
const spanishWeekdays = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];

function formatDisplayDate(dateString: string): string {
    // Input: YYYY-MM-DD
    try {
        const date = new Date(dateString + 'T00:00:00'); // Avoid timezone issues
        const day = date.getDate();
        const month = spanishMonths[date.getMonth()];
        const year = date.getFullYear();
        return `${day} de ${month}, ${year}`;
    } catch (e) {
        return dateString; // Fallback
    }
}

function getDayOfWeek(dateString: string): string {
    try {
        const date = new Date(dateString + 'T00:00:00'); // Avoid timezone issues
        return spanishWeekdays[date.getDay()];
    } catch (e) {
        return ''; // Fallback
    }
}

// --- Navigation Function - Using correct route name ---
function goToCreatePage(): void {
    router.visit(route('admin.appointments.create')); // Correct route name
}
</script>

<style>
/* Optional scoped styles or global overrides */
</style>
