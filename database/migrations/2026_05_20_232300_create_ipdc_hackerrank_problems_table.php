<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ipdc_hackerrank_problems', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->text('input_format')->nullable();
            $table->text('constraints')->nullable();
            $table->text('output_format')->nullable();
            $table->text('sample_input')->nullable();
            $table->text('sample_output')->nullable();
            $table->text('test_cases')->nullable(); // JSON data: [{"input":"...","output":"..."}]
            $table->string('difficulty')->default('Easy'); // Easy, Medium, Hard
            $table->integer('points')->default(100);
            $table->integer('created_by')->nullable(); // Staff ID
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ipdc_hackerrank_problems');
    }
};
