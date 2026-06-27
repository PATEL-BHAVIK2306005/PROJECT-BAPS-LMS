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
        \Illuminate\Support\Facades\DB::table('staff')->insert([
            'name' => 'Sadhu Adbhutanand Das',
            'role' => 'admin',
            'email' => 'sadhu.adbhutanand@baps.ac.in',
            'unique_code' => 'BAPS-HOSTEL-001',
            'password' => bcrypt('baps@admin2026'),
            'access_level' => 200, // Admins get 200%
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \Illuminate\Support\Facades\DB::table('staff')->where('email', 'sadhu.adbhutanand@baps.ac.in')->delete();
    }
};
