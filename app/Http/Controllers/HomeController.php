<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventCategory;
use App\Models\City;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Get active cities from database
        $cities = City::where('is_active', true)
            ->orderBy('name')
            ->pluck('name')
            ->toArray();

        // Fallback to default cities if none in DB
        if (empty($cities)) {
            $cities = ['Mumbai', 'Delhi', 'Bengaluru', 'Hyderabad', 'Chennai', 'Kolkata', 'Gurgaon'];
        }

        // Get event categories
        $categoriesFromDb = EventCategory::where('is_active', true)
            ->orderBy('category_name')
            ->pluck('category_name')
            ->toArray();

        // Fallback to default categories
        $categories = !empty($categoriesFromDb) 
            ? $categoriesFromDb 
            : ['Movies', 'Concerts', 'Sports', 'Theatre', 'Workshops', 'Comedy', 'Festivals'];

        // Get trending events (published, upcoming, ordered by bookings count or recent)
        $trending = Event::where('status', 'published')
            ->where('event_date', '>=', now())
            ->with(['eventCategory', 'bookings'])
            ->withCount('bookings')
            ->orderBy('bookings_count', 'desc')
            ->take(4)
            ->get()
            ->map(function ($event) {
                // Use event base_price instead of ticket pricing
                $price = $event->base_price ?? 0;

                return [
                    'name' => $event->title,
                    'city' => $event->location ?? 'Online',
                    'genre' => $event->eventCategory->name ?? 'Event',
                    'rating' => 4.5 + (rand(0, 9) / 10),
                    'price' => $price,
                    'tag' => $this->getEventTag($event),
                    'id' => $event->id,
                ];
            })
            ->toArray();

        // Fallback trending if none found
        if (empty($trending)) {
            $trending = [
                ['name' => 'Nebula Nights', 'city' => 'Mumbai', 'genre' => 'Sci-Fi', 'rating' => 4.9, 'price' => 480, 'tag' => 'IMAX Laser'],
                ['name' => 'Symphony Under Stars', 'city' => 'Delhi', 'genre' => 'Concert', 'rating' => 4.8, 'price' => 750, 'tag' => 'Live'],
                ['name' => 'Rivalry Weekend', 'city' => 'Bengaluru', 'genre' => 'Sports', 'rating' => 4.7, 'price' => 620, 'tag' => 'Derby'],
                ['name' => 'Late Night Laughter', 'city' => 'Hyderabad', 'genre' => 'Comedy', 'rating' => 4.6, 'price' => 399, 'tag' => 'Stand-up'],
            ];
        }

        // Static offers for now (can be moved to DB later)
        $offers = [
            ['title' => 'Gold Lounge', 'subtitle' => 'VIP recliners with gourmet snacks', 'badge' => 'New'],
            ['title' => 'Midnight Premieres', 'subtitle' => 'Limited seats · early access', 'badge' => 'Hot'],
            ['title' => 'Bank Wednesdays', 'subtitle' => 'Flat 20% off on cards', 'badge' => 'Offer'],
        ];

        return view('home', compact('cities', 'categories', 'offers', 'trending'));
    }

    private function getEventTag(Event $event): string
    {
        // Determine tag based on event properties
        if ($event->category === 'Concert' || $event->category === 'Music') {
            return 'Live';
        }

        if (str_contains(strtolower($event->name), 'imax') || str_contains(strtolower($event->description ?? ''), 'imax')) {
            return 'IMAX';
        }

        if ($event->bookings_count > 50) {
            return 'Hot';
        }

        if ($event->created_at && $event->created_at->isAfter(now()->subDays(7))) {
            return 'New';
        }

        return 'Featured';
    }
}
