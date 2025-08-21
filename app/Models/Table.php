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

    // Relationship dengan TableHistory
    public function histories()
    {
        return $this->hasMany(TableHistory::class);
    }

    // Scope untuk meja yang tersedia
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available')
                     ->whereDoesntHave('histories', function($q) {
                         $q->where('status', 'inactive')
                           ->where(function($subQ) {
                               $subQ->whereNull('end_date')
                                    ->orWhere('end_date', '>', now());
                           });
                     });
    }

    // Scope untuk meja yang bisa dipesen (tidak sedang dalam riwayat nonaktif)
    public function scopeBookable($query)
    {
        return $query->whereDoesntHave('histories', function($q) {
            $q->where('status', 'inactive')
              ->where(function($subQ) {
                  $subQ->whereNull('end_date')
                       ->orWhere('end_date', '>', now());
              });
        });
    }

    // Check apakah meja tersedia
    public function isAvailable()
    {
        return $this->status === 'available';
    }
}
