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
            $table->index('role', 'idx_users_role');
        });

        Schema::table('messages', function (Blueprint $table) {
            $table->index('section', 'idx_messages_section');
        });

        Schema::table('progress', function (Blueprint $table) {
            $table->index(['user_id', 'completed'], 'idx_progress_user_completed');
        });

        Schema::table('quiz_attempts', function (Blueprint $table) {
            $table->index(['user_id', 'passed'], 'idx_quiz_attempts_user_passed');
        });

        Schema::table('ipdc_hackerrank_submissions', function (Blueprint $table) {
            $table->index(['user_id', 'status'], 'idx_hr_submissions_user_status');
        });

        Schema::table('student_queries', function (Blueprint $table) {
            $table->index('student_id', 'idx_student_queries_student_id');
        });

        Schema::table('ptm_reports', function (Blueprint $table) {
            $table->index('student_id', 'idx_ptm_reports_student_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('idx_users_role');
        });

        Schema::table('messages', function (Blueprint $table) {
            $table->dropIndex('idx_messages_section');
        });

        Schema::table('progress', function (Blueprint $table) {
            $table->dropIndex('idx_progress_user_completed');
        });

        Schema::table('quiz_attempts', function (Blueprint $table) {
            $table->dropIndex('idx_quiz_attempts_user_passed');
        });

        Schema::table('ipdc_hackerrank_submissions', function (Blueprint $table) {
            $table->dropIndex('idx_hr_submissions_user_status');
        });

        Schema::table('student_queries', function (Blueprint $table) {
            $table->dropIndex('idx_student_queries_student_id');
        });

        Schema::table('ptm_reports', function (Blueprint $table) {
            $table->dropIndex('idx_ptm_reports_student_id');
        });
    }
};
