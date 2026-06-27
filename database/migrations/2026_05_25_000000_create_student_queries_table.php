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
        // 1. Add status to staff table if it doesn't exist
        if (Schema::hasTable('staff') && !Schema::hasColumn('staff', 'status')) {
            Schema::table('staff', function (Blueprint $table) {
                $table->string('status')->default('active'); // active, dnd, out_of_station
            });
        }

        // 2. Create student_queries table
        Schema::create('student_queries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->string('category'); // Schedule, Class Cancel, Fees Issue, LMS Issue, Other/Document Request
            $table->string('title');
            $table->text('description');
            $table->string('assigned_type'); // staff, cr
            $table->foreignId('assigned_staff_id')->nullable()->constrained('staff')->onDelete('cascade');
            $table->foreignId('assigned_cr_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('status')->default('pending'); // pending, solved, unsolved
            $table->boolean('salary_cut_applied')->default(false);
            $table->decimal('salary_cut_amount', 10, 2)->default(10000.00);
            $table->boolean('fine_applied')->default(false);
            $table->decimal('fine_amount', 10, 2)->default(100.00);
            $table->boolean('is_waived')->default(false);
            $table->decimal('original_penalty_amount', 10, 2)->default(0.00);
            $table->text('resolution_notes')->nullable();
            $table->string('resolved_by_name')->nullable();
            $table->string('resolved_by_role')->nullable();
            $table->text('resolved_by_signature')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_queries');

        if (Schema::hasTable('staff') && Schema::hasColumn('staff', 'status')) {
            Schema::table('staff', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }
};
