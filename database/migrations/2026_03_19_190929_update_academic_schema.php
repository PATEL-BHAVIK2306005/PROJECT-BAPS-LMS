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
        Schema::table('courses', function (Blueprint $table) {
            $table->foreignId('department_id')->nullable()->constrained('departments')->onDelete('set null');
            $table->string('program')->nullable(); // B.Tech, M.Tech, etc.
            $table->string('year')->nullable();    // 1st, 2nd, etc.
            $table->string('semester')->nullable(); // 1-8
        });

        Schema::table('enrollments', function (Blueprint $table) {
            $table->string('class_section')->nullable()->after('semester'); // 01-05
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropColumn(['department_id', 'program', 'year', 'semester']);
        });

        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropColumn(['program', 'year', 'semester', 'class_section']);
        });
    }
};
