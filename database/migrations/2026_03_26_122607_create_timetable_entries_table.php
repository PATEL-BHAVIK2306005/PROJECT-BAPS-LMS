<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('timetable_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('timetable_id')->constrained()->cascadeOnDelete();
            $table->string('day_of_week'); // Monday, Tuesday, etc.
            $table->integer('slot'); // 1 to 6
            $table->integer('duration')->default(1); // 1 = 1 hour, 2 = 2 hours lab
            $table->string('subject')->nullable();
            $table->string('faculty')->nullable();
            $table->string('room')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('timetable_entries');
    }
};
