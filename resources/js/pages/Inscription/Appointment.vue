<script setup lang="ts">
import { Head, useForm, usePage } from '@inertiajs/vue3'; // Import router, useForm, usePage
import { ChevronLeftIcon, ChevronRightIcon } from 'lucide-vue-next';
import { computed, onMounted, ref, watch } from 'vue';

// Define Props - Receive data from controller
interface AvailabilityGrouped {
    [date: string]: string[]; // Key: "YYYY-MM-DD", Value: ["HH:mm", "HH:mm"]
}

const props = defineProps({
    // Renamed from availableDates/timeSlots to match controller output
    availabilityGrouped: {
        type: Object as () => AvailabilityGrouped,
        required: true,
    },
    registrationId: {
        type: String,
        required: true,
    },
    errors: Object, // To display validation errors from the backend
});

// Emits (if needed by a parent component, otherwise remove)
// const emit = defineEmits(['confirm']);

// State
const currentDisplayMonth = ref(new Date()); // Start display in current month
const selectedDateObj = ref<Date | null>(null); // Store selected date as Date object or null
const selectedTimeSlot = ref(''); // Store selected time string
const generalError = ref(''); // For displaying non-field specific errors

// Inertia Form Helper for submission
const form = useForm({
    date: '',
    time: '',
    // No need to include registrationId here, it's in the route parameter
});

// Watch for backend validation errors
watch(
    () => props.errors,
    (newErrors) => {
        if (newErrors) {
            // Display specific errors if available, otherwise a general message
            if (newErrors.slot) {
                generalError.value = newErrors.slot;
            } else if (newErrors.booking) {
                generalError.value = newErrors.booking;
            } else if (newErrors.date || newErrors.time) {
                // Errors related to date/time format usually mean something went wrong before booking logic
                generalError.value = 'Error en la selección. Intenta de nuevo.';
            } else if (Object.keys(newErrors).length > 0) {
                generalError.value = 'Ocurrió un error. Por favor, inténtalo de nuevo.';
            } else {
                generalError.value = '';
            }
        } else {
            generalError.value = '';
        }
    },
    { immediate: true },
);

// Watch for general flash errors from redirects
const page = usePage();
watch(
    () => page.props.flash,
    (flashMessages: any) => {
        if (flashMessages?.error) {
            generalError.value = flashMessages.error;
        }
        // Clear error on success navigation? Or let success page handle messages.
        // if (flashMessages?.success) { generalError.value = ''; }
    },
    { deep: true, immediate: true },
);

// Initial Selection Logic
const trySetInitialSelection = () => {
    // Find the first available date from the grouped data
    const availableDates = Object.keys(props.availabilityGrouped).sort();
    const firstAvailableDateStr = availableDates.find((dateStr) => {
        try {
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            // Ensure date is not in the past
            return new Date(dateStr + 'T00:00:00') >= today;
        } catch {
            return false;
        }
    });

    if (firstAvailableDateStr && props.availabilityGrouped[firstAvailableDateStr]?.length > 0) {
        try {
            const [year, month, day] = firstAvailableDateStr.split('-').map(Number);
            selectedDateObj.value = new Date(year, month - 1, day);
            // Set the display month to the month of the first available date
            currentDisplayMonth.value = new Date(year, month - 1, 1);
        } catch (e) {
            console.error('Error parsing initial date:', e);
            selectedDateObj.value = null;
            currentDisplayMonth.value = new Date(); // Default to current month
        }
    } else {
        selectedDateObj.value = null; // No available future dates
        // Find the very first date overall to set the calendar month, even if past/unavailable
        const firstDateOverall = availableDates[0];
        if (firstDateOverall) {
            try {
                const [year, month] = firstDateOverall.split('-').map(Number);
                currentDisplayMonth.value = new Date(year, month - 1, 1);
            } catch {
                currentDisplayMonth.value = new Date();
            }
        } else {
            currentDisplayMonth.value = new Date(); // Default to current month if no data at all
        }
    }
    selectedTimeSlot.value = ''; // Always reset time on init
};

