<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ipdc_assets', function (Blueprint $row) {
            $row->id();
            $row->string('title');
            $row->enum('type', ['workbook', 'solution', 'resource'])->default('resource');
            $row->string('file_path');
            $row->string('uploaded_by')->nullable();
            $row->string('status')->default('active');
            $row->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ipdc_assets');
    }
};
