<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'menu_item_id',
        'menu_name',
        'price',
        'quantity',
        'image',
        'notes'
    ];

    protected $casts = [
        'price' => 'integer',
        'quantity' => 'integer',
        'menu_item_id' => 'integer'
    ];

    // Relationship dengan Order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Hitung total harga untuk item ini
    public function getTotalAttribute()
    {
        return $this->price * $this->quantity;
    }
}
