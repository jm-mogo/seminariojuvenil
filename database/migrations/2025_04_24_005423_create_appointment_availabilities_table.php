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
        Schema::create('appointment_availabilities', function (Blueprint $table) {
            $table->id();
            $table->date('date'); // Stores the available date (e.g., 2024-08-15)
            $table->time('time'); // Stores the available time slot (e.g., 09:00:00)
            $table->foreignUuid('registration_id')->nullable()->constrained('registrations')->onDelete('cascade');
            $table->timestamps(); // created_at and updated_at

            $table->unique(['date', 'time']);

            // Index for faster date lookups
            $table->index('date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointment_availabilities');
    }
};
