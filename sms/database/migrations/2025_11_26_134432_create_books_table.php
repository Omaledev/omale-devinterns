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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')
               ->constrained('schools')
               ->onDelete('cascade');
            $table->foreignId('teacher_id')
               ->constrained('users')
               ->onDelete('cascade');
            $table->foreignId('subject_id')
               ->constrained('subjects')
               ->onDelete('cascade');
            $table->foreignId('class_level_id')
               ->constrained('class_levels')
               ->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('file_path');
            $table->string('file_name');
            $table->string('file_size');
            $table->string('file_type');
            $table->integer('download_count')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
