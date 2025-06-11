<template>
    <header
        :class="{
            'bg-card sticky top-5 z-40 mx-auto mt-4 flex w-[90%] items-center justify-between rounded-2xl border p-2 shadow-md md:w-[70%] lg:w-[75%] lg:max-w-screen-xl': true,
        }"
    >
        <a href="/" class="flex items-center gap-2 text-lg font-bold">
            <img src="/logo.jpg" alt="Logo Seminario Juvenil" class="h-12" />
            Seminario Juvenil
        </a>
    </header>

    <section class="mx-auto max-w-4xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="mb-8 text-center">
            <!-- Updated Title -->
            <h1 class="text-4xl font-bold tracking-tight sm:text-5xl">Formulario de Re-inscripción</h1>
            <!-- Updated Description -->
            <p class="text-muted-foreground mt-4 text-xl">Completa los campos requeridos para procesar tu re-inscripción.</p>
            <p v-if="form.hasErrors" class="mt-4 font-medium text-red-600">Por favor, corrige los errores indicados en el formulario.</p>
        </div>

        <form @submit.prevent="submit" class="space-y-8">
            <Card>
                <CardHeader>
                    <CardTitle>Datos Personales del Estudiante</CardTitle>
                    <CardDescription>Información básica del estudiante.</CardDescription>
                </CardHeader>
                <CardContent class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div class="space-y-2">
                        <Label for="studentFirstName">Nombres <span class="text-red-500">*</span></Label>
                        <Input id="studentFirstName" v-model="form.studentFirstName" required placeholder="Ej: Juan Carlos" />
                        <p v-if="form.errors.studentFirstName" class="text-sm text-red-600">{{ form.errors.studentFirstName }}</p>
                    </div>
                    <div class="space-y-2">
                        <Label for="studentLastName">Apellidos <span class="text-red-500">*</span></Label>
                        <Input id="studentLastName" v-model="form.studentLastName" required placeholder="Ej: Pérez Gómez" />
                        <p v-if="form.errors.studentLastName" class="text-sm text-red-600">{{ form.errors.studentLastName }}</p>
                    </div>
                    <div class="space-y-2">
                        <Label for="studentIdNumber">Cédula de Identidad <span class="text-red-500">*</span></Label>
                        <Input id="studentIdNumber" v-model="form.studentIdNumber" required placeholder="Ej: V-12345678 ó E-12345678" />
                        <p v-if="form.errors.studentIdNumber" class="text-sm text-red-600">{{ form.errors.studentIdNumber }}</p>
                    </div>

                    <div class="space-y-2">
                        <Label for="birthDateTrigger">Fecha de Nacimiento <span class="text-red-500">*</span></Label>
                        <Popover v-model:open="isCalendarOpen">
                            <PopoverTrigger as-child>
                                <Button
                                    variant="outline"
                                    :class="cn('w-full justify-start text-left font-normal', !selectedDateValue && 'text-muted-foreground')"
                                    id="birthDateTrigger"
                                >
                                    <CalendarIcon class="mr-2 h-4 w-4" />
                                    {{ formattedDateForButton }}
                                </Button>
                            </PopoverTrigger>
                            <PopoverContent class="w-auto p-0">
                                <Calendar v-model="selectedDateValue" initial-focus :locale="'es'" @update:modelValue="handleDateUpdate" />
                            </PopoverContent>
                        </Popover>
                        <p v-if="form.errors.birthDate" class="text-sm text-red-600">{{ form.errors.birthDate }}</p>
                    </div>

                    <div class="space-y-2">
                        <Label for="studentPhoneNumber">Teléfono</Label>
                        <Input id="studentPhoneNumber" type="tel" v-model="form.studentPhoneNumber" placeholder="Ej: 0412-1234567" />
                        <p v-if="form.errors.studentPhoneNumber" class="text-sm text-red-600">{{ form.errors.studentPhoneNumber }}</p>
                    </div>
                    <div class="space-y-2">
                        <Label for="studentEmail">Correo Electrónico</Label>
                        <Input id="studentEmail" type="email" v-model="form.studentEmail" placeholder="ejemplo@correo.com" />
                        <p v-if="form.errors.studentEmail" class="text-sm text-red-600">{{ form.errors.studentEmail }}</p>
                    </div>

                    <div class="space-y-2">
                        <Label>Género <span class="text-red-500">*</span></Label>
                        <RadioGroup v-model="form.studentGender" required class="flex items-center gap-4 pt-1">
                            <div class="flex items-center space-x-2">
                                <RadioGroupItem id="gender-male" value="male" />
                                <Label for="gender-male">Masculino</Label>
                            </div>
                            <div class="flex items-center space-x-2">
                                <RadioGroupItem id="gender-female" value="female" />
                                <Label for="gender-female">Femenino</Label>
                            </div>
                        </RadioGroup>
                        <p v-if="form.errors.studentGender" class="text-sm text-red-600">{{ form.errors.studentGender }}</p>
                    </div>

                    <!-- === NEW FIELD === -->
                    <div class="space-y-2">
                        <Label for="previousParticipations">¿Cuántas veces has participado antes? <span class="text-red-500">*</span></Label>
                        <Input
                            id="previousParticipations"
                            type="number"
                            min="1"
                            max="2"
                            v-model.number="form.previousParticipations"
                            required
                            placeholder="1 ó 2"
                        />
                        <p class="text-muted-foreground text-xs">Indica si has participado 1 o 2 veces anteriormente en el seminario.</p>
                        <p v-if="form.errors.previousParticipations" class="text-sm text-red-600">{{ form.errors.previousParticipations }}</p>
                    </div>
                    <!-- === END NEW FIELD === -->
                </CardContent>
            </Card>

            <Card>
                <CardHeader>
                    <CardTitle>Datos del Representante</CardTitle>
                    <CardDescription>Información del padre, madre o tutor legal.</CardDescription>
                </CardHeader>
                <CardContent class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div class="space-y-2">
                        <Label for="guardianFirstName">Nombres <span class="text-red-500">*</span></Label>
                        <Input id="guardianFirstName" v-model="form.guardianFirstName" required placeholder="Ej: María Elena" />
                        <p v-if="form.errors.guardianFirstName" class="text-sm text-red-600">{{ form.errors.guardianFirstName }}</p>
                    </div>
                    <div class="space-y-2">
                        <Label for="guardianLastName">Apellidos <span class="text-red-500">*</span></Label>
                        <Input id="guardianLastName" v-model="form.guardianLastName" required placeholder="Ej: Rojas Silva" />
                        <p v-if="form.errors.guardianLastName" class="text-sm text-red-600">{{ form.errors.guardianLastName }}</p>
                    </div>
                    <div class="space-y-2">
                        <Label for="guardianIdNumber">Cédula de Identidad <span class="text-red-500">*</span></Label>
                        <Input id="guardianIdNumber" v-model="form.guardianIdNumber" required placeholder="Ej: V-87654321" />
                        <p v-if="form.errors.guardianIdNumber" class="text-sm text-red-600">{{ form.errors.guardianIdNumber }}</p>
                    </div>
                    <div class="space-y-2">
                        <Label for="guardianPhoneNumber">Teléfono <span class="text-red-500">*</span></Label>
                        <Input id="guardianPhoneNumber" type="tel" v-model="form.guardianPhoneNumber" required placeholder="Ej: 0416-9876543" />
                        <p v-if="form.errors.guardianPhoneNumber" class="text-sm text-red-600">{{ form.errors.guardianPhoneNumber }}</p>
                    </div>
                    <div class="space-y-2 md:col-span-2">
                        <Label for="guardianRelationship">Parentesco con el estudiante <span class="text-red-500">*</span></Label>
                        <Input id="guardianRelationship" v-model="form.guardianRelationship" required placeholder="Ej: Madre, Padre, Tutor Legal" />
                        <p v-if="form.errors.guardianRelationship" class="text-sm text-red-600">{{ form.errors.guardianRelationship }}</p>
                    </div>
                </CardContent>
            </Card>

            <Card>
                <CardHeader>
                    <CardTitle>Información de la Iglesia</CardTitle>
                    <CardDescription>Confirma o actualiza los detalles sobre la congregación a la que asistes.</CardDescription>
                </CardHeader>
                <CardContent class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div class="space-y-2">
                        <Label for="churchName">Nombre de la Iglesia <span class="text-red-500">*</span></Label>
                        <Input id="churchName" v-model="form.churchName" required placeholder="Ej: Iglesia Bautista Central" />
                        <p v-if="form.errors.churchName" class="text-sm text-red-600">{{ form.errors.churchName }}</p>
                    </div>
                    <div class="space-y-2">
                        <Label for="churchCity">Ciudad de la Iglesia <span class="text-red-500">*</span></Label>
                        <Input id="churchCity" v-model="form.churchCity" required placeholder="Ej: Caracas" />
                        <p v-if="form.errors.churchCity" class="text-sm text-red-600">{{ form.errors.churchCity }}</p>
                    </div>
                    <div class="space-y-2">
                        <Label for="pastorName">Nombre del Pastor Principal <span class="text-red-500">*</span></Label>
                        <Input id="pastorName" v-model="form.pastorName" required placeholder="Ej: Pastor Luis Rodríguez" />
                        <p v-if="form.errors.pastorName" class="text-sm text-red-600">{{ form.errors.pastorName }}</p>
                    </div>
                    <div class="space-y-2">
                        <Label for="attendanceDuration">Tiempo Asistiendo (Años/Meses) <span class="text-red-500">*</span></Label>
                        <Input id="attendanceDuration" v-model="form.attendanceDuration" required placeholder="Ej: 3 años y 6 meses" />
                        <p v-if="form.errors.attendanceDuration" class="text-sm text-red-600">{{ form.errors.attendanceDuration }}</p>
                    </div>
                </CardContent>
            </Card>

            <Card>
                <CardHeader>
                    <!-- Updated Title -->
                    <CardTitle>Documentos Requeridos</CardTitle>
                    <!-- Updated Description -->
                    <CardDescription>Adjunta los archivos solicitados actualizados. Asegúrate que sean legibles.</CardDescription>
                </CardHeader>
                <CardContent class="space-y-6">
                    <div class="space-y-2">
                        <Label for="profilePhoto">Foto Personal Reciente (Tipo Carnet) <span class="text-red-500">*</span></Label>
                        <Input
                            id="profilePhoto"
                            type="file"
                            @input="form.profilePhoto = ($event.target as HTMLInputElement)?.files?.[0] ?? null"
                            accept=".jpg,.jpeg,.png"
                            required
                        />
                        <p class="text-muted-foreground text-sm">Formatos aceptados: JPG, PNG. Tamaño máx: 5MB.</p>
                        <p v-if="form.errors.profilePhoto" class="text-sm text-red-600">{{ form.errors.profilePhoto }}</p>
                        <Progress
                            v-if="form.progress && form.progress.percentage && form.progress.percentage < 100"
                            :model-value="form.progress.percentage"
                            class="w-full"
                        />
                    </div>

                    <div class="space-y-2">
                        <Label for="studentIdPhoto">Foto Cédula del Estudiante <span class="text-red-500">*</span></Label>
                        <Input
                            id="studentIdPhoto"
                            type="file"
                            @input="form.studentIdPhoto = ($event.target as HTMLInputElement)?.files?.[0] ?? null"
                            accept=".jpg,.jpeg,.png,.pdf"
                            required
                        />
                        <p class="text-muted-foreground text-sm">Formatos aceptados: JPG, PNG, PDF. Tamaño máx: 5MB.</p>
                        <p v-if="form.errors.studentIdPhoto" class="text-sm text-red-600">{{ form.errors.studentIdPhoto }}</p>
                    </div>

                    <div class="space-y-2">
                        <Label for="guardianIdPhoto">Foto Cédula del Representante <span class="text-red-500">*</span></Label>
                        <Input
                            id="guardianIdPhoto"
                            type="file"
                            @input="form.guardianIdPhoto = ($event.target as HTMLInputElement)?.files?.[0] ?? null"
                            accept=".jpg,.jpeg,.png,.pdf"
                            required
                        />
                        <p class="text-muted-foreground text-sm">Formatos aceptados: JPG, PNG, PDF. Tamaño máx: 5MB.</p>
                        <p v-if="form.errors.guardianIdPhoto" class="text-sm text-red-600">{{ form.errors.guardianIdPhoto }}</p>
                    </div>

                    <!-- === REMOVED FIELDS ===
                    <div class="space-y-2">
                        <Label for="salvationTestimony">Testimonio de Salvación <span class="text-red-500">*</span></Label>
                        ...
                    </div>

                    <div class="space-y-2">
                        <Label for="enrollmentPurpose">Propósito de Ingreso al Seminario <span class="text-red-500">*</span></Label>
                       ...
                    </div>

                    <div class="space-y-2">
                        <Label for="recommendationLetter">Carta de Recomendación Pastoral (Opcional)</Label>
                        ...
                    </div>
                    === END REMOVED FIELDS === -->
                </CardContent>
            </Card>

            <!-- === Section 5: Confirmation and Submission === -->
            <Card>
                <CardHeader>
                    <CardTitle>Confirmación y Envío</CardTitle>
                </CardHeader>
                <CardContent class="space-y-6 text-center">
                    <div class="space-y-4 rounded-md border p-4 text-left">
                        <p class="text-muted-foreground text-center text-sm">Antes de enviar, por favor lee y acepta el reglamento.</p>
                        <div class="text-center">
                            <Button type="button" variant="link" @click="isRulesDialogOpen = true" class="h-auto p-0 text-base">
                                Ver Reglamento del Seminario Juvenil
                            </Button>
                        </div>

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div class="flex items-start space-x-2 rounded-md border p-3">
                                <Checkbox id="studentAgreed" v-model="form.studentAgreedToRules" />
                                <div class="grid gap-1.5 leading-none">
                                    <Label for="studentAgreed" class="cursor-pointer font-medium">
                                        Confirmación del Estudiante <span class="text-red-500">*</span>
                                    </Label>
                                    <p class="text-muted-foreground text-xs">Confirmo que he leído y acepto el reglamento.</p>
                                    <p v-if="form.errors.studentAgreedToRules" class="text-sm text-red-600">{{ form.errors.studentAgreedToRules }}</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-2 rounded-md border p-3">
                                <Checkbox id="guardianAgreed" v-model="form.guardianAgreedToRules" />
                                <div class="grid gap-1.5 leading-none">
                                    <Label for="guardianAgreed" class="cursor-pointer font-medium">
                                        Confirmación del Representante <span class="text-red-500">*</span>
                                    </Label>
                                    <p class="text-muted-foreground text-xs">Confirmo que he leído y acepto el reglamento junto al estudiante.</p>
                                    <p v-if="form.errors.guardianAgreedToRules" class="text-sm text-red-600">
                                        {{ form.errors.guardianAgreedToRules }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Updated Text -->
                    <p class="text-muted-foreground">
                        Al enviar este formulario, confirmas que la información proporcionada es veraz y que ambos han aceptado el reglamento. La
                        re-inscripción puede requerir pasos adicionales en la oficina.
                    </p>
                    <Button
                        type="submit"
                        class="w-full md:w-1/2 lg:w-1/3"
                        :disabled="form.processing || !form.studentAgreedToRules || !form.guardianAgreedToRules"
                    >
                        <Loader2 v-if="form.processing" class="mr-2 h-4 w-4 animate-spin" />
                        <!-- Updated Button Text -->
                        {{ form.processing ? 'Enviando...' : 'Enviar Re-inscripción' }}
                    </Button>
                    <p v-if="form.recentlySuccessful" class="font-medium text-green-600">
                        ¡Formulario enviado con éxito! Nos pondremos en contacto pronto si es necesario.
                    </p>
                </CardContent>
            </Card>
        </form>
        <RulesDialog v-model:open="isRulesDialogOpen" />
    </section>
</template>

<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Calendar } from '@/components/ui/calendar';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover';
import { Progress } from '@/components/ui/progress';
import { RadioGroup, RadioGroupItem } from '@/components/ui/radio-group';
import { cn } from '@/lib/utils';
import { useForm } from '@inertiajs/vue3';
import { type DateValue, getLocalTimeZone, parseDate } from '@internationalized/date';
import { format } from 'date-fns';
import { es } from 'date-fns/locale';
import { Calendar as CalendarIcon, Loader2 } from 'lucide-vue-next';
import { computed, onMounted, ref, watch } from 'vue';
import RulesDialog from './RulesDialog.vue'; // Assuming RulesDialog is still needed

