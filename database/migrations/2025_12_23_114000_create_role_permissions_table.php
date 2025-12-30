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
        Schema::create('role_permissions', function (Blueprint $table) {
            $table->id();
            $table->string('role'); // Role name (not 'admin' - admin always has full access)
            $table->string('permission'); // Permission key like 'surat_keluar.view'
            $table->boolean('enabled')->default(false);
            $table->timestamps();

            $table->unique(['role', 'permission']); // Each role-permission pair is unique
            $table->index('role'); // Fast lookup by role
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_permissions');
    }
};
