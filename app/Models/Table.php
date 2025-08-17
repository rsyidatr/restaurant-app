<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    use HasFactory;

    protected $fillable = [
        'table_number',
        'capacity',
        'status',
        'description'
    ];

    protected $casts = [
        'capacity' => 'integer'
    ];

    // Relationship dengan Orders
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    
    // Current order (order yang sedang berlangsung)
    public function currentOrder()
    {
        return $this->hasOne(Order::class)->whereIn('status', ['pending', 'processing', 'ready']);
    }
    
    // Relationship dengan Reservations
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    // Scope untuk meja yang tersedia
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    // Check apakah meja tersedia
    public function isAvailable()
    {
        return $this->status === 'available';
    }
}
