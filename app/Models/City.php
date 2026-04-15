<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_active',
        'image',
        'is_featured'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean'
    ];

    public function venues()
    {
        return $this->hasMany(Venue::class);
    }
    
public function create()
{
    $cities = City::where('is_active', true)
                ->orderBy('name')
                ->pluck('name', 'id');

    return view('organiser.events.create', compact('cities'));
}
}
