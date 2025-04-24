<script setup lang="ts">
// --- Imports remain the same ---
import { Alert, AlertDescription } from '@/components/ui/alert';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3'; // Import router and usePage
import { ChevronLeftIcon, ChevronRightIcon, PlusIcon, XIcon } from 'lucide-vue-next';
import { computed, ref, watch } from 'vue';

// --- Props remain the same ---
interface ExistingAvailability {
    [date: string]: string[];
}
const props = defineProps({
    errors: Object,
});

// --- Breadcrumbs - Updated ---
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Entrevisttas', // Or 'Disponibilidad' - match your section name
        href: route('admin.appointments'), // Link to the index page using its name
    },
    {
        title: 'Crear/Editar Disponibilidad',
        href: route('admin.appointments.create'), // Link to the create page using its specific name
    },
];

// --- State remains the same ---
const currentDate = ref(new Date());
const selectedDates = ref<string[]>([]);
const timeSlots = ref<string[]>([]);
const newTimeSlot = ref({
    startHour: 9,
    startMinute: '00',
});
const saveMessage = ref('');
const saveStatus = ref<'success' | 'error' | ''>('');
const isSaving = ref(false);

// --- Constants remain the same ---
const spanishDaysOfWeek = ['DOM', 'LUN', 'MAR', 'MIÉ', 'JUE', 'VIE', 'SÁB'];
const spanishMonths = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
const hours = Array.from({ length: 14 }, (_, i) => i + 7);

// --- Computed Properties remain the same ---
const daysOfWeek = computed(() => spanishDaysOfWeek);
const currentMonthName = computed(() => spanishMonths[currentDate.value.getMonth()]);
const currentYear = computed(() => currentDate.value.getFullYear());
interface CalendarDate {
    day: number;
    fullDate: string;
    currentMonth: boolean;
}
const calendarDates = computed<CalendarDate[]>(() => {
    /* ... logic unchanged ... */
    const year = currentDate.value.getFullYear();
    const month = currentDate.value.getMonth();
    const firstDayOfMonth = new Date(year, month, 1);
    const lastDayOfMonth = new Date(year, month + 1, 0);
    const lastDateOfPrevMonth = new Date(year, month, 0).getDate();
    const firstDayOfWeek = firstDayOfMonth.getDay();
    const dates: CalendarDate[] = [];
    for (let i = firstDayOfWeek - 1; i >= 0; i--) {
        const day = lastDateOfPrevMonth - i;
        const date = new Date(year, month - 1, day);
        dates.push({ day, fullDate: formatDate(date), currentMonth: false });
    }
    for (let day = 1; day <= lastDayOfMonth.getDate(); day++) {
        const date = new Date(year, month, day);
        dates.push({ day, fullDate: formatDate(date), currentMonth: true });
    }
    const totalCells = Math.ceil(dates.length / 7) * 7;
    const neededNextMonth = totalCells > dates.length ? totalCells - dates.length : 42 - dates.length > 0 ? 42 - dates.length : 0;
    for (let day = 1; day <= neededNextMonth; day++) {
        const date = new Date(year, month + 1, day);
        dates.push({ day, fullDate: formatDate(date), currentMonth: false });
    }
    return dates;
});
const selectedDatesText = computed(() => {
    /* ... logic unchanged ... */
    if (selectedDates.value.length === 0) return '';
    const sortedDates = [...selectedDates.value].sort();
    const firstDate = new Date(sortedDates[0] + 'T00:00:00');
    if (selectedDates.value.length === 1) {
        const day = firstDate.getDate();
        const month = spanishMonths[firstDate.getMonth()];
        return `${day} de ${month}`;
    }
    return `${selectedDates.value.length} fechas seleccionadas`;
});
const canSave = computed(() => selectedDates.value.length > 0 && timeSlots.value.length > 0 && !isSaving.value);