// --- Spanish translations (constants remain the same) ---
const spanishDaysOfWeek = ['DOM', 'LUN', 'MAR', 'MIÉ', 'JUE', 'VIE', 'SÁB'];
const spanishMonths = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
const spanishWeekdays = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];

// --- Computed properties for calendar display (remain the same) ---
const daysOfWeek = computed(() => spanishDaysOfWeek);
const currentMonthName = computed(() => spanishMonths[currentDisplayMonth.value.getMonth()]);
const currentYear = computed(() => currentDisplayMonth.value.getFullYear());

// --- calendarDates (Adapted) ---
// Generates the array of date objects for the calendar grid
const calendarDates = computed(() => {
    const year = currentDisplayMonth.value.getFullYear();
    const month = currentDisplayMonth.value.getMonth();
    const firstDayOfMonth = new Date(year, month, 1);
    const lastDayOfMonth = new Date(year, month + 1, 0);
    const firstDayOfWeek = firstDayOfMonth.getDay(); // 0 (Sun) to 6 (Sat)
    const today = new Date();
    today.setHours(0, 0, 0, 0); // For checking if date is past

    const dates: { day: number; fullDate: string; currentMonth: boolean; available: boolean; hasTimes: boolean; isPast: boolean; dateObj: Date }[] =
        [];

    // Days from previous month
    const prevMonthLastDate = new Date(year, month, 0);
    const prevMonthYear = prevMonthLastDate.getFullYear();
    const prevMonthMonth = prevMonthLastDate.getMonth();
    const prevMonthDays = prevMonthLastDate.getDate();
    for (let i = firstDayOfWeek - 1; i >= 0; i--) {
        const day = prevMonthDays - i;
        const dateObj = new Date(prevMonthYear, prevMonthMonth, day);
        const fullDate = formatDate(dateObj);
        const isPast = dateObj < today;
        dates.push({
            day,
            fullDate,
            currentMonth: false,
            available: !isPast && isDateAvailable(fullDate),
            hasTimes: hasAvailableTimes(fullDate),
            isPast,
            dateObj,
        });
    }

    // Days from current month
    for (let day = 1; day <= lastDayOfMonth.getDate(); day++) {
        const dateObj = new Date(year, month, day);
        const fullDate = formatDate(dateObj);
        const isPast = dateObj < today;
        dates.push({
            day,
            fullDate,
            currentMonth: true,
            available: !isPast && isDateAvailable(fullDate),
            hasTimes: hasAvailableTimes(fullDate),
            isPast,
            dateObj,
        });
    }

    // Days from next month to fill grid (usually up to 42 cells)
    const daysDisplayed = dates.length;
    const nextMonthDay = 1;
    const nextMonthDate = new Date(year, month + 1, nextMonthDay);
    const nextMonthYear = nextMonthDate.getFullYear();
    const nextMonthMonth = nextMonthDate.getMonth();
    for (let i = 0; i < 42 - daysDisplayed; i++) {
        const day = nextMonthDay + i;
        const dateObj = new Date(nextMonthYear, nextMonthMonth, day);
        const fullDate = formatDate(dateObj);
        const isPast = dateObj < today;
        dates.push({
            day,
            fullDate,
            currentMonth: false,
            available: !isPast && isDateAvailable(fullDate),
            hasTimes: hasAvailableTimes(fullDate),
            isPast,
            dateObj,
        });
    }

    return dates;
});

// --- formattedSelectedDate (remains the same) ---
const formattedSelectedDate = computed(() => {
    // ... same logic ...
    if (!selectedDateObj.value) {
        return 'Seleccione una fecha';
    }
    try {
        const weekday = spanishWeekdays[selectedDateObj.value.getDay()];
        const day = selectedDateObj.value.getDate();
        const month = spanishMonths[selectedDateObj.value.getMonth()];
        return `${weekday}, ${day} de ${month}`;
    } catch {
        return 'Fecha inválida';
    }
});

