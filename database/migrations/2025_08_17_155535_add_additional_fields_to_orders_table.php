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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('customer_email')->nullable()->after('customer_phone');
            $table->integer('subtotal')->nullable()->after('total_amount');
            $table->text('special_instructions')->nullable()->after('notes');
            $table->timestamp('confirmed_at')->nullable()->after('payment_date');
            $table->timestamp('started_cooking_at')->nullable()->after('confirmed_at');
            $table->timestamp('ready_at')->nullable()->after('started_cooking_at');
            $table->timestamp('served_at')->nullable()->after('ready_at');
            $table->timestamp('estimated_completion_time')->nullable()->after('served_at');
            $table->enum('priority', ['low', 'normal', 'high'])->default('normal')->after('estimated_completion_time');
            $table->foreignId('waiter_id')->nullable()->constrained('users')->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['waiter_id']);
            $table->dropColumn([
                'customer_email',
                'subtotal',
                'special_instructions',
                'confirmed_at',
                'started_cooking_at',
                'ready_at',
                'served_at',
                'estimated_completion_time',
                'priority',
                'waiter_id'
            ]);
        });
    }
};
