<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'show_timing_id',
        'name',
        'description',
        'price',
        'quantity',
        'quantity_sold',
        'ticket_type',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function showTiming(): BelongsTo
    {
        return $this->belongsTo(ShowTiming::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function getAvailableQuantity(): int
    {
        return $this->quantity - $this->quantity_sold;
    }
}
