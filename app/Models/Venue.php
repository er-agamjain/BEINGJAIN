<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
    protected $fillable = [
        'organiser_id',
        'city_id',
        'name',
        'description',
        'address',
        'phone',
        'email',
        'total_capacity',
        'seating_layout',
        'is_active'
    ];

    public function organiser()
    {
        return $this->belongsTo(User::class, 'organiser_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function seatCategories()
    {
        return $this->hasMany(SeatCategory::class);
    }

    public function showTimings()
    {
        return $this->hasMany(ShowTiming::class);
    }
}
