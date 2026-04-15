<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShowTiming extends Model
{
    protected $fillable = [
        'event_id',
        'venue_id',
        'show_date_time',
        'duration_minutes',
        'available_seats',
        'booked_seats',
        'status',
        'notes'
    ];

    protected $casts = [
        'show_date_time' => 'datetime',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

    public function seats()
    {
        return $this->hasMany(Seat::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            $model->generateSeats();
        });
    }

    public function generateSeats()
    {
        // No venue assigned — skip seat generation
        if (!$this->venue_id) {
            return;
        }

        // Load venue with seat categories
        $this->load('venue.seatCategories');

        $venue = $this->venue;
        $seatCategories = $venue->seatCategories;

        if ($seatCategories->isEmpty()) {
            return; // No categories defined, can't generate seats
        }

        // Calculate total capacity
        $totalCapacity = $this->available_seats;
        $categoryCount = $seatCategories->count();

        // Distribute seats across categories evenly
        $seatsPerCategory = floor($totalCapacity / $categoryCount);
        $remainingSeats = $totalCapacity % $categoryCount;

        $seatNumber = 1;
        $rowNumber = 1;
        $columnNumber = 1;
        $maxColumnsPerRow = 12; // Standard theater row width

        foreach ($seatCategories as $index => $category) {
            // Allocate seats to this category
            $categorySeats = $seatsPerCategory + ($index < $remainingSeats ? 1 : 0);

            for ($i = 0; $i < $categorySeats; $i++) {
                Seat::create([
                    'show_timing_id' => $this->id,
                    'seat_category_id' => $category->id,
                    'seat_number' => 'S' . str_pad($seatNumber, 4, '0', STR_PAD_LEFT),
                    'row_number' => $rowNumber,
                    'column_number' => $columnNumber,
                    'status' => 'available',
                    'current_price' => null, // Will use category base_price
                ]);

                $seatNumber++;
                $columnNumber++;

                // Move to next row when we reach max columns
                if ($columnNumber > $maxColumnsPerRow) {
                    $columnNumber = 1;
                    $rowNumber++;
                }
            }
        }
    }
}
