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
        Schema::create('classroom_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')
               ->constrained()
               ->onDelete('cascade');
            $table->foreignId('teacher_id')
                ->constrained('teacher_profiles')
                ->onDelete('cascade');
            $table->foreignId('class_level_id')
                ->constrained()
                ->onDelete('cascade');
            $table->foreignId('section_id')->nullable()
                ->constrained()
                ->onDelete('cascade');
            $table->foreignId('subject_id')
                ->constrained()
                ->onDelete('cascade');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            // This is to prevent duplicate assignment
            $table->unique(['teacher_id', 'class_level_id', 'section_id', 'subject_id'], 'unique_assignment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classroom_assignments');
    }
};
