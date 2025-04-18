<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('registrations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->enum('registration_type', ['new', 'recurring'])->comment('new or recurring')->index();
            $table->string('status')->default('draft')->comment('draft, submitted, interview_scheduled, approved, etc.')->index();
            $table->string('payment_status')->default('pending')->comment('pending, paid, waived')->index();
            $table->text('final_decision_notes')->nullable()->comment('Internal notes, rejection reasons, etc.');

            // student Details (Repeated for every registration)
            $table->string('student_full_name');
            $table->date('student_date_of_birth');
            $table->enum('student_gender', ['male', 'female']);
            $table->string('student_identity_document_number');
            $table->string('student_phone')->nullable();
            $table->string('student_email')->nullable(); // Cannot enforce uniqueness per student easily here

            // Representative Details (Repeated for every registration)
            $table->string('guardian_full_name');
            $table->string('guardian_phone');
            $table->string('guardian_relationship');

            // Church Details (Repeated for every registration)
            $table->string('church_name');
            $table->string('church_pastor_name');
            $table->string('church_attendance_duration');

            // Recurring student Specifics
            $table->smallInteger('previous_participations_count');

            // Document Paths (Nullable, paths relative to storage disk)
            $table->string('doc_photo_path')->nullable()->comment('Path to student photo');
            $table->string('doc_student_id_card_path')->nullable()->comment('Path to ID card photo/PDF');
            $table->string('doc_guardian_id_card_path')->nullable()->comment('Path to ID card photo/PDF');
            $table->string('doc_recommendation_path')->nullable()->comment('Path to pastor recommendation (if needed)');
            $table->string('doc_testimony_path')->nullable()->comment('Path to salvation testimony (Word/PDF)');
            $table->string('doc_purpose_statement_path')->nullable()->comment('Path to purpose statement (Word/PDF)');
            // Note: Docs to bring physically aren't stored as paths here initially.

            // Interview Details
            $table->timestamp('interview_scheduled_at')->nullable()->index();
            $table->text('interview_notes')->nullable()->comment('Notes taken during the interview');
            $table->enum('interview_status', ['pending_scheduling', 'scheduled', 'completed', 'accepted', 'rejected'])->default('pending_scheduling')->comment('Interview status: pending_scheduling, scheduled, completed, accepted, rejected')->index();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