// --- Methods ---
// --- formatDate, isDateSelected, getButtonVariant, toggleDateSelection, clearSelection, previousMonth, nextMonth, addTimeSlot, removeTimeSlot, hasTimeSlots ---
// --- remain unchanged ---
function formatDate(date: Date): string {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
}
function isDateSelected(date: CalendarDate): boolean {
    return selectedDates.value.includes(date.fullDate);
}
function getButtonVariant(date: CalendarDate): 'default' | 'outline' | 'secondary' | 'ghost' {
    if (!date.currentMonth) return 'ghost';
    if (isDateSelected(date)) return 'default';
    return 'ghost';
}
function toggleDateSelection(date: CalendarDate): void {
    if (!date.currentMonth || isSaving.value) return;
    saveMessage.value = '';
    const index = selectedDates.value.indexOf(date.fullDate);
    if (index === -1) {
        selectedDates.value.push(date.fullDate);
        selectedDates.value.sort();
    } else {
        selectedDates.value.splice(index, 1);
    }
    if (selectedDates.value.length === 0) {
        timeSlots.value = [];
    }
}
function clearSelection(): void {
    if (isSaving.value) return;
    selectedDates.value = [];
    timeSlots.value = [];
    saveMessage.value = '';
    saveStatus.value = '';
}
function previousMonth(): void {
    if (isSaving.value) return;
    currentDate.value = new Date(currentDate.value.getFullYear(), currentDate.value.getMonth() - 1, 1);
}
function nextMonth(): void {
    if (isSaving.value) return;
    currentDate.value = new Date(currentDate.value.getFullYear(), currentDate.value.getMonth() + 1, 1);
}
function addTimeSlot(): void {
    if (isSaving.value) return;
    saveMessage.value = '';
    const hour = String(newTimeSlot.value.startHour).padStart(2, '0');
    const minute = newTimeSlot.value.startMinute;
    const timeSlot = `${hour}:${minute}`;
    if (!timeSlots.value.includes(timeSlot)) {
        timeSlots.value.push(timeSlot);
        timeSlots.value.sort();
    }
}
function removeTimeSlot(index: number): void {
    if (isSaving.value) return;
    saveMessage.value = '';
    timeSlots.value.splice(index, 1);
}
function hasTimeSlots(date: string): boolean {
    return selectedDates.value.includes(date) && timeSlots.value.length > 0;
}

// --- saveAvailability - Updated ---
function saveAvailability(): void {
    if (!canSave.value) return;

    isSaving.value = true;
    saveMessage.value = '';
    saveStatus.value = '';

    const availabilityData: ExistingAvailability = {};
    selectedDates.value.forEach((date) => {
        availabilityData[date] = [...timeSlots.value];
    });

    // Use Inertia router to POST data to the named route 'appointment.store'
    // Ensure the path '/admin/apointment' (with typo) matches the route definition if not using Ziggy
    router.post(
        route('admin.appointments.store'),
        {
            // USE THIS if Ziggy is installed
            // router.post('/admin/apointment', { // USE THIS if Ziggy is NOT installed (matches typo in route)
            availability: availabilityData,
        },
        {
            preserveScroll: true,
            onSuccess: (page) => {
                const flash = page.props.flash as { success?: string; error?: string }; // Type assertion
                saveMessage.value = flash?.success || 'Guardado exitosamente.';
                saveStatus.value = 'success';
                // Optionally reset state or clear selection
                // clearSelection();
                setTimeout(() => {
                    saveMessage.value = '';
                    saveStatus.value = '';
                }, 3000);
            },
            onError: (errors) => {
                console.error('Error saving availability:', errors);
                if (errors.availability) {
                    saveMessage.value = errors.availability;
                } else {
                    const firstError = Object.values(errors)[0];
                    saveMessage.value = typeof firstError === 'string' ? firstError : 'Error al guardar. Revisa los datos.';
                }
                saveStatus.value = 'error';
                setTimeout(() => {
                    saveMessage.value = '';
                    saveStatus.value = '';
                }, 6000);
            },
            onFinish: () => {
                isSaving.value = false;
            },
        },
    );
}

