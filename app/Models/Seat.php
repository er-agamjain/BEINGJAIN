<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    protected $fillable = [
        'seat_category_id',
        'show_timing_id',
        'seat_number',
        'row_number',
        'column_number',
        'status',
        'current_price'
    ];

    protected $casts = [
        'current_price' => 'decimal:2',
    ];

    public function seatCategory()
    {
        return $this->belongsTo(SeatCategory::class);
    }

    public function showTiming()
    {
        return $this->belongsTo(ShowTiming::class);
    }
}
