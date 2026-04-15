<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventCommunity extends Model
{
    protected $fillable = [
        'name',
        'description',
        'is_active'
    ];

    /**
     * Get the events that belong to this community
     */
    public function events()
    {
        return $this->hasMany(Event::class, 'community', 'name');
    }
}
