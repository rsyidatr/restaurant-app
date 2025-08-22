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
        // Drop the existing constraint first
        DB::statement("ALTER TABLE users DROP CONSTRAINT IF EXISTS users_role_check");
        
        // Update existing 'pelanggan' to 'customer'
        DB::statement("UPDATE users SET role = 'customer' WHERE role = 'pelanggan'");
        
        // Change column type to varchar and add new constraint
        DB::statement("ALTER TABLE users ALTER COLUMN role TYPE varchar(255)");
        DB::statement("ALTER TABLE users ADD CONSTRAINT users_role_check CHECK (role IN ('admin', 'customer', 'pelayan', 'koki'))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to old enum
        DB::statement("ALTER TABLE users DROP CONSTRAINT IF EXISTS users_role_check");
        DB::statement("UPDATE users SET role = 'pelanggan' WHERE role = 'customer'");
        DB::statement("ALTER TABLE users ADD CONSTRAINT users_role_check CHECK (role IN ('pelanggan', 'pelayan', 'koki'))");
    }
};
