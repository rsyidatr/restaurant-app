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
        // Drop foreign key constraint first
        Schema::table('menu_items', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
        });
        
        // Drop existing menu_categories table
        Schema::dropIfExists('menu_categories');
        
        // Recreate with proper structure
        Schema::create('menu_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });
        
        // Re-add foreign key constraint
        Schema::table('menu_items', function (Blueprint $table) {
            $table->foreign('category_id')->references('id')->on('menu_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop foreign key constraint
        Schema::table('menu_items', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
        });
        
        Schema::dropIfExists('menu_categories');
        
        // Restore old structure
        Schema::create('menu_categories', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
        
        // Re-add foreign key constraint
        Schema::table('menu_items', function (Blueprint $table) {
            $table->foreign('category_id')->references('id')->on('menu_categories')->onDelete('cascade');
        });
    }
};