// --- availableTimeSlots (Adapted) ---
const availableTimeSlots = computed(() => {
    if (!selectedDateObj.value) return [];
    const dateKey = formatDate(selectedDateObj.value);
    // Use availabilityGrouped from props
    const slots = props.availabilityGrouped[dateKey] || [];
    return slots.map((time) => ({ time })).sort((a, b) => a.time.localeCompare(b.time)); // Sort times
});

// --- Helper Methods (Adapted/Added) ---
function formatDate(date: Date | null): string {
    // ... same logic ...
    if (!date) return '';
    try {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    } catch {
        return '';
    }
}

// Check if date exists in the keys of availabilityGrouped
function isDateAvailable(dateString: string): boolean {
    if (!dateString) return false;
    return Object.prototype.hasOwnProperty.call(props.availabilityGrouped, dateString);
}

// Check if the date has time slots defined
function hasAvailableTimes(dateString: string): boolean {
    if (!dateString) return false;
    return props.availabilityGrouped[dateString] && props.availabilityGrouped[dateString].length > 0;
}

function isSelectedDate(calendarDate: { fullDate: string }): boolean {
    return calendarDate.fullDate === formatDate(selectedDateObj.value);
}

// --- Action Methods (Adapted/Added) ---
function selectDate(calendarDate: { fullDate: string; available: boolean; currentMonth: boolean; isPast: boolean; dateObj: Date }) {
    // Allow selecting only current month, available, non-past dates
    if (calendarDate.available && calendarDate.currentMonth && !calendarDate.isPast) {
        selectedDateObj.value = calendarDate.dateObj;
        selectedTimeSlot.value = ''; // Reset time slot when date changes
        generalError.value = ''; // Clear errors on successful selection
    }
}

function selectTimeSlot(time: string) {
    selectedTimeSlot.value = time;
    generalError.value = ''; // Clear errors on successful selection
}

function previousMonth() {
    currentDisplayMonth.value = new Date(currentDisplayMonth.value.getFullYear(), currentDisplayMonth.value.getMonth() - 1, 1);
}

function nextMonth() {
    currentDisplayMonth.value = new Date(currentDisplayMonth.value.getFullYear(), currentDisplayMonth.value.getMonth() + 1, 1);
}

// --- confirmAppointment (UPDATED) ---
function confirmAppointment() {
    if (!selectedTimeSlot.value || !selectedDateObj.value || form.processing) return;

    const dateString = formatDate(selectedDateObj.value);
    if (!dateString) {
        generalError.value = 'Fecha seleccionada inválida.';
        return;
    }

    form.date = dateString;
    form.time = selectedTimeSlot.value;
    generalError.value = ''; // Clear previous errors

    // Use the named route for booking, passing the registrationId parameter
    form.post(route('inscription.appointment.book', { registration: props.registrationId }), {
        preserveScroll: true, // Keep scroll position on validation errors
        onSuccess: () => {
            // Controller will redirect, Inertia handles it.
            // Success message will be shown on the 'successful' page via flash message.
            console.log('Booking request sent successfully.');
        },
        onError: (errors) => {
            // Errors are already watched and set generalError
            console.error('Booking failed:', errors);
            // form.reset(); // Optionally reset form fields on error
        },
        onFinish: () => {
            // Runs regardless of success/error
        },
    });
}

// --- Lifecycle Hooks (Updated) ---
onMounted(() => {
    trySetInitialSelection();
    // If there's an initial error (e.g., from redirect), display it
    if (page.props.flash?.error) {
        generalError.value = page.props.flash.error as string;
    }
});
</script>

