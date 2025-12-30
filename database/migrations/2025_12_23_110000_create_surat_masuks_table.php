<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('surat_masuks', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_surat'); // Manual input
            $table->date('tanggal_surat');
            $table->string('jenis_surat'); // Free text input
            $table->string('judul_surat');
            $table->text('isi_surat')->nullable();
            $table->text('disposisi_pimpinan')->nullable();
            $table->date('tanggal_disposisi')->nullable();
            $table->enum('status_tindak_lanjut', ['pending', 'proses', 'selesai'])->default('pending');
            $table->string('posisi_tindak_lanjut')->nullable(); // Free text
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Who input this
            $table->timestamps();
        });

        // Add admin_surat_masuk to users role enum
        // We'll modify the column to include the new role
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'pemimpin', 'operator', 'pegawai', 'admin_surat_masuk') DEFAULT 'pegawai'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_masuks');

        // Revert role enum
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'pemimpin', 'operator', 'pegawai') DEFAULT 'pegawai'");
    }
};
