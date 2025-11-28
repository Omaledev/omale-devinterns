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
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')
               ->constrained('schools')
               ->onDelete('cascade');
            $table->foreignId('user_id')
               ->constrained('users')
               ->onDelete('cascade'); 
            $table->string('title');
            $table->text('content');
            $table->enum('target_audience', ['all', 'teachers', 'students', 'parents', 'bursars'])->default('all');
            $table->json('specific_classes')->nullable(); 
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