<template>
    <Head title="Seleccionar Cita" />

    <!-- Header -->
    <header
        class="bg-card sticky top-5 z-40 mx-auto mt-4 flex w-[90%] items-center justify-between rounded-2xl border p-2 shadow-md md:w-[70%] lg:w-[75%] lg:max-w-screen-xl"
    >
        <a href="/" class="text-foreground flex items-center gap-2 text-lg font-bold">
            <img src="/logo.jpg" alt="Logo Seminario Juvenil" class="h-12" />
            Seminario Juvenil
        </a>
        <!-- Maybe add user info or step indicator here -->
    </header>

    <!-- Main Scheduler Grid -->
    <div
        class="bg-card mx-auto my-8 grid max-w-5xl grid-cols-1 overflow-hidden rounded-lg border shadow-lg md:min-h-[600px] md:grid-cols-[1fr_320px]"
    >
        <!-- Calendar Area (Left/Top) -->
        <div class="flex flex-col items-center p-6 md:p-8">
            <h1 class="text-foreground mb-6 text-center text-2xl font-semibold">Selecciona Fecha y Hora para tu Entrevista</h1>

            <!-- Month Navigation -->
            <div class="mb-6 flex w-full items-center justify-center">
                <button
                    @click="previousMonth"
                    :disabled="form.processing"
                    class="text-muted-foreground hover:bg-muted focus:ring-ring flex h-8 w-8 items-center justify-center rounded-full transition-colors focus:ring-1 focus:outline-none disabled:opacity-50"
                    aria-label="Mes anterior"
                >
                    <ChevronLeftIcon class="h-5 w-5" />
                </button>
                <span class="text-foreground mx-4 min-w-[150px] text-center text-lg font-medium">{{ currentMonthName }} {{ currentYear }}</span>
                <button
                    @click="nextMonth"
                    :disabled="form.processing"
                    class="text-muted-foreground hover:bg-muted focus:ring-ring flex h-8 w-8 items-center justify-center rounded-full transition-colors focus:ring-1 focus:outline-none disabled:opacity-50"
                    aria-label="Mes siguiente"
                >
                    <ChevronRightIcon class="h-5 w-5" />
                </button>
            </div>

            <!-- Calendar Grid -->
            <div class="grid w-full max-w-md grid-cols-7 gap-1 md:gap-2">
                <!-- Days of Week -->
                <div v-for="day in daysOfWeek" :key="day" class="text-muted-foreground pb-2 text-center text-xs font-medium uppercase md:text-sm">
                    {{ day }}
                </div>

                <!-- Calendar Dates -->
                <div v-for="date in calendarDates" :key="date.fullDate" class="flex items-center justify-center">
                    <button
                        :class="[
                            'focus:ring-ring relative mx-auto flex h-9 w-9 items-center justify-center rounded-full border text-sm transition-colors focus:ring-2 focus:ring-offset-2 focus:outline-none',
                            // Base state based on month
                            date.currentMonth ? 'text-foreground font-medium' : 'text-muted-foreground/50 font-normal',

                            // Availability & Interactivity (only for current month, non-past, available dates)
                            date.currentMonth && date.available && !date.isPast
                                ? 'hover:bg-muted cursor-pointer border-transparent'
                                : 'cursor-not-allowed border-transparent', // Not interactive if not available/past/other month

                            // Selected state (overrides hover/availability appearance)
                            isSelectedDate(date)
                                ? '!bg-primary !text-primary-foreground !border-primary font-semibold' // Use !important to ensure override
                                : '',

                            // Disabled/Past state appearance
                            !date.currentMonth || date.isPast || !date.available
                                ? 'text-muted-foreground/50 pointer-events-none' // Greyed out and non-interactive
                                : '',

                            // Specific styling for unavailable but current month (e.g., fully booked)
                            date.currentMonth && !date.isPast && !date.available
                                ? 'text-muted-foreground/70 border-transparent line-through' // Example: strike-through
                                : '',
                        ]"
                        :disabled="!date.currentMonth || date.isPast || !date.available || form.processing"
                        @click="selectDate(date)"
                        :aria-label="`Seleccionar ${date.day} de ${spanishMonths[date.dateObj.getMonth()]}`"
                        :aria-pressed="isSelectedDate(date)"
                    >
                        {{ date.day }}
                        <!-- Indicator Dot - Show if date has times, is available, current month, not past, AND NOT selected -->
                        <span
                            v-if="date.hasTimes && date.available && date.currentMonth && !date.isPast && !isSelectedDate(date)"
                            class="bg-primary absolute bottom-1 left-1/2 h-1.5 w-1.5 -translate-x-1/2 transform rounded-full"
                            aria-hidden="true"
                        ></span>
                    </button>
                </div>
            </div>
            <!-- General Error Display Area -->
            <div
                v-if="generalError"
                class="border-destructive bg-destructive/10 text-destructive mt-4 w-full max-w-md rounded-md border p-3 text-center text-sm dark:text-red-400"
            >
                {{ generalError }}
            </div>
            <div
                v-else-if="!Object.keys(availabilityGrouped).length"
                class="mt-4 w-full max-w-md rounded-md border border-yellow-300 bg-yellow-50 p-3 text-center text-sm text-yellow-700 dark:border-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-300"
            >
                No hay fechas de entrevista disponibles en este momento. Por favor, inténtalo más tarde.
            </div>
        </div>

        <!-- Time Slots Area (Right/Bottom) -->
        <div class="bg-muted/30 border-t p-6 md:border-t-0 md:border-l dark:bg-zinc-800/30">
            <h2 class="text-foreground mb-1 text-lg font-semibold">{{ formattedSelectedDate }}</h2>
            <p class="text-muted-foreground mb-6 text-sm">Selecciona un horario disponible:</p>

            <div v-if="selectedDateObj && availableTimeSlots.length > 0" class="flex flex-col gap-3">
                <button
                    v-for="slot in availableTimeSlots"
                    :key="slot.time"
                    :class="[
                        'focus:ring-ring rounded-md border px-4 py-3 text-center text-sm font-medium transition-colors focus:ring-2 focus:ring-offset-2 focus:outline-none disabled:opacity-50',
                        selectedTimeSlot === slot.time
                            ? 'border-primary bg-primary/10 text-primary ring-primary ring-1' // Selected style
                            : 'border-border bg-card text-card-foreground hover:bg-muted/80 dark:hover:bg-zinc-700', // Default style
                    ]"
                    :disabled="form.processing"
                    @click="selectTimeSlot(slot.time)"
                    :aria-pressed="selectedTimeSlot === slot.time"
                >
                    {{ slot.time }}
                </button>

                <button
                    class="bg-primary text-primary-foreground ring-offset-background hover:bg-primary/90 focus-visible:ring-ring mt-4 inline-flex h-10 items-center justify-center rounded-md px-4 py-2 text-sm font-medium transition-colors focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:outline-none disabled:pointer-events-none disabled:opacity-50"
                    :disabled="!selectedTimeSlot || form.processing"
                    @click="confirmAppointment"
                >
                    <span v-if="form.processing">Reservando...</span>
                    <span v-else>Confirmar Cita</span>
                </button>
            </div>
            <div v-else class="text-muted-foreground mt-8 text-center text-sm">
                <p v-if="!selectedDateObj">Selecciona una fecha disponible en el calendario.</p>
                <p v-else-if="selectedDateObj && !isDateAvailable(formatDate(selectedDateObj))">Esta fecha no tiene horarios disponibles.</p>
                <p v-else-if="selectedDateObj && availableTimeSlots.length === 0 && isDateAvailable(formatDate(selectedDateObj))">
                    Todos los horarios para <br />
                    {{ formattedSelectedDate }} <br />
                    han sido reservados.
                </p>
                <p v-else>No hay horarios disponibles para esta fecha.</p>
            </div>
        </div>
    </div>
</template>

<!-- <style scoped> block removed -->
