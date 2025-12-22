<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     * 
     * Adds performance indexes and data integrity constraints.
     */
    public function up(): void
    {
        Schema::table('surats', function (Blueprint $table) {
            // Unique constraint on letter number (business rule enforcement)
            $table->unique('nomor_surat_full', 'unique_letter_number');

            // Performance indexes for common queries
            $table->index('tanggal_surat');
            $table->index(['tipe_surat_id', 'tanggal_surat']);
            $table->index(['user_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surats', function (Blueprint $table) {
            $table->dropUnique('unique_letter_number');
            $table->dropIndex(['tanggal_surat']);
            $table->dropIndex(['tipe_surat_id', 'tanggal_surat']);
            $table->dropIndex(['user_id', 'status']);
        });
    }
};
