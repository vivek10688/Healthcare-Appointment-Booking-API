<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('healthcare_professional_id')
                  ->constrained()
                  ->onDelete('cascade');
            $table->dateTime('appointment_start_time');
            $table->dateTime('appointment_end_time');
            $table->enum('status', ['booked', 'completed', 'cancelled'])
                  ->default('booked');
            $table->text('notes')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes for performance
            $table->index(['user_id', 'status']);
            $table->index(['healthcare_professional_id', 'appointment_start_time'], 'appt_prof_start_idx');
            $table->index('appointment_start_time');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
