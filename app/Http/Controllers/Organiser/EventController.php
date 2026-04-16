<?php

namespace App\Http\Controllers\Organiser;

use App\Models\Event;
use App\Models\EventCategory;
use App\Models\EventCommunity;
use App\Models\EventGacchh;
use App\Models\EventTag;
use App\Models\City;
use App\Models\ShowTiming;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index()
    {
        $events = Auth::user()->events()->with('city')->paginate(15);
        return view('organiser.events.index', compact('events'));
    }

    public function create()
    {
        $categories = \App\Models\EventCategory::where('is_active', true)->pluck('category_name', 'id');
        
        // Get unique values for community, gacchh, and tags
        //$communities = \App\Models\EventCategory::where('is_active', true)->whereNotNull('community')->distinct()->pluck('community');
        //$gacchhs = \App\Models\EventCategory::where('is_active', true)->whereNotNull('gacchh')->distinct()->pluck('gacchh');
        //$tagsList = \App\Models\EventCategory::where('is_active', true)->whereNotNull('tags')->distinct()->pluck('tags');
        
        // Get values from their dedicated tables
        $communities = EventCommunity::where('is_active', true)->pluck('name');
        $gacchhs = EventGacchh::where('is_active', true)->pluck('name');
        $tagsList = EventTag::where('is_active', true)->pluck('name');
        
        // ✅ ADD THIS
        $cities = City::where('is_active', true)
                ->orderBy('name')
                ->pluck('name', 'id');
        
        return view('organiser.events.create', compact('categories', 'communities', 'gacchhs', 'tagsList','cities'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'               => 'required|string|max:255',
            'description'         => 'required|string',
            'location'            => 'nullable|string|max:255',
            'navigation_location' => 'nullable|url|max:2048',
            'address'             => 'nullable|string|max:500',
            'city_id'             => 'nullable|exists:cities,id',
            'all_day'             => 'nullable|boolean',
            'event_date'          => 'required|date|after:today',
            'start_time'          => 'required|date_format:H:i',
            'end_time'            => 'required_unless:all_day,1|nullable|date_format:H:i',
            'is_free'             => 'boolean',
            'base_price'          => 'required_if:is_free,false|nullable|numeric|min:0',
            'category_id'         => 'nullable|exists:event_categories,id',
            'community'           => 'nullable|string|max:255',
            'gacchh'              => 'nullable|string|max:255',
            'tags'                => 'nullable|string|max:255',
            'image'               => 'nullable|image|max:2048',
        ]);

        $validated['organiser_id'] = Auth::id();
        $event = Event::create($validated);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('events', 'public');
            $event->update(['image' => $path]);
        }

        return redirect()->route('organiser.events.show', $event)->with('success', 'Event created');
    }

    public function show(Event $event)
    {
        if ((int)$event->organiser_id !== (int)Auth::id()) {
            abort(403);
        }

        $event->load('city');
        return view('organiser.events.show', compact('event'));
    }

    public function edit(Event $event)
    {
        if ((int)$event->organiser_id !==(int) Auth::id()) {
            abort(403);
        }

            $event->load(['showTimings.tickets', 'showTimings.venue', 'city']);
        $categories  = \App\Models\EventCategory::where('is_active', true)->pluck('category_name', 'id');
        $communities = EventCommunity::where('is_active', true)->pluck('name');
        $gacchhs     = EventGacchh::where('is_active', true)->pluck('name');
        $tagsList    = EventTag::where('is_active', true)->pluck('name');
        $cities      = City::where('is_active', true)->orderBy('name')->pluck('name', 'id');
        $venues      = \App\Models\Venue::where('organiser_id', Auth::id())->orderBy('name')->get();

        return view('organiser.events.edit', compact('event', 'categories', 'communities', 'gacchhs', 'tagsList', 'cities', 'venues'));
    }

    public function update(Request $request, Event $event)
    {
        if ((int)$event->organiser_id !==(int) Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title'               => 'required|string|max:255',
            'description'         => 'required|string',
            'location'            => 'nullable|string|max:255',
            'navigation_location' => 'nullable|url|max:2048',
            'address'             => 'nullable|string|max:500',
            'city_id'             => 'nullable|exists:cities,id',
            'all_day'             => 'nullable|boolean',
            'event_date'          => 'required|date',
            'start_time'          => 'required|date_format:H:i',
            'end_time'            => 'required_unless:all_day,1|nullable|date_format:H:i',
            'is_free'             => 'boolean',
            'base_price'          => 'required_if:is_free,false|nullable|numeric|min:0',
            'category_id'         => 'nullable|exists:event_categories,id',
            'community'           => 'nullable|string|max:255',
            'gacchh'              => 'nullable|string|max:255',
            'tags'                => 'nullable|string|max:255',
            'image'               => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('events', 'public');
        }

        $event->update($validated);

        return redirect()->route('organiser.events.show', $event)->with('success', 'Event updated');
    }

    public function destroy(Event $event)
    {
        if ((int)$event->organiser_id !==(int) Auth::id()) {
            abort(403);
        }

        $event->delete();
        return redirect()->route('organiser.events.index')->with('success', 'Event deleted');
    }

    public function publish(Event $event)
    {
        if ((int)$event->organiser_id !==(int) Auth::id()) {
            abort(403);
        }

        $event->update(['status' => 'published']);
        return redirect()->route('organiser.events.show', $event)->with('success', 'Event published successfully');
    }

    public function storeTiming(Request $request, Event $event)
    {
        if ((int)$event->organiser_id !== (int)Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'venue_id'         => 'required|exists:venues,id',
            'show_date_time'   => 'required|date',
            'duration_minutes' => 'required|integer|min:1',
            'available_seats'  => 'required|integer|min:1',
            'notes'            => 'nullable|string|max:500',
        ]);

        // Verify organiser owns the venue
        $venue = \App\Models\Venue::findOrFail($validated['venue_id']);
        if ((int)$venue->organiser_id !== (int)Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $validated['event_id'] = $event->id;
        $validated['status']   = 'scheduled';

        ShowTiming::create($validated);

        return redirect()->route('organiser.events.edit', $event)->with('success', 'Show timing added successfully!');
    }

    public function destroyTiming(ShowTiming $timing)
    {
        $event = $timing->event;

        if ((int)$event->organiser_id !== (int)Auth::id()) {
            abort(403);
        }

        $timing->delete();
        return redirect()->route('organiser.events.edit', $event)->with('success', 'Show timing deleted.');
    }
}
