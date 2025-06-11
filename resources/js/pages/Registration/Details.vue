<template>
    <Toaster />
    <!-- Título para la pestaña del navegador -->
    <Head :title="`Detalles de Inscripción - ${registration.student_full_name}`" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-4 md:p-6">
            <!-- Cabecera -->
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-semibold">Detalles de Inscripción</h1>
                <!-- Opcional: Botones de Acción (Editar, Aprobar, Rechazar, etc.) -->
            </div>

            <!-- Sección: Marcar como Revisado / Rechazar -->
            <div v-if="registration.interview_status == 'pending'" class="flex flex-col gap-4">
                Después de revisar la información, puede proceder a marcar como revisado o rechazar la inscripción.
                <div class="flex max-w-sm gap-4">
                    <Button variant="secondary">Rechazar</Button>
                    <!-- Add @click handler for reject action -->
                    <Button @click="handleCheck">Revisado</Button>
                </div>
            </div>

            <!-- Sección: Enviar Mensaje para Agendar Entrevista -->
            <div v-if="registration.interview_status == 'checked'" class="flex flex-col gap-4">
                <div class="bg-muted/40 flex flex-col gap-4 rounded-md border p-4">
                    <h2 class="text-lg font-semibold">Agendado Pendiente</h2>
                    <p class="text-sm">
                        Copie el siguiente mensaje y envíelo al estudiante para que pueda agendar su entrevista usando el enlace proporcionado.
                    </p>
                    <!-- Message Block -->
                    <div class="bg-background rounded-md border p-3">
                        <!-- The content is now primarily driven by the computed property 'interviewMessageText' -->
                        <p>
                            ¡Gracias por realizar la inscripción en línea del Seminario Juvenil! Ya hemos revisado sus datos. Para continuar, por
                            favor agende una entrevista usando el siguiente enlace único. Recuerde no compartirlo:
                            <strong class="font-semibold">
                                <a :href="generateAppointmentLink(registration.id)" target="_blank" rel="noopener noreferrer">LINK DE AGENDADO</a>
                            </strong>
                        </p>
                    </div>
                    <div class="flex items-center gap-2">
                        <Button variant="secondary" @click="copyInterviewMessage">
                            <IconClipboard class="mr-2 h-4 w-4" />
                            Copiar Mensaje Completo
                        </Button>
                    </div>
                </div>
            </div>

            <!-- Cuadrícula Principal de Detalles -->
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                <!-- Columna Izquierda (Estudiante, Representante, Iglesia) -->
                <div class="space-y-6 lg:col-span-2">
                    <!-- Resumen de la Inscripción -->
                    <SectionCard title="Resumen de la Inscripción">
                        <DetailItem label="ID de Inscripción" :value="registration.id" />
                        <DetailItem label="Tipo" :value="registration.registration_type">
                            <Badge :variant="'secondary'">
                                {{ registration.registration_type === 'new' ? 'Nueva' : 'Recurrente' }}
                            </Badge>
                        </DetailItem>
                        <DetailItem label="Estado del Pago" :value="registration.payment_status">
                            <Badge :variant="getPaymentStatusVariant(registration.payment_status)">
                                {{ capitalize(registration.payment_status) }}
                            </Badge>
                        </DetailItem>
                        <DetailItem
                            label="Participaciones Anteriores"
                            :value="registration.previous_participations_count"
                            v-if="registration.registration_type === 'recurrent'"
                        />
                        <DetailItem label="Fecha de Inscripción" :value="formatDate(registration.created_at)" />
                    </SectionCard>

                    <!-- Detalles del Estudiante -->
                    <SectionCard title="Detalles del Estudiante">
                        <DetailItem label="Nombre Completo" :value="registration.student_full_name" />
                        <DetailItem label="Fecha de Nacimiento" :value="formatDate(registration.student_date_of_birth)" />
                        <DetailItem label="Género" :value="registration.student_gender">
                            {{ capitalize(registration.student_gender) }}
                        </DetailItem>
                        <DetailItem label="Número de Identificación" :value="registration.student_identity_document_number" />
                        <DetailItem label="Teléfono" :value="registration.student_phone" placeholder="No Proporcionado" />
                        <DetailItem label="Correo Electrónico" :value="registration.student_email" placeholder="No Proporcionado" />
                    </SectionCard>

                    <!-- Detalles del Representante -->
                    <SectionCard title="Detalles del Representante">
                        <DetailItem label="Nombre Completo" :value="registration.guardian_full_name" />
                        <DetailItem label="Teléfono" :value="registration.guardian_phone" />
                        <DetailItem label="Parentesco" :value="registration.guardian_relationship" />
                    </SectionCard>

                    <!-- Detalles de la Iglesia -->
                    <SectionCard title="Detalles de la Iglesia">
                        <DetailItem label="Nombre de la Iglesia" :value="registration.church_name" />
                        <DetailItem label="Nombre del Pastor" :value="registration.church_pastor_name" />
                        <DetailItem label="Tiempo de Asistencia" :value="registration.church_attendance_duration" />
                    </SectionCard>
                </div>

                <!-- Columna Derecha (Documentos, Entrevista, Notas) -->
                <div class="space-y-6">
                    <!-- Documentos -->
                    <SectionCard title="Documentos Subidos">
                        <DocumentLink label="Foto" :path="registration.doc_photo_path" :registration-id="registration.id" document-type="photo" />
                        <DocumentLink
                            label="Cédula/ID del Estudiante"
                            :path="registration.doc_student_id_card_path"
                            :registration-id="registration.id"
                            document-type="student_id"
                        />
                        <DocumentLink
                            label="Cédula/ID del Representante"
                            :path="registration.doc_guardian_id_card_path"
                            :registration-id="registration.id"
                            document-type="guardian_id"
                        />
                        <DocumentLink
                            label="Carta de Recomendación"
                            :path="registration.doc_recommendation_path"
                            :registration-id="registration.id"
                            document-type="recommendation"
                        />
                        <DocumentLink
                            label="Testimonio de Salvación"
                            :path="registration.doc_testimony_path"
                            :registration-id="registration.id"
                            document-type="testimony"
                        />
                        <DocumentLink
                            label="Declaración de Propósito"
                            :path="registration.doc_purpose_statement_path"
                            :registration-id="registration.id"
                            document-type="purpose"
                        />
                        <p v-if="!hasDocuments" class="text-muted-foreground pt-2 text-sm">No se subieron documentos.</p>
                    </SectionCard>

                    <!-- Detalles de la Entrevista -->
                    <SectionCard title="Detalles de la Entrevista">
                        <DetailItem label="Estado">
                            <Badge :variant="getInterviewStatusVariant(registration.interview_status)">
                                {{ formatInterviewStatus(registration.interview_status) }}
                            </Badge>
                        </DetailItem>
                        <DetailItem label="Fecha Programada" :value="formatDate(registration.interview_scheduled_at)" placeholder="No Programada" />
                        <DetailItem
                            label="Notas de la Entrevista"
                            :value="registration.interview_notes"
                            placeholder="No hay notas registradas."
                            :isBlock="true"
                        />
                    </SectionCard>

                    <!-- Notas Internas -->
                    <SectionCard title="Notas Internas de Decisión">
                        <DetailItem
                            label="Notas"
                            :value="registration.final_decision_notes"
                            placeholder="No hay notas finales registradas."
                            :isBlock="true"
                        />
                    </SectionCard>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, type Registration } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { Clipboard as IconClipboard } from 'lucide-vue-next';