// Updated useForm definition
const form = useForm({
    studentFirstName: '',
    studentLastName: '',
    studentIdNumber: '',
    birthDate: null as string | null,
    studentPhoneNumber: '',
    studentEmail: '',
    studentGender: '' as 'male' | 'female',
    previousParticipations: 1, // Added field
    guardianFirstName: '',
    guardianLastName: '',
    guardianIdNumber: '',
    guardianPhoneNumber: '',
    guardianRelationship: '',
    churchName: '',
    churchCity: '',
    pastorName: '',
    attendanceDuration: '',
    studentAgreedToRules: false,
    guardianAgreedToRules: false,
    profilePhoto: null as File | null,
    studentIdPhoto: null as File | null,
    guardianIdPhoto: null as File | null,
    // Removed fields:
    // salvationTestimony: null as File | null,
    // enrollmentPurpose: null as File | null,
    // recommendationLetter: null as File | null,
});

const isCalendarOpen = ref(false);
const selectedDateValue = ref<DateValue>();
const isRulesDialogOpen = ref(false); // Keep if rules dialog is still relevant

const formattedDateForButton = computed(() => {
    // ... (keep existing date formatting logic)
    if (selectedDateValue.value) {
        try {
            const jsDate = selectedDateValue.value.toDate(getLocalTimeZone());
            return format(jsDate, 'PPP', { locale: es });
        } catch (error) {
            console.error('Error formatting date:', error);
            return 'Fecha inválida';
        }
    }
    return 'Selecciona una fecha';
});

