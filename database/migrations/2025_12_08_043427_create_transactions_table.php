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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('client_reference')->unique();
            $table->string('transaction_id')->nullable();
            $table->string('customer_name');
            $table->string('customer_number');
            $table->string('network');
            $table->decimal('amount', 10, 2);
            $table->text('message')->nullable();
            $table->string('status')->default('pending'); // pending, success, error
            $table->string('response_code')->nullable();
            $table->json('response_body')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
