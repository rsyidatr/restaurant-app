<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'user_id',
        'menu_item_id',
        'menu_name',
        'price',
        'quantity',
        'image'
    ];

    protected $casts = [
        'price' => 'integer',
        'quantity' => 'integer',
        'menu_item_id' => 'integer'
    ];

    // Relationship dengan User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Hitung total harga untuk item ini
    public function getTotalAttribute()
    {
        return $this->price * $this->quantity;
    }

    // Scope untuk mendapatkan cart berdasarkan session atau user
    public function scopeForSessionOrUser($query, $sessionId, $userId = null)
    {
        if ($userId) {
            return $query->where('user_id', $userId);
        }
        return $query->where('session_id', $sessionId)->whereNull('user_id');
    }
}
