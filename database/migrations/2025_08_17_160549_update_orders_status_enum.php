<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing data first
        DB::statement("UPDATE orders SET status = 'preparing' WHERE status = 'processing'");
        DB::statement("UPDATE orders SET status = 'served' WHERE status = 'completed'");
        
        // Update enum status untuk orders
        DB::statement("ALTER TABLE orders DROP CONSTRAINT IF EXISTS orders_status_check");
        DB::statement("ALTER TABLE orders ALTER COLUMN status TYPE varchar(50)");
        DB::statement("ALTER TABLE orders ADD CONSTRAINT orders_status_check CHECK (status IN ('pending', 'preparing', 'ready', 'served', 'cancelled'))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE orders DROP CONSTRAINT IF EXISTS orders_status_check");
        DB::statement("ALTER TABLE orders ALTER COLUMN status TYPE varchar(50)");
        DB::statement("ALTER TABLE orders ADD CONSTRAINT orders_status_check CHECK (status IN ('pending', 'processing', 'ready', 'completed', 'cancelled'))");
        
        // Revert data
        DB::statement("UPDATE orders SET status = 'processing' WHERE status = 'preparing'");
        DB::statement("UPDATE orders SET status = 'completed' WHERE status = 'served'");
    }
};