watch(selectedDateValue, (newValue) => {
    // ... (keep existing date watch logic)
    if (newValue) {
        try {
            const jsDate = newValue.toDate(getLocalTimeZone());
            form.birthDate = format(jsDate, 'yyyy-MM-dd');
        } catch (error) {
            console.error('Error converting selected date:', error);
            form.birthDate = null;
        }
    } else {
        form.birthDate = null;
    }
    if (form.errors.birthDate) {
        form.clearErrors('birthDate');
    }
});

onMounted(() => {
    // ... (keep existing onMounted logic)
    if (form.birthDate && typeof form.birthDate === 'string') {
        try {
            selectedDateValue.value = parseDate(form.birthDate);
        } catch (e) {
            console.error('Error parsing initial date string:', form.birthDate, e);
            form.birthDate = null;
            selectedDateValue.value = undefined;
        }
    }

    const htmlElement = document.documentElement;
    if (htmlElement.classList.contains('dark')) {
        htmlElement.classList.remove('dark');
    }
    htmlElement.classList.add('light');
});

const handleDateUpdate = (value: DateValue | undefined) => {
    // ... (keep existing date update logic)
    if (value) {
        isCalendarOpen.value = false;
    }
};

const submit = () => {
    // Update the submission URL for recurrent students
    const submitUrl = '/inscription/recurrent'; // Changed URL

    console.log('Submitting recurrent form data:', form.data());

    form.post(submitUrl, {
        preserveScroll: true,
        preserveState: (page) => Object.keys(page.props.errors).length > 0,
        onSuccess: (page) => {
            console.log('Recurrent form submitted successfully!', page);
            // Optional: Reset form fields specifically needed for recurrent logic if any
        },
        onError: (errors) => {
            console.error('Recurrent form submission errors:', errors);
        },
        onFinish: () => {
            console.log('Recurrent form submission attempt finished.');
        },
    });
};
</script>

<style scoped>
/* Add any specific styles if needed */
</style>
