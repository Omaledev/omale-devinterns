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
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')
              ->constrained('users')
              ->onDelete('cascade');
            $table->foreignId('class_level_id')
              ->constrained()
              ->onDelete('cascade');
            $table->foreignId('subject_id')
              ->constrained()->onDelete('cascade');
            $table->foreignId('assessment_type_id')
              ->constrained()
              ->onDelete('cascade'); 
            $table->foreignId('teacher_id')
               ->constrained('users'); 
            $table->foreignId('academic_session_id')
               ->constrained();
            $table->foreignId('term_id')
               ->constrained(); 
            $table->integer('score'); 
            $table->boolean('is_locked')->default(false); // For locking grades 
            $table->timestamps();
            // Prevent duplicate entries for the same assessment
            $table->unique(['student_id', 'subject_id', 'assessment_type_id', 'term_id', 'academic_session_id'], 'unique_grade_entry');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
