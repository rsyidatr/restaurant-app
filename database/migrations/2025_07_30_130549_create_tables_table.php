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
        Schema::create('tables', function (Blueprint $table) {
            $table->id();
            $table->string('table_number')->unique(); // Nomor meja
            $table->integer('capacity'); // Kapasitas orang
            $table->enum('status', ['available', 'occupied', 'reserved', 'cleaning'])->default('available');
            $table->text('description')->nullable(); // Deskripsi lokasi meja
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tables');
    }
};
