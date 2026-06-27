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
        Schema::create('circulars', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->string('category'); // academic, exams, administrative, student_cr, urgent
            $table->string('created_by_name');
            $table->string('created_by_role');
            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->string('signature_type')->default('mapped'); // mapped, manual
            $table->string('manual_signature_name')->nullable();
            $table->string('manual_signature_designation')->nullable();
            $table->longText('manual_signature_svg')->nullable(); // stored as SVG or custom raw text/signature style
            $table->timestamps();
        });

        Schema::create('lms_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->string('type'); // lms_notification, circular, approvals, qa_notification, faculty_notice, news, urgent_news
            $table->string('created_by_name');
            $table->string('created_by_role');
            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('circulars');
        Schema::dropIfExists('lms_notifications');
    }
};