watch(
    () => props.errors,
    (newErrors) => {
        if (newErrors && Object.keys(newErrors).length > 0 && !isSaving.value) {
            saveMessage.value = 'Error de validación. Revisa los campos.';
            saveStatus.value = 'error';
        }
    },
);

// Optional: Watch flash messages directly if needed
// const page = usePage();
// watch(() => page.props.flash, (flashMessages) => {
//     const flash = flashMessages as { success?: string; error?: string };
//     if (flash?.success) { /* ... */ }
//     if (flash?.error) { /* ... */ }
// }, { deep: true });
</script>

<!-- Template remains the same as the last version provided -->
<!-- No changes needed in the <template> section based on route changes -->
<template>
    <div>
        <Head title="Panel de Administración - Crear Disponibilidad" />
        <AppLayout :breadcrumbs="breadcrumbs">
            <!-- Optional Flash Message Display -->
            <!-- <div v-if="$page.props.flash.success" class="m-4 p-4 bg-green-100 text-green-700 rounded"> {{ $page.props.flash.success }} </div> -->
            <!-- <div v-if="$page.props.flash.error" class="m-4 p-4 bg-red-100 text-red-700 rounded"> {{ $page.props.flash.error }} </div> -->

            <div class="max-w-7xl space-y-6 px-4 py-6 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between">
                    <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Crear Disponibilidad</h1>
                </div>

                <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                    <!-- Calendar Section -->
                    <fieldset :disabled="isSaving" class="lg:col-span-2">
                        <Card>
                            <CardHeader>
                                <CardTitle>Seleccionar Fechas Disponibles</CardTitle>
                                <CardDescription>Elige las fechas para las que deseas configurar horarios.</CardDescription>
                                <p v-if="errors?.availability && typeof errors.availability === 'string'" class="mt-2 text-sm text-red-600">
                                    {{ errors.availability }}
                                </p>
                            </CardHeader>
                            <CardContent class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <Button variant="outline" size="icon" @click="previousMonth" :disabled="isSaving">
                                        <ChevronLeftIcon class="h-4 w-4" />
                                    </Button>
                                    <span class="text-center font-medium">{{ currentMonthName }} {{ currentYear }}</span>
                                    <Button variant="outline" size="icon" @click="nextMonth" :disabled="isSaving">
                                        <ChevronRightIcon class="h-4 w-4" />
                                    </Button>
                                </div>
                                <div class="grid grid-cols-7 gap-1">
                                    <div v-for="day in daysOfWeek" :key="day" class="text-muted-foreground p-2 text-center text-xs font-medium">
                                        {{ day }}
                                    </div>
                                    <template v-for="date in calendarDates" :key="date.fullDate">
                                        <div class="flex items-center justify-center">
                                            <Button
                                                :variant="getButtonVariant(date)"
                                                size="icon"
                                                :class="[
                                                    'h-9 w-9 rounded-full',
                                                    !date.currentMonth ? 'text-muted-foreground opacity-50' : '',
                                                    isDateSelected(date) ? 'relative' : '',
                                                ]"
                                                :disabled="!date.currentMonth || isSaving"
                                                @click="toggleDateSelection(date)"
                                            >
                                                {{ date.day }}
                                                <span
                                                    v-if="hasTimeSlots(date.fullDate) && isDateSelected(date)"
                                                    class="absolute bottom-1 left-1/2 h-1.5 w-1.5 -translate-x-1/2 transform rounded-full bg-green-500"
                                                    aria-label="Tiene horarios configurados"
                                                ></span>
                                            </Button>
                                        </div>
                                    </template>
                                </div>
                            </CardContent>
                            <CardFooter class="flex justify-end">
                                <Button variant="outline" @click="clearSelection" :disabled="isSaving"> Limpiar Selección </Button>
                            </CardFooter>
                        </Card>
                    </fieldset>

                    <!-- Time Slots Section -->
                    <fieldset :disabled="isSaving" class="lg:col-span-1">
                        <Card>
                            <CardHeader>
                                <CardTitle>Configurar Horarios</CardTitle>
                                <CardDescription v-if="selectedDates.length === 0"> Selecciona fechas en el calendario. </CardDescription>
                                <CardDescription v-else> Para: {{ selectedDatesText }} </CardDescription>
                                <p v-if="errors?.['availability.*.*']" class="mt-2 text-sm text-red-600">{{ errors['availability.*.*'] }}</p>
                            </CardHeader>
                            <CardContent class="space-y-6">
                                <div v-if="selectedDates.length === 0" class="text-muted-foreground bg-muted rounded-md p-4 text-center text-sm">
                                    Selecciona una o más fechas para añadir horarios.
                                </div>
                                <div v-else class="space-y-4">
                                    <div class="bg-muted/50 space-y-2 rounded-md border p-4">
                                        <Label for="start-hour">Añadir Hora Disponible (formato 24h)</Label>
                                        <div class="flex items-center gap-2">
                                            <Select v-model="newTimeSlot.startHour" :disabled="isSaving">
                                                <SelectTrigger id="start-hour" class="w-[80px]"> <SelectValue placeholder="Hora" /> </SelectTrigger>
                                                <SelectContent>
                                                    <SelectItem v-for="hour in hours" :key="`start-${hour}`" :value="hour">
                                                        {{ String(hour).padStart(2, '0') }}
                                                    </SelectItem>
                                                </SelectContent>
                                            </Select>
                                            <span class="text-muted-foreground">:</span>
                                            <Select v-model="newTimeSlot.startMinute" :disabled="isSaving">
                                                <SelectTrigger id="start-minute" class="w-[80px]"> <SelectValue placeholder="Min" /> </SelectTrigger>
                                                <SelectContent>
                                                    <SelectItem value="00">00</SelectItem> <SelectItem value="15">15</SelectItem>
                                                    <SelectItem value="30">30</SelectItem> <SelectItem value="45">45</SelectItem>
                                                </SelectContent>
                                            </Select>
                                            <Button @click="addTimeSlot" size="sm" class="ml-auto" :disabled="isSaving">
                                                <PlusIcon class="mr-1 h-4 w-4" /> Añadir
                                            </Button>
                                        </div>
                                    </div>
                                    <div>
                                        <h3 class="mb-2 text-sm font-medium">Horarios Configurados</h3>
                                        <div v-if="timeSlots.length === 0" class="text-muted-foreground bg-muted rounded-md p-3 text-center text-xs">
                                            No hay horarios añadidos para las fechas seleccionadas.
                                        </div>
                                        <div v-else class="grid grid-cols-[repeat(auto-fill,minmax(80px,1fr))] gap-2">
                                            <Badge
                                                v-for="(slot, index) in timeSlots"
                                                :key="index"
                                                variant="secondary"
                                                class="flex items-center justify-between"
                                            >
                                                <span>{{ slot }}</span>
                                                <Button
                                                    variant="ghost"
                                                    size="icon"
                                                    class="hover:bg-destructive/20 hover:text-destructive ml-1 h-5 w-5"
                                                    @click="removeTimeSlot(index)"
                                                    aria-label="Eliminar horario"
                                                    :disabled="isSaving"
                                                >
                                                    <XIcon class="h-3 w-3" />
                                                </Button>
                                            </Badge>
                                        </div>
                                    </div>
                                </div>
                            </CardContent>
                            <CardFooter class="flex flex-col items-stretch gap-4">
                                <Button @click="saveAvailability" :disabled="!canSave">
                                    <span v-if="isSaving">Guardando...</span>
                                    <span v-else>Guardar Disponibilidad</span>
                                </Button>
                                <Alert v-if="saveMessage" :variant="saveStatus === 'success' ? 'default' : 'destructive'">
                                    <AlertDescription>{{ saveMessage }}</AlertDescription>
                                </Alert>
                            </CardFooter>
                        </Card>
                    </fieldset>
                </div>
            </div>
        </AppLayout>
    </div>
</template>
