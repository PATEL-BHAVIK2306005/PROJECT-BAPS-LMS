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
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('subject_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('exam_title')->default('University Examination 2026');
            $table->decimal('internal_marks', 8, 2)->default(0); // Max 60
            $table->decimal('external_marks_raw', 8, 2)->default(0); // Raw marks out of 100
            $table->decimal('external_marks_final', 8, 2)->default(0); // Converted to 40
            $table->decimal('total_obtained', 8, 2)->default(0); // internal + external_final
            $table->integer('total_max')->default(100);
            $table->string('grade');
            $table->text('remarks')->nullable();
            $table->string('status')->default('published');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};
