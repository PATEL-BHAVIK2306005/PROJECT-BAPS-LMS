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
            if (!Schema::hasColumn('users', 'program')) {
                $table->string('program')->nullable(); // Diploma, Bachelors, Masters
            }
            if (!Schema::hasColumn('users', 'year')) {
                $table->integer('year')->nullable(); // 1st, 2nd, etc.
            }
            if (!Schema::hasColumn('users', 'semester')) {
                $table->integer('semester')->nullable(); // 1-8
            }
            if (!Schema::hasColumn('users', 'class_section')) {
                $table->string('class_section')->nullable(); // Class 01, Class 02, etc. (Or Branch)
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['program', 'year', 'semester', 'class_section']);
        });
    }
};
