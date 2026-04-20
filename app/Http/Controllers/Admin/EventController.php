<?php

namespace App\Http\Controllers\Admin;

use App\Models\Event;
use App\Models\EventCategory;
use App\Models\EventCommunity;
use App\Models\EventGacchh;
use App\Models\EventTag;
use App\Models\ShowTiming;
use App\Models\Ticket;
use App\Models\City;
use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::with(['organiser', 'city'])->latest()->paginate(20);
        return view('admin.events', compact('events'));
    }

    public function create()
    {
        $categories = EventCategory::where('is_active', true)->pluck('category_name', 'id');
        
        // Get unique values for community, gacchh, and tags
        //$communities = EventCategory::where('is_active', true)->whereNotNull('community')->distinct()->pluck('community');
        //$gacchhs = EventCategory::where('is_active', true)->whereNotNull('gacchh')->distinct()->pluck('gacchh');
        //$tagsList = EventCategory::where('is_active', true)->whereNotNull('tags')->distinct()->pluck('tags');
        
         // Get values from their dedicated tables
        $communities = EventCommunity::where('is_active', true)->pluck('name');
        $gacchhs = EventGacchh::where('is_active', true)->pluck('name');
        $tagsList = EventTag::where('is_active', true)->pluck('name');
        $cities = City::where('is_active', true)->orderBy('name')->pluck('name', 'id');
        
        //return view('admin.events.create', compact('categories', 'communities', 'gacchhs', 'tagsList'));
        return view('admin.events.create', compact('categories', 'communities', 'gacchhs', 'tagsList', 'cities'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'nullable|string',
            'navigation_location' => 'nullable|url|max:2048',
            'address' => 'nullable|string|max:500',
            'city_id' => 'nullable|exists:cities,id',
            'all_day' => 'nullable|boolean',
            'event_date' => 'required|date|after:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required_unless:all_day,1|nullable|date_format:H:i',
            'is_free' => 'boolean',
            'base_price' => 'required_if:is_free,false|nullable|numeric|min:0',
            'category_id' => 'nullable|exists:event_categories,id',
            'community' => 'nullable|string|max:255',
            'gacchh' => 'nullable|string|max:255',
            'tags' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:2048',
        ]);

        $validated['organiser_id'] = Auth::id();
        $validated['status'] = 'published'; // Admin events auto-published
        $event = Event::create($validated);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = \Illuminate\Support\Str::random(40) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('storage/events'), $filename);
            $event->update(['image' => 'events/' . $filename]);
        }

        return redirect()->route('admin.events.index')->with('success', 'Event created successfully! No commission will be applied.');
    }
    
    public function show(Event $event)
    {
        $event->load(['organiser', 'eventCategory', 'showTimings.venue', 'showTimings.tickets', 'city']);
        return view('admin.events.show', compact('event'));
    }

    public function edit(Event $event)
    {
        $event->load(['organiser', 'showTimings.tickets', 'showTimings.venue', 'city']);
        $categories = EventCategory::where('is_active', true)->pluck('category_name', 'id');
        $communities = EventCommunity::where('is_active', true)->pluck('name');
        $gacchhs = EventGacchh::where('is_active', true)->pluck('name');
        $tagsList = EventTag::where('is_active', true)->pluck('name');
        $cities = City::where('is_active', true)->orderBy('name')->pluck('name', 'id');
        $venues = Venue::with(['city', 'organiser'])->orderBy('name')->get();

        return view('admin.events.edit', compact('event', 'categories', 'communities', 'gacchhs', 'tagsList', 'cities', 'venues'));
    }

    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'nullable|string',
            'navigation_location' => 'nullable|url|max:2048',
            'address' => 'nullable|string|max:500',
            'city_id' => 'nullable|exists:cities,id',
            'all_day' => 'nullable|boolean',
            'event_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required_unless:all_day,1|nullable|date_format:H:i',
            'is_free' => 'boolean',
            'base_price' => 'required_if:is_free,false|nullable|numeric|min:0',
            'category_id' => 'nullable|exists:event_categories,id',
            'community' => 'nullable|string|max:255',
            'gacchh' => 'nullable|string|max:255',
            'tags' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = \Illuminate\Support\Str::random(40) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('storage/events'), $filename);
            $validated['image'] = 'events/' . $filename;
        }

        $event->update($validated);

        return redirect()->route('admin.events.edit', $event)->with('success', 'Event updated successfully!');
    }

    public function approve(Event $event)
    {
        //$event->update(['status' => 'published']);
         $event->update(['status' => 'approved']);
        return redirect()->back()->with('success', 'Event approved');
    }

    public function reject(Event $event)
    {
        //$event->update(['status' => 'cancelled']);
         $event->update(['status' => 'rejected']);
        return redirect()->back()->with('success', 'Event rejected');
    }

    public function destroy(Event $event)
    {
        $event->delete();
         return redirect()->route('admin.events.index')->with('success', 'Event deleted');
    }

    public function storeTicket(Request $request, Event $event)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'ticket_type' => 'required|in:free,paid',
        ]);

        $event->tickets()->create($validated);

        return redirect()->route('admin.events.edit', $event)->with('success', 'Ticket added successfully!');
    }

    public function destroyTicket(Ticket $ticket)
    {
        $event = $ticket->event;
        $ticket->delete();
        return redirect()->route('admin.events.edit', $event)->with('success', 'Ticket deleted.');
    }

    public function storeTiming(Request $request, Event $event)
    {
        $validated = $request->validate([
            'venue_id' => 'required|exists:venues,id',
            'show_date_time' => 'required|date',
            'duration_minutes' => 'required|integer|min:1',
            'available_seats' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:500',
        ]);

        $validated['event_id'] = $event->id;
        $validated['status'] = 'scheduled';

        ShowTiming::create($validated);

        return redirect()->route('admin.events.edit', $event)->with('success', 'Show timing added successfully!');
    }

    public function destroyTiming(ShowTiming $timing)
    {
        $event = $timing->event;
        $timing->delete();
        return redirect()->route('admin.events.edit', $event)->with('success', 'Show timing deleted.');
    }

    public function createTicket(Request $request, Event $event)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'ticket_type' => 'required|in:free,paid',
        ]);

        $event->tickets()->create($validated);

        return redirect()->back()->with('success', 'Ticket created manually');
    }
}
