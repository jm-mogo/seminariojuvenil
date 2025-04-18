<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids; // Import HasUuids
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    use HasFactory, HasUuids; // Use HasUuids

    /**
     * The attributes that are mass assignable.
     *
     * We include all fields that we intend to set via Registration::create()
     * Exclude 'id' if using HasUuids trait as it handles it.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'registration_type',
        'status',
        'payment_status',
        // 'final_decision_notes', // Usually set later
        'student_full_name',
        'student_date_of_birth',
        'student_gender',
        'student_identity_document_number',
        'student_phone',
        'student_email',
        'guardian_full_name',
        // 'guardian_identity_document_number' // Not in schema
        'guardian_phone',
        'guardian_relationship',
        'church_name',
        // 'church_city', // Not in schema
        'church_pastor_name',
        'church_attendance_duration',
        'previous_participations_count',
        'agreed_to_rules_online',
        'doc_photo_path',
        'doc_student_id_card_path',
        'doc_guardian_id_card_path',
        'doc_recommendation_path',
        'doc_testimony_path',
        'doc_purpose_statement_path',
        // 'interview_scheduled_at', // Usually set later
        // 'interview_notes', // Usually set later
        // 'interview_status', // Set by default or later
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'student_date_of_birth' => 'date',
        'interview_scheduled_at' => 'datetime',
        'agreed_to_rules_online' => 'boolean',
        'previous_participations_count' => 'integer',
    ];

    /**
     * Get the columns that should receive a unique identifier.
     * Required when using HasUuids trait and the primary key is not 'id'.
     * Remove if your primary key IS 'id'.
     *
     * @return array<int, string>
     */
    // public function uniqueIds(): array
    // {
    //     return ['uuid']; // If your primary key column name is 'uuid'
    // }

    /**
     * Get the primary key for the model.
     * Required if your primary key is not 'id'.
     *
     * @return string
     */
    // public function getKeyName()
    // {
    //     return 'uuid'; // If your primary key column name is 'uuid'
    // }

    /**
     * Get the auto-incrementing key type.
     * Required if your primary key is not an integer.
     *
     * @return string
     */
    // public function getKeyType()
    // {
    //     return 'string';
    // }

    /**
     * Indicate if the IDs are auto-incrementing.
     * Required if your primary key is not auto-incrementing.
     *
     * @var bool
     */
    // public $incrementing = false;

}
