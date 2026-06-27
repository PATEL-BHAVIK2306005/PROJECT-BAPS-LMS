<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('code_review_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->unsignedBigInteger('mentor_id')->nullable();
            $table->string('title');
            $table->text('description');
            $table->mediumText('code_snippet');
            $table->string('language');
            $table->string('category');
            $table->string('status')->default('pending'); // pending, reviewed
            $table->timestamps();
        });

        Schema::create('code_review_feedbacks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_id')->constrained('code_review_requests')->onDelete('cascade');
            $table->unsignedBigInteger('reviewer_id');
            $table->integer('rating')->default(5);
            $table->text('comments');
            $table->string('signature_type')->default('mapped'); // mapped, manual
            $table->text('signature_data')->nullable(); // SVG or string signature representation
            $table->string('badge_hash')->unique();
            $table->timestamps();
        });

        Schema::create('student_privilege_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('feedback_id')->constrained('code_review_feedbacks')->onDelete('cascade');
            $table->string('privilege_type');
            $table->text('justification');
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->unsignedBigInteger('processed_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_privilege_applications');
        Schema::dropIfExists('code_review_feedbacks');
        Schema::dropIfExists('code_review_requests');
    }
};
