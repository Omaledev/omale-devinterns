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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')
               ->constrained()
               ->onDelete('cascade');
            $table->foreignId('student_id')
               ->constrained('student_profiles')
               ->onDelete('cascade');
            $table->foreignId('class_level_id')
               ->constrained()
               ->onDelete('cascade');; 
            $table->foreignId('teacher_id')
               ->constrained('users')
               ->onDelete('cascade');
            $table->date('date');
            $table->enum('status', ['PRESENT', 'ABSENT', 'LATE']);
            $table->timestamps();
            // Prevent duplicate attendance for the same student on the same day
            $table->unique(['student_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
