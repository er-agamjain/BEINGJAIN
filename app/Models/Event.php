<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'organiser_id',
        'title',
        'description',
        'terms_conditions',
        'location',
        'navigation_location',
        'address',
        'city_id',
        'all_day',
        'event_date',
        'start_time',
        'end_time',
        'base_price',
        'is_free',
        'community',
        'gacchh',
        'tags',
        'status',
        'image',
        'category_id',
    ];

    protected $casts = [
        'organiser_id' => 'integer',
        'city_id' => 'integer',
        'category_id' => 'integer',
        'event_date' => 'datetime',
    ];

    public function organiser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'organiser_id');
    }

    public function eventCategory(): BelongsTo
    {
        return $this->belongsTo(EventCategory::class, 'category_id');
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function showTimings(): HasMany
    {
        return $this->hasMany(ShowTiming::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function venues()
    {
        return $this->hasManyThrough(Venue::class, ShowTiming::class, 'event_id', 'id', 'id', 'venue_id');
    }

    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image) {
            return null;
        }

        return asset('public/storage/' . $this->image);
    }
}
