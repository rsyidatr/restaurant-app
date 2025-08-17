<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'session_id',
        'user_id',
        'table_id',
        'customer_name',
        'customer_phone',
        'customer_email',
        'order_type',
        'status',
        'payment_status',
        'total_amount',
        'subtotal',
        'tax_amount',
        'service_charge',
        'grand_total',
        'payment_method',
        'notes',
        'special_instructions',
        'order_date',
        'payment_date',
        'confirmed_at',
        'started_cooking_at',
        'ready_at',
        'served_at',
        'estimated_completion_time',
        'priority'
    ];

    protected $casts = [
        'total_amount' => 'integer',
        'subtotal' => 'integer',
        'tax_amount' => 'integer',
        'service_charge' => 'integer',
        'grand_total' => 'integer',
        'order_date' => 'datetime',
        'payment_date' => 'datetime',
        'confirmed_at' => 'datetime',
        'started_cooking_at' => 'datetime',
        'ready_at' => 'datetime',
        'served_at' => 'datetime',
        'estimated_completion_time' => 'datetime'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function table()
    {
        return $this->belongsTo(Table::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function waiter()
    {
        return $this->belongsTo(User::class, 'waiter_id');
    }

    // Generate order number
    public static function generateOrderNumber()
    {
        $date = now()->format('Ymd');
        $lastOrder = self::whereDate('created_at', now())->latest()->first();
        $sequence = $lastOrder ? (int) substr($lastOrder->order_number, -4) + 1 : 1;
        
        return $date . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    // Scope untuk order berdasarkan session atau user
    public function scopeForSessionOrUser($query, $sessionId, $userId = null)
    {
        if ($userId) {
            return $query->where('user_id', $userId);
        }
        return $query->where('session_id', $sessionId)->whereNull('user_id');
    }
}
