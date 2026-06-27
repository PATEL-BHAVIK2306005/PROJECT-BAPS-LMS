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
        Schema::table('users', function (Blueprint $table) {
            $table->index('status', 'idx_users_status');
        });
        Schema::table('enrollments', function (Blueprint $table) {
            $table->index('status', 'idx_enrollments_status');
        });
        Schema::table('gatepasses', function (Blueprint $table) {
            $table->index('status', 'idx_gatepasses_status');
        });
        Schema::table('leaves', function (Blueprint $table) {
            $table->index('status', 'idx_leaves_status');
        });
        Schema::table('password_approvals', function (Blueprint $table) {
            $table->index('status', 'idx_password_approvals_status');
        });
        Schema::table('fee_payments', function (Blueprint $table) {
            $table->index('status', 'idx_fee_payments_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('idx_users_status');
        });
        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropIndex('idx_enrollments_status');
        });
        Schema::table('gatepasses', function (Blueprint $table) {
            $table->dropIndex('idx_gatepasses_status');
        });
        Schema::table('leaves', function (Blueprint $table) {
            $table->dropIndex('idx_leaves_status');
        });
        Schema::table('password_approvals', function (Blueprint $table) {
            $table->dropIndex('idx_password_approvals_status');
        });
        Schema::table('fee_payments', function (Blueprint $table) {
            $table->dropIndex('idx_fee_payments_status');
        });
    }
};
