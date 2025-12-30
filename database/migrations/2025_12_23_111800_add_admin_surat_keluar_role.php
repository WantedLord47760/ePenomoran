<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add admin_surat_keluar to users role enum
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'pemimpin', 'operator', 'pegawai', 'admin_surat_masuk', 'admin_surat_keluar') DEFAULT 'pegawai'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert role enum
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'pemimpin', 'operator', 'pegawai', 'admin_surat_masuk') DEFAULT 'pegawai'");
    }
};
