<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TableHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'table_id',
        'reason',
        'description',
        'start_date',
        'end_date',
        'status'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime'
    ];

    public function table()
    {
        return $this->belongsTo(Table::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'inactive')
                     ->where(function($q) {
                         $q->whereNull('end_date')
                           ->orWhere('end_date', '>', now());
                     });
    }
}
