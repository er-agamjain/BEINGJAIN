<?php

namespace App\Http\Controllers\Admin;

use App\Models\Seat;
use App\Models\ShowTiming;
use App\Models\Venue;
use Illuminate\Http\Request;

class SeatController extends Controller
{
    public function index(Request $request)
    {
        $query = Seat::with('showTiming.event', 'seatCategory');

        // Filter by show timing
        if ($request->filled('show_timing_id')) {
            $query->where('show_timing_id', $request->show_timing_id);
        }

        // Filter by seat status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by venue
        if ($request->filled('venue_id')) {
            $query->whereHas('showTiming', function ($q) {
                $q->where('venue_id', request('venue_id'));
            });
        }

        $seats = $query->latest('id')->paginate(50);

        // Get filters data
        $showTimings = ShowTiming::with('event', 'venue')->get();
        $venues = Venue::all();
        $statuses = ['available', 'reserved', 'booked', 'blocked'];

        return view('admin.seats.index', compact('seats', 'showTimings', 'venues', 'statuses'));
    }

    public function show(Seat $seat)
    {
        $seat->load('showTiming.event', 'showTiming.venue', 'seatCategory');
        return view('admin.seats.show', compact('seat'));
    }

    public function edit(Seat $seat)
    {
        $seat->load('showTiming.event', 'seatCategory');
        return view('admin.seats.edit', compact('seat'));
    }

    public function update(Request $request, Seat $seat)
    {
        $validated = $request->validate([
            'status' => 'required|in:available,reserved,booked,blocked',
            'current_price' => 'nullable|numeric|min:0',
        ]);

        $seat->update($validated);

        return redirect()->route('admin.seats.index')->with('success', 'Seat updated successfully!');
    }

    public function bulkUpdate(Request $request)
    {
        $validated = $request->validate([
            'seat_ids' => 'required|array',
            'seat_ids.*' => 'exists:seats,id',
            'status' => 'required|in:available,reserved,booked,blocked',
        ]);

        Seat::whereIn('id', $validated['seat_ids'])->update(['status' => $validated['status']]);

        return redirect()->back()->with('success', count($validated['seat_ids']) . ' seats updated successfully!');
    }

    public function blockSeats(Request $request)
    {
        $validated = $request->validate([
            'show_timing_id' => 'required|exists:show_timings,id',
            'rows' => 'nullable|string',
            'columns' => 'nullable|string',
        ]);

        $query = Seat::where('show_timing_id', $validated['show_timing_id']);

        // Block specific rows
        if ($request->filled('rows')) {
            $rows = explode(',', $validated['rows']);
            $rows = array_map('trim', $rows);
            $query->whereIn('row_number', $rows);
        }

        // Block specific columns
        if ($request->filled('columns')) {
            $columns = explode(',', $validated['columns']);
            $columns = array_map('trim', $columns);
            $query->whereIn('column_number', $columns);
        }

        $count = $query->update(['status' => 'blocked']);

        return redirect()->back()->with('success', $count . ' seats blocked successfully!');
    }

    public function unblockSeats(Request $request)
    {
        $validated = $request->validate([
            'show_timing_id' => 'required|exists:show_timings,id',
        ]);

        $count = Seat::where('show_timing_id', $validated['show_timing_id'])
            ->where('status', 'blocked')
            ->update(['status' => 'available']);

        return redirect()->back()->with('success', $count . ' seats unblocked successfully!');
    }

    public function stats(Request $request)
    {
        $showTiming = null;
        if ($request->filled('show_timing_id')) {
            $showTiming = ShowTiming::with('event', 'venue')->findOrFail($request->show_timing_id);
            $stats = $showTiming->seats()
                ->selectRaw('status, count(*) as count')
                ->groupBy('status')
                ->pluck('count', 'status');
        } else {
            $stats = Seat::selectRaw('status, count(*) as count')
                ->groupBy('status')
                ->pluck('count', 'status');
        }

        return view('admin.seats.stats', compact('stats', 'showTiming'));
    }
}
