<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('staff', function (Blueprint $table) {
            $table->string('profile_photo')->nullable()->after('email');
            $table->longText('profile_photo_data')->nullable()->after('profile_photo');
            $table->string('profile_photo_mime', 50)->nullable()->after('profile_photo_data');
        });
    }

    public function down(): void
    {
        Schema::table('staff', function (Blueprint $table) {
            $table->dropColumn(['profile_photo', 'profile_photo_data', 'profile_photo_mime']);
        });
    }
};
