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
        Schema::table('payments', function (Blueprint $table) {
            $table->enum('status', ['pending', 'approved', 'declined'])->default('approved')->after('amount');
            $table->string('proof_file_path')->nullable()->after('reference_number');
            $table->string('bursar_remarks')->nullable()->after('proof_file_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['status', 'proof_file_path', 'bursar_remarks']);
        });
    }
};
