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
        Schema::table('payments', function (Blueprint $table) {
            $table->unsignedBigInteger('order_id');
            $table->decimal('amount', 10, 2);
            $table->string('payment_method');
            $table->string('status')->default('pending');
            $table->string('transaction_id')->nullable();
            $table->datetime('paid_at')->nullable();
            
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['order_id']);
            $table->dropColumn([
                'order_id', 
                'amount', 
                'payment_method', 
                'status', 
                'transaction_id', 
                'paid_at'
            ]);
        });
    }
};
