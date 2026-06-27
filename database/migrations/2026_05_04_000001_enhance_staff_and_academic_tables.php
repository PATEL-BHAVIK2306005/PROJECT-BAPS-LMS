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
        Schema::table('staff', function (Blueprint $table) {
            $table->json('positions')->nullable();
            $table->string('exam_role')->default('none'); // none, universal, department, assistant
            $table->foreignId('supervisor_id')->nullable()->constrained('staff')->onDelete('set null');
        });

        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('code')->nullable();
            $table->string('type')->default('theory'); // theory, pbl, lab
            $table->timestamps();
        });

        Schema::create('exam_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->nullable()->constrained('departments');
            $table->string('title');
            $table->date('date');
            $table->string('time_slot');
            $table->timestamps();
        });

        Schema::create('seating_arrangements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_schedule_id')->constrained('exam_schedules');
            $table->string('room_no');
            $table->integer('capacity');
            $table->json('student_ids')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seating_arrangements');
        Schema::dropIfExists('exam_schedules');
        Schema::dropIfExists('subjects');
        Schema::table('staff', function (Blueprint $table) {
            $table->dropColumn(['positions', 'exam_role', 'supervisor_id']);
        });
    }
};