import { computed } from 'vue';
// *** Import Toast from vue-sonner ***
import DetailItem from '@/components/common/DetailItem.vue';
import DocumentLink from '@/components/common/DocumentLink.vue';
import SectionCard from '@/components/common/SectionCard.vue';
import { Toaster } from '@/components/ui/sonner'; // Componentes Ayudantes
import { toast } from 'vue-sonner';
// --- Props ---
interface Props {
    registration: Registration & {
        // *** Add potential link property if it comes from the backend ***
        interview_scheduling_link?: string | null;
    };
}
const props = defineProps<Props>();
const registration = computed(() => props.registration);

// --- Breadcrumbs ---
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Inscripciones',
        href: '/admin/registrations',
    },
    {
        title: 'Detalles',
        href: `/admin/registrations/${registration.value.id}`,
    },
];

// --- Actions ---
const handleCheck = () => {
    router.put(
        `/admin/registrations/${registration.value.id}/status`,
        {
            interview_status: 'checked', // Change status to 'checked'
        },
        {
            preserveScroll: true, // Keep scroll position after update
            onSuccess: () => {
                console.log('Registration status updated to checked.');
                toast.success('Inscripción marcada como revisada.'); // Optional success toast
            },
            onError: (errors) => {
                console.error('Error updating registration status:', errors);
                toast.error('Error al actualizar el estado. Revise la consola.'); // Optional error toast
            },
        },
    );
};

