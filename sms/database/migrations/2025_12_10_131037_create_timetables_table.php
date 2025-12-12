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
        Schema::create('timetables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')
               ->constrained()
               ->onDelete('cascade');
            $table->foreignId('class_level_id')
               ->constrained()
               ->onDelete('cascade');
            $table->foreignId('section_id')
               ->constrained()
               ->onDelete('cascade');
            $table->foreignId('subject_id')
               ->constrained()->onDelete('cascade');
            $table->foreignId('teacher_id')
               ->constrained('users')
               ->onDelete('cascade'); 
            $table->enum('weekday', ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']);
            $table->time('start_time');
            $table->time('end_time');
            $table->unique(['school_id', 'class_level_id', 'section_id', 'weekday', 'start_time'], 'tt_slot_unique');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('timetables');
    }
};
