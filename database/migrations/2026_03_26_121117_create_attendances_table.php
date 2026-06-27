<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // The student
            $table->date('date');
            $table->enum('status', ['present', 'absent', 'late'])->default('present');
            $table->unsignedBigInteger('marked_by')->nullable(); // Admin, Faculty, or CR
            $table->foreign('marked_by')->references('id')->on('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['course_id', 'user_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
