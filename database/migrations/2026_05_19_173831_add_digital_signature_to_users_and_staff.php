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
            if (!Schema::hasColumn('users', 'digital_signature')) {
                $table->text('digital_signature')->nullable();
            }
        });
        Schema::table('staff', function (Blueprint $table) {
            if (!Schema::hasColumn('staff', 'digital_signature')) {
                $table->text('digital_signature')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'digital_signature')) {
                $table->dropColumn('digital_signature');
            }
        });
        Schema::table('staff', function (Blueprint $table) {
            if (Schema::hasColumn('staff', 'digital_signature')) {
                $table->dropColumn('digital_signature');
            }
        });
    }
};
