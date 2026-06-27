<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ipdc_hackerrank_submissions', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('problem_id');
            $table->text('code');
            $table->string('language');
            $table->string('status')->default('Pending'); // Passed, Failed, Compile Error, Pending
            $table->integer('passed_test_cases')->default(0);
            $table->integer('total_test_cases')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ipdc_hackerrank_submissions');
    }
};
