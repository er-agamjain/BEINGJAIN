<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventCategory extends Model
{
    protected $fillable = [
        'category_name',
        'community',
        'gacchh',
        'tags',
        'description',
        'is_active'
    ];

    /**
     * Get the events that belong to this category
     */
    public function events()
    {
        return $this->hasMany(Event::class, 'category_id');
    }
}
