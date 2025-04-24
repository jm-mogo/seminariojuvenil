import type { PageProps } from '@inertiajs/core';
import type { LucideIcon } from 'lucide-vue-next';
import type { Config } from 'ziggy-js';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    href: string;
    icon?: LucideIcon;
    isActive?: boolean;
}

export interface SharedData extends PageProps {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    ziggy: Config & { location: string };
    sidebarOpen: boolean;
}

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}

export interface Registration {
    /** The unique identifier for the registration (UUID). */
    id: string;
    /** Indicates if the student is registering for the first time or is returning. */
    registration_type: 'new' | 'recurrent';
    /** The current status of the payment for this registration. */
    payment_status: 'pending' | 'paid' | 'waived'; // Default is 'pending'
    /** Internal notes regarding the final decision, rejection reasons, etc. */
    final_decision_notes: string | null;
    // --- Student Details ---
    student_full_name: string;
    student_date_of_birth: string;
    student_gender: 'male' | 'female';
    student_identity_document_number: string;
    student_phone: string | null;
    student_email: string | null;

    // --- Guardian/Representative Details ---
    guardian_full_name: string;
    guardian_phone: string;
    guardian_relationship: string;
    // --- Church Details ---
    church_name: string;
    church_pastor_name: string;
    church_attendance_duration: string;
    previous_participations_count: number;
    doc_photo_path: string | null;
    doc_student_id_card_path: string | null;
    doc_guardian_id_card_path: string | null;
    doc_recommendation_path: string | null;
    doc_testimony_path: string | null;
    doc_purpose_statement_path: string | null;
    interview_scheduled_at: string | null;
    interview_notes: string | null;
    interview_status: 'pending' | 'checked' | 'scheduled' | 'completed' | 'accepted' | 'rejected'; // Default 'pending_scheduling' - Note: using values from comment in migration as they seem more descriptive than the raw enum list provided there. Adjust if needed.
    created_at: string;
    updated_at: string;
}

export type BreadcrumbItemType = BreadcrumbItem;
