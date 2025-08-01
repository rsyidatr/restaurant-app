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
        Schema::table('reservations', function (Blueprint $table) {
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone');
            $table->datetime('reservation_time');
            $table->integer('party_size');
            $table->string('status')->default('pending');
            $table->text('special_requests')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn([
                'customer_name', 
                'customer_email', 
                'customer_phone', 
                'reservation_time', 
                'party_size', 
                'status', 
                'special_requests'
            ]);
        });
    }
};
