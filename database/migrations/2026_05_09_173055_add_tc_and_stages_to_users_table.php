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
            $table->integer('application_stage')->default(1);
            $table->boolean('tc_accepted')->default(false);
            $table->text('digital_signature')->nullable();
            $table->string('generated_password')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['application_stage', 'tc_accepted', 'digital_signature', 'generated_password']);
        });
    }
};
