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
        Schema::table('menu_items', function (Blueprint $table) {
            if (!Schema::hasColumn('menu_items', 'price')) {
                $table->decimal('price', 10, 2)->after('description');
            }
            if (!Schema::hasColumn('menu_items', 'image_url')) {
                $table->string('image_url')->nullable()->after('price');
            }
            if (!Schema::hasColumn('menu_items', 'is_available')) {
                $table->boolean('is_available')->default(true)->after('image_url');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('menu_items', function (Blueprint $table) {
            $table->dropColumn(['price', 'image_url', 'is_available']);
        });
    }
};
