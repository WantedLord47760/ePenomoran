<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     * 
     * Creates audit log table for legal/governance compliance.
     * Tracks WHO did WHAT to WHICH letter and WHEN.
     */
    public function up(): void
    {
        Schema::create('surat_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surat_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Action type: 'created', 'updated', 'approved', 'rejected', 'deleted', 'restored'
            $table->string('action', 50);

            // Status change tracking
            $table->string('old_status', 10)->nullable();
            $table->string('new_status', 10)->nullable();

            // Optional notes/reason
            $table->text('notes')->nullable();

            // Request metadata for forensics
            $table->ipAddress('ip_address');
            $table->text('user_agent')->nullable();

            $table->timestamps();

            // Indexes for audit queries
            $table->index(['surat_id', 'created_at']);
            $table->index(['user_id', 'action']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_audit_logs');
    }
};
