<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     * Adds pegawai-related fields to users table
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nip', 20)->nullable()->unique()->after('email');
            $table->string('no_hp', 15)->nullable()->after('nip');
            $table->string('jabatan')->nullable()->after('no_hp');
            $table->string('pangkat')->nullable()->after('jabatan');
            $table->enum('bidang', [
                'Sekretariat',
                'Bidang TIK dan Persandian',
                'Bidang IKPS',
                'Bidang Aptika'
            ])->nullable()->after('pangkat');
        });

        // Update role enum to include 'pegawai'
        // For MySQL, we need to modify the enum
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'operator', 'pemimpin', 'pegawai') DEFAULT 'pegawai'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nip', 'no_hp', 'jabatan', 'pangkat', 'bidang']);
        });

        // Revert role enum
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'operator', 'pemimpin') DEFAULT 'operator'");
    }
};