// *** Computed Property for the Interview Message ***
const interviewMessageText = computed(() => {
    // Replace placeholder with actual data if available, otherwise use a placeholder
    const link = generateAppointmentLink(registration.value.id) || '[ENLACE PENDIENTE]'; // Use a clear placeholder
    // Construct the full message text
    return `¡Gracias por realizar la inscripción en línea del Seminario Juvenil! \nYa hemos revisado sus datos. Para continuar, por favor agende una entrevista usando el siguiente enlace único. \n\nRecuerde no compartirlo: ${link}`;
});

// *** Updated Function to Copy Message and Show Toast ***
const copyInterviewMessage = () => {
    const textToCopy = interviewMessageText.value;

    navigator.clipboard
        .writeText(textToCopy)
        .then(() => {
            // Use toast for success feedback
            toast.success('¡Mensaje copiado al portapapeles!');
        })
        .catch((err) => {
            console.error('Error al copiar al portapapeles:', err);
            // Use toast for error feedback
            toast.error('Error al copiar el mensaje. Revise la consola.');
        });
};

// --- Funciones Ayudantes / Formatting ---
const capitalize = (s: string | null | undefined): string => {
    if (!s) return '';
    if (s === 'male') return 'Masculino';
    if (s === 'female') return 'Femenino';
    // Generic capitalize for other cases (like payment status)
    return s.charAt(0).toUpperCase() + s.slice(1);
};

const formatDate = (dateString: string | null | undefined): string | null => {
    if (!dateString) return null;
    try {
        return new Date(dateString).toLocaleDateString(undefined, {
            // Use browser locale
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            timeZone: 'UTC', // Assume dates from DB are UTC, format based on user browser locale
        });
    } catch (e) {
        console.error('Error formateando fecha:', dateString, e);
        return dateString; // Return original on error
    }
};

const formatInterviewStatus = (status: Registration['interview_status']): string => {
    switch (status) {
        case 'pending':
            return 'Pendiente de Revisión';
        case 'checked':
            return 'Revisado (Esperando Agenda)';
        case 'scheduled':
            return 'Entrevista Programada';
        case 'completed':
            return 'Entrevista Completada';
        case 'accepted':
            return 'Aceptado';
        case 'rejected':
            return 'Rechazado';
        default:
            return 'Estado Desconocido';
    }
};

// Determina la variante del Badge según el estado
type BadgeVariant = 'default' | 'secondary' | 'destructive' | 'outline';

const getPaymentStatusVariant = (status: Registration['payment_status']): BadgeVariant => {
    switch (status) {
        case 'paid':
            return 'default'; // Default (often green) for success
        case 'pending':
            return 'secondary'; // Neutral/Gray
        case 'waived':
            return 'outline'; // Informational
        default:
            return 'secondary';
    }
};

const getInterviewStatusVariant = (status: Registration['interview_status']): BadgeVariant => {
    switch (status) {
        case 'accepted':
            return 'default'; // Success
        case 'rejected':
            return 'destructive'; // Error/Rejected
        case 'scheduled':
            return 'outline'; // Scheduled
        case 'completed':
            return 'secondary'; // Completed (neutral)
        case 'checked':
            return 'secondary'; // Checked (neutral step)
        case 'pending':
            return 'secondary'; // Pending (neutral)
        default:
            return 'secondary';
    }
};

// Verifica si hay algún documento subido
const hasDocuments = computed(() => {
    return !!(
        registration.value.doc_photo_path ||
        registration.value.doc_student_id_card_path ||
        registration.value.doc_guardian_id_card_path ||
        registration.value.doc_recommendation_path ||
        registration.value.doc_testimony_path ||
        registration.value.doc_purpose_statement_path
    );
});

const generateAppointmentLink = (registrationId: string): string => {
    const baseUrl = window.location.origin; // URL base de la aplicación
    return `${baseUrl}/inscription/appointment/${registrationId}`;
};
</script>

<style scoped>
/* Estilos específicos si son necesarios */
</style>
