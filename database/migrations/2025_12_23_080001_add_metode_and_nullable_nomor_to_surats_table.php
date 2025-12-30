<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     * Adds metode_pembuatan and makes nomor fields nullable for approval-based numbering
     */
    public function up(): void
    {
        Schema::table('surats', function (Blueprint $table) {
            // Add metode_pembuatan column
            $table->enum('metode_pembuatan', ['Srikandi', 'TTE', 'Manual'])
                ->default('Manual')
                ->after('file_surat_original_name');
        });

        // Make nomor_urut nullable (for pending letters without number)
        Schema::table('surats', function (Blueprint $table) {
            $table->integer('nomor_urut')->nullable()->change();
        });

        // Make nomor_surat_full nullable
        Schema::table('surats', function (Blueprint $table) {
            $table->string('nomor_surat_full')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surats', function (Blueprint $table) {
            $table->dropColumn('metode_pembuatan');
        });

        // Revert to non-nullable (note: this may fail if there are NULL values)
        Schema::table('surats', function (Blueprint $table) {
            $table->integer('nomor_urut')->nullable(false)->change();
            $table->string('nomor_surat_full')->nullable(false)->change();
        });
    }
};
