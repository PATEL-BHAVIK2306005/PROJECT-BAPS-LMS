<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('parent_1_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('parent_2_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('parent_3_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('parent_4_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('hostel_swami_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('hostel_room_no')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['parent_1_id']);
            $table->dropForeign(['parent_2_id']);
            $table->dropForeign(['parent_3_id']);
            $table->dropForeign(['parent_4_id']);
            $table->dropForeign(['hostel_swami_id']);
            
            $table->dropColumn([
                'parent_1_id',
                'parent_2_id',
                'parent_3_id',
                'parent_4_id',
                'hostel_swami_id',
                'hostel_room_no'
            ]);
        });
    }
};
