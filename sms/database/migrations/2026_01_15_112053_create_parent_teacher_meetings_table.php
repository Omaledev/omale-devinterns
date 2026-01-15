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
        Schema::create('parent_teacher_meetings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained('teacher_profiles')->onDelete('cascade');
            $table->string('subject'); 
            $table->dateTime('scheduled_at');
            $table->enum('status', ['pending', 'approved', 'declined', 'completed'])->default('pending');
            $table->text('notes')->nullable(); 
            $table->boolean('is_virtual')->default(false);
            $table->string('meeting_link')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parent_teacher_meetings');
    }
};
