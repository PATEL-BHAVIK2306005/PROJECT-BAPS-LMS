<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('external_certifications', function (Blueprint $row) {
            $row->id();
            $row->foreignId('user_id')->constrained()->onDelete('cascade');
            $row->string('platform'); // NPTEL, HackerRank, etc.
            $row->string('title');
            $row->string('file_path')->nullable();
            $row->text('credential_link')->nullable();
            $row->date('issue_date')->nullable();
            $row->enum('verification_status', ['pending', 'verified', 'rejected'])->default('pending');
            $row->string('verified_by')->nullable();
            $row->text('admin_remarks')->nullable();
            $row->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('external_certifications');
    }
};
