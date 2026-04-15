<?php

namespace App\Http\Controllers\Organiser;

use App\Models\SeatCategory;
use App\Models\Venue;
use Illuminate\Http\Request;

class SeatCategoryController extends Controller
{
    public function index(Venue $venue)
    {
        if ((int)$venue->organiser_id !==(int) auth()->id()) {
            abort(403);
        }

        $seatCategories = $venue->seatCategories()->get();
        return view('organiser.seat-categories.index', compact('venue', 'seatCategories'));
    }

    public function create(Venue $venue)
    {
        if ((int)$venue->organiser_id !== (int)auth()->id()) {
            abort(403);
        }

        return view('organiser.seat-categories.create', compact('venue'));
    }

    public function store(Request $request, Venue $venue)
    {
        if ((int)$venue->organiser_id !== (int)auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string',
            'total_seats' => 'required|integer|min:1',
            'base_price' => 'required|numeric|min:0',
            'color' => 'nullable|regex:/^#[0-9A-F]{6}$/i',
            'description' => 'nullable|string',
        ]);

        $validated['venue_id'] = $venue->id;
        $validated['color'] = $request->color ?? '#3B82F6';

        SeatCategory::create($validated);
        return redirect()->route('organiser.seat-categories.index', $venue)->with('success', 'Seat category created!');
    }

    public function edit(Venue $venue, SeatCategory $seatCategory)
    {
        if ((int)$venue->organiser_id !== (int)auth()->id() || (int)$seatCategory->venue_id !== (int)$venue->id) {
            abort(403);
        }

        return view('organiser.seat-categories.edit', compact('venue', 'seatCategory'));
    }

    public function update(Request $request, Venue $venue, SeatCategory $seatCategory)
    {
        if ((int)$venue->organiser_id !== (int) auth()->id() || (int)$seatCategory->venue_id !== (int)$venue->id) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string',
            'base_price' => 'required|numeric|min:0',
            'color' => 'nullable|regex:/^#[0-9A-F]{6}$/i',
            'description' => 'nullable|string',
        ]);

        $validated['color'] = $request->color ?? $seatCategory->color;
        $seatCategory->update($validated);

        return redirect()->route('organiser.seat-categories.index', $venue)->with('success', 'Seat category updated!');
    }

    public function destroy(Venue $venue, SeatCategory $seatCategory)
    {
        if ((int)$venue->organiser_id !== (int) auth()->id() || (int)$seatCategory->venue_id !==(int) $venue->id) {
            abort(403);
        }

        if ($seatCategory->seats()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete category with assigned seats!');
        }

        $seatCategory->delete();
        return redirect()->route('organiser.seat-categories.index', $venue)->with('success', 'Seat category deleted!');
    }
}
