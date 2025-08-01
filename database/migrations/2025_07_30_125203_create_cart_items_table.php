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
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->nullable(); // untuk guest users
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade'); // untuk authenticated users
            $table->integer('menu_item_id'); // ID dari menu item
            $table->string('menu_name'); // nama menu
            $table->integer('price'); // harga per item
            $table->integer('quantity'); // jumlah item
            $table->string('image')->nullable(); // gambar menu
            $table->timestamps();
            
            // Index untuk performa yang lebih baik
            $table->index(['session_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
