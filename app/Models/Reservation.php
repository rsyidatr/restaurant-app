<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'table_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'reservation_time',
        'party_size',
        'status',
        'special_requests'
    ];

    protected $casts = [
        'reservation_time' => 'datetime',
        'party_size' => 'integer'
    ];

    // Accessor untuk reservation_date
    public function getReservationDateAttribute()
    {
        return $this->reservation_time ? $this->reservation_time->format('Y-m-d') : null;
    }

    // Accessor untuk reservation_time dalam format waktu saja
    public function getReservationTimeFormattedAttribute()
    {
        return $this->reservation_time ? $this->reservation_time->format('H:i') : null;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function table()
    {
        return $this->belongsTo(Table::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('reservation_time', today());
    }
}
