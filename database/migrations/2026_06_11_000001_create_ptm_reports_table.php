<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ptm_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->string('created_by_role');
            $table->string('created_by_name');
            $table->string('academic_term')->nullable();
            $table->string('category')->default('Academic'); // Academic, Behavior, Attendance, Exams
            $table->string('subject');
            $table->text('report_content');
            $table->text('parent_reply')->nullable();
            $table->timestamp('parent_replied_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ptm_reports');
    }
};
