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
        Schema::create('fee_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('fee_type')->default('Library and LMS');
            $table->decimal('amount', 10, 2);
            $table->string('token_number', 4);
            $table->string('status')->default('pending'); // pending, paid
            $table->string('payment_method')->nullable();
            $table->string('transaction_id')->nullable();
            $table->date('payment_date')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('payer_name')->nullable();
            $table->string('approval_field_1')->nullable(); // e.g. Accounts Clear
            $table->string('approval_field_2')->nullable(); // e.g. Final Audit
            $table->text('remarks')->nullable();
            $table->integer('processed_by_id')->nullable();
            $table->string('processed_by_role')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fee_payments');
    }
};
