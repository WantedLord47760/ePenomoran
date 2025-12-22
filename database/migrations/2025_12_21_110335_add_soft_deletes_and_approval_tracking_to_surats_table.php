<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     * 
     * Adds soft deletes and approval tracking for audit compliance.
     */
    public function up(): void
    {
        Schema::table('surats', function (Blueprint $table) {
            // Soft delete support
            $table->softDeletes();
            $table->unsignedBigInteger('deleted_by')->nullable()->after('deleted_at');

            // Approval tracking
            $table->timestamp('approved_at')->nullable()->after('status');
            $table->unsignedBigInteger('approved_by')->nullable()->after('approved_at');

            // Rejection tracking
            $table->timestamp('rejected_at')->nullable()->after('approved_by');
            $table->unsignedBigInteger('rejected_by')->nullable()->after('rejected_at');
            $table->text('rejection_reason')->nullable()->after('rejected_by');

            // Foreign keys
            $table->foreign('deleted_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('rejected_by')->references('id')->on('users')->onDelete('set null');

            // Indexes for performance
            $table->index(['status', 'approved_at']);
            $table->index(['deleted_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surats', function (Blueprint $table) {
            $table->dropForeign(['deleted_by']);
            $table->dropForeign(['approved_by']);
            $table->dropForeign(['rejected_by']);
            $table->dropIndex(['status', 'approved_at']);
            $table->dropIndex(['deleted_at']);

            $table->dropColumn([
                'deleted_at',
                'deleted_by',
                'approved_at',
                'approved_by',
                'rejected_at',
                'rejected_by',
                'rejection_reason'
            ]);
        });
    }
};
