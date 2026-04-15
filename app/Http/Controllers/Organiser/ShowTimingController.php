<?php

namespace App\Http\Controllers\Organiser;

use App\Models\ShowTiming;
use App\Models\Event;
use App\Models\Venue;
use Illuminate\Http\Request;

class ShowTimingController extends Controller
{
    public function index(Request $request)
    {
        $query = ShowTiming::whereHas('event', function ($q) {
            $q->where('organiser_id', auth()->id());
        });

        // Apply filters
        if ($request->filled('event_id')) {
            $query->where('event_id', $request->event_id);
        }

        if ($request->filled('venue_id')) {
            $query->where('venue_id', $request->venue_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        //$showTimings = $query->with('event', 'venue')->latest('show_date_time')->paginate(20);
        $showTimings = $query->with('event', 'venue')->withCount('seats')->latest('show_date_time')->paginate(20);
        
        // Get data for filter dropdowns
        $events = Event::where('organiser_id', auth()->id())->get();
        $venues = Venue::where('organiser_id', auth()->id())->get();

        return view('organiser.show-timings.index', compact('showTimings', 'events', 'venues'));
    }

    public function create()
    {
        $events = Event::where('organiser_id', auth()->id())->get();
        //$venues = Venue::where('organiser_id', auth()->id())->get();
        $venues = Venue::where('organiser_id', auth()->id())->withCount('seatCategories')->get();
        return view('organiser.show-timings.create', compact('events', 'venues'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'venue_id' => 'required|exists:venues,id',
            'show_date_time' => 'required|date|after:now',
            'duration_minutes' => 'required|integer|min:30',
            'available_seats' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        // Verify organiser owns the event and venue
        $event = Event::findOrFail($request->event_id);
        $venue = Venue::findOrFail($request->venue_id);

        if ((int)$event->organiser_id !== (int)auth()->id() || (int)$venue->organiser_id !==(int) auth()->id()) {
            abort(403, 'Unauthorized');
        }

        // Status defaults to 'scheduled' via migration
        ShowTiming::create($validated);
        return redirect()->route('organiser.show-timings.index')->with('success', 'Show timing created successfully!');
    }

    public function edit(ShowTiming $showTiming)
    {
        $event = Event::find($showTiming->event_id);
        if ((int)$event->organiser_id !== (int)auth()->id()) {
            abort(403);
        }

        $events = Event::where('organiser_id', auth()->id())->get();
        $venues = Venue::where('organiser_id', auth()->id())->get();
        return view('organiser.show-timings.edit', compact('showTiming', 'events', 'venues'));
    }

    public function update(Request $request, ShowTiming $showTiming)
    {
        $event = Event::find($showTiming->event_id);
        if ((int)$event->organiser_id !==(int) auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'show_date_time' => 'required|date|after:now',
            'duration_minutes' => 'required|integer|min:30',
            'available_seats' => 'required|integer|min:1',
            'status' => 'required|in:scheduled,cancelled,completed',
            'notes' => 'nullable|string',
        ]);

        $showTiming->update($validated);
        return redirect()->route('organiser.show-timings.index')->with('success', 'Show timing updated successfully!');
    }

    public function blockSeats(ShowTiming $showTiming, Request $request)
    {
        $event = Event::find($showTiming->event_id);
        if ((int)$event->organiser_id !==(int) auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'seat_ids' => 'required|array',
            'seat_ids.*' => 'exists:seats,id',
        ]);

        $showTiming->seats()
            ->whereIn('id', $request->seat_ids)
            ->update(['status' => 'blocked']);

        return redirect()->back()->with('success', 'Seats blocked successfully!');
    }
    
    
    public function regenerateSeats(ShowTiming $showTiming)
    {
        $event = Event::find($showTiming->event_id);
        if ($event->organiser_id !== auth()->id()) {
            abort(403);
        }

        // Only regenerate if no seats exist yet
        if ($showTiming->seats()->count() > 0) {
            return redirect()->route('organiser.show-timings.index')
                ->with('error', 'Seats already exist for this show timing. Delete existing seats first.');
        }

        $showTiming->generateSeats();

        $seatsCreated = $showTiming->seats()->count();
        if ($seatsCreated === 0) {
            return redirect()->route('organiser.show-timings.index')
                ->with('error', 'Could not generate seats. Please add seat categories to the venue first.');
        }

        return redirect()->route('organiser.show-timings.index')
            ->with('success', "Seats regenerated successfully! {$seatsCreated} seats created.");
    }
}
