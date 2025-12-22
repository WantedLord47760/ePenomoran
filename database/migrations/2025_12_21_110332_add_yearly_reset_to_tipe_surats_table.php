<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     * 
     * Adds yearly reset tracking to prevent race conditions
     * and enable automatic counter reset on year change.
     */
    public function up(): void
    {
        Schema::table('tipe_surats', function (Blueprint $table) {
            // Track the year of last number generation
            $table->year('last_reset_year')->nullable()->after('nomor_terakhir');

            // Add index for performance on reset checks
            $table->index(['id', 'last_reset_year']);
        });

        // Initialize existing records with current year
        DB::table('tipe_surats')->update(['last_reset_year' => date('Y')]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tipe_surats', function (Blueprint $table) {
            $table->dropIndex(['id', 'last_reset_year']);
            $table->dropColumn('last_reset_year');
        });
    }
};
