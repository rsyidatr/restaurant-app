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
            $table->string('order_number')->unique(); // Nomor order
            $table->string('session_id')->nullable(); // untuk guest users
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade'); // untuk authenticated users
            $table->foreignId('table_id')->constrained()->onDelete('cascade'); // nomor meja
            $table->enum('order_type', ['dine_in', 'take_away', 'delivery'])->default('dine_in');
            $table->enum('status', ['pending', 'preparing', 'ready', 'served', 'cancelled'])->default('pending');
            $table->enum('payment_status', ['pending', 'paid', 'failed'])->default('pending');
            $table->integer('total_amount'); // total harga
            $table->integer('tax_amount')->default(0); // pajak
            $table->integer('service_charge')->default(0); // biaya layanan
            $table->integer('grand_total'); // total keseluruhan
            $table->string('payment_method')->nullable(); // metode pembayaran
            $table->text('notes')->nullable(); // catatan pesanan
            $table->timestamp('order_date');
            $table->timestamp('payment_date')->nullable();
            $table->timestamps();
            
            // Index untuk performa
            $table->index(['session_id', 'user_id']);
            $table->index('order_number');
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
