<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('time_capsules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->text('secret_message');
            $table->string('lock_type'); // 'date', 'level', 'xp', 'course'
            $table->date('unlock_date')->nullable();
            $table->integer('target_level')->nullable();
            $table->integer('target_xp')->nullable();
            $table->foreignId('target_course_id')->nullable()->constrained('courses')->onDelete('set null');
            $table->integer('staked_xp')->default(0);
            $table->string('status')->default('locked'); // 'locked', 'unlocked'
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('time_capsules');
    }
};
