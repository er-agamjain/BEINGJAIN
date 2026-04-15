<?php

namespace App\Http\Controllers\User;

use App\Models\Event;
use App\Models\EventCategory;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::where('status', 'published');

        // Search
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('location', 'like', '%' . $request->search . '%');
            });
        }

        // Location (City)
        if ($request->filled('city')) {
            $query->where('location', 'like', '%' . $request->city . '%');
        }

        // Community (Multi-select)
        if ($request->filled('communities') && is_array($request->communities)) {
            $query->whereIn('community', $request->communities);
        }

        // Gacchh (Multi-select)
        if ($request->filled('gacchhs') && is_array($request->gacchhs)) {
            $query->whereIn('gacchh', $request->gacchhs);
        }

        // Event Category (Multi-select)
        if ($request->filled('categories') && is_array($request->categories)) {
            $query->whereIn('category_id', $request->categories);
        } elseif ($request->filled('category')) {
             // Handle legacy single select or fix the value issue
             // If value is name, we might need to find id, but let's assume we fix the view to send ID.
             $query->where('category_id', $request->category);
        }

        // Date Filter
        if ($request->filled('date_filter')) {
            switch ($request->date_filter) {
                case 'today':
                    $query->whereDate('event_date', now());
                    break;
                case 'tomorrow':
                    $query->whereDate('event_date', now()->addDay());
                    break;
                case 'this_week':
                    $query->whereBetween('event_date', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'this_weekend':
                    $query->whereBetween('event_date', [now()->endOfWeek()->subDay(), now()->endOfWeek()]);
                    break;
                case 'custom':
                    if ($request->filled('start_date') && $request->filled('end_date')) {
                        $query->whereBetween('event_date', [$request->start_date, $request->end_date]);
                    }
                    break;
            }
        } elseif ($request->filled('date')) {
             $query->whereDate('event_date', $request->date);
        }

        // Event Type
        if ($request->filled('event_type')) {
             $type = $request->event_type;
             if (is_array($type)) {
                 $type = $type[0] ?? null;
             }
             
             if ($type === 'online') {
                 $query->where('location', 'like', '%Online%');
             } elseif ($type === 'offline') {
                 $query->where('location', 'not like', '%Online%');
             }
        }

        // Booking Type
        if ($request->filled('booking_type')) {
             $type = $request->booking_type;
             if (is_array($type)) {
                 $type = $type[0] ?? null;
             }
             
             if ($type === 'free') {
                 $query->where('is_free', true);
             } elseif ($type === 'paid') {
                 $query->where('is_free', false);
             }
        }

        $events = $query->with('organiser', 'city')->paginate(12);
        
        // Filter Data
        $categories = EventCategory::where('is_active', true)->pluck('category_name', 'id');
        $cities = \App\Models\City::where('is_active', true)->orderBy('name')->get();
        $featuredCities = $cities->where('is_featured', true)->values();
        $communities = Event::distinct()->whereNotNull('community')->where('community', '!=', '')->pluck('community');
        $gacchhs = Event::distinct()->whereNotNull('gacchh')->where('gacchh', '!=', '')->pluck('gacchh');

        return view('user.events.index', compact('events', 'categories', 'cities', 'featuredCities', 'communities', 'gacchhs'));
    }

    public function show(Event $event)
    {
        if ($event->status !== 'published') {
            abort(404);
        }

        $event->load([
            'organiser',
            'city',
            'showTimings' => function($query) {
                $query->where('show_date_time', '>=', now())
                      ->where('status', 'scheduled')
                      ->with(['venue.seatCategories', 'seats'])
                      ->orderBy('show_date_time');
            }
        ]);

        // Get seat availability data for the first show timing
        $seatMapData = null;
        if ($event->showTimings->isNotEmpty()) {
            $firstShow = $event->showTimings->first();
            if ($firstShow->venue && $firstShow->venue->seatCategories->isNotEmpty()) {
                $seatMapData = [
                    'venue' => $firstShow->venue,
                    'categories' => $firstShow->venue->seatCategories,
                    'availability' => $this->calculateSeatAvailability($firstShow)
                ];
            }
        }

        return view('user.events.show', compact('event', 'seatMapData'));
    }

    private function calculateSeatAvailability($showTiming)
    {
        $availability = [];
        
        foreach ($showTiming->venue->seatCategories as $category) {
            $totalSeats = $category->total_seats;
            $bookedSeats = $showTiming->seats()
                ->where('seat_category_id', $category->id)
                ->whereIn('status', ['booked', 'reserved'])
                ->count();
            
            $availability[$category->id] = [
                'total' => $totalSeats,
                'booked' => $bookedSeats,
                'available' => $totalSeats - $bookedSeats,
                'percentage' => $totalSeats > 0 ? round((($totalSeats - $bookedSeats) / $totalSeats) * 100) : 0
            ];
        }
        
        return $availability;
    }
}
