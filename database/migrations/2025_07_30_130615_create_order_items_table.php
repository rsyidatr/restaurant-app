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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->integer('menu_item_id'); // ID dari menu item
            $table->string('menu_name'); // nama menu
            $table->integer('price'); // harga per item saat order
            $table->integer('quantity'); // jumlah item
            $table->string('image')->nullable(); // gambar menu
            $table->text('notes')->nullable(); // catatan khusus untuk item
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
