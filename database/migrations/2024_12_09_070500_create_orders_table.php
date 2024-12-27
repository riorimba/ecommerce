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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->uuid('order_id')->unique();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->enum('status', ['pending', 'paid', 'shipped', 'completed', 'cancelled'])->default('pending');
            $table->integer('total');
            $table->string('payment_type')->nullable();
            $table->timestamp('transaction_time')->nullable();
            $table->string('transaction_id')->nullable();
            $table->timestamp('pay_at')->nullable();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
