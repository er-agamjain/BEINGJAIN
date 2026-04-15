<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeatCategory extends Model
{
    protected $fillable = [
        'venue_id',
        'name',
        'total_seats',
        'base_price',
        'color',
        'description'
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
    ];

    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

    public function seats()
    {
        return $this->hasMany(Seat::class);
    }
}
