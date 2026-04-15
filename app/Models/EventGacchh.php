<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventGacchh extends Model
{
    protected $table = 'event_gacchs';
    
    protected $fillable = [
        'name',
        'description',
        'is_active'
    ];

    /**
     * Get the events that belong to this gacchh
     */
    public function events()
    {
        return $this->hasMany(Event::class, 'gacchh', 'name');
    }
}
