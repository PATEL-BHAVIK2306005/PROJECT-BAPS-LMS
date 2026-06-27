<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            Schema::table('external_certifications', function (Blueprint $table) {
                $table->string('mime_type')->nullable();
                $table->binary('file_content')->nullable();
            });

            Schema::table('ipdc_assets', function (Blueprint $table) {
                $table->string('mime_type')->nullable();
                $table->binary('file_content')->nullable();
            });

            Schema::table('task_submissions', function (Blueprint $table) {
                $table->string('mime_type')->nullable();
                $table->binary('file_content')->nullable();
            });
        } else {
            Schema::table('external_certifications', function (Blueprint $table) {
                $table->string('mime_type')->nullable();
            });
            DB::statement('ALTER TABLE external_certifications ADD file_content LONGBLOB NULL');

            Schema::table('ipdc_assets', function (Blueprint $table) {
                $table->string('mime_type')->nullable();
            });
            DB::statement('ALTER TABLE ipdc_assets ADD file_content LONGBLOB NULL');

            Schema::table('task_submissions', function (Blueprint $table) {
                $table->string('mime_type')->nullable();
            });
            DB::statement('ALTER TABLE task_submissions ADD file_content LONGBLOB NULL');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ipdc_tables', function (Blueprint $table) {
            //
        });
    }
};
