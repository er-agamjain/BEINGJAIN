<?php

namespace App\Http\Controllers\Admin;

use App\Models\SeatCategory;
use App\Models\Venue;
use Illuminate\Http\Request;

class SeatCategoryController extends Controller
{
    public function index(Venue $venue)
    {
        $seatCategories = $venue->seatCategories()->with('seats')->get();

        return view('admin.seat-categories.index', compact('venue', 'seatCategories'));
    }

    public function store(Request $request, Venue $venue)
    {
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

        return redirect()->route('admin.seat-categories.index', $venue)->with('success', 'Seat category created!');
    }

    public function update(Request $request, Venue $venue, SeatCategory $seatCategory)
    {
        if ((int) $seatCategory->venue_id !== (int) $venue->id) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string',
            'base_price' => 'required|numeric|min:0',
            'color' => 'nullable|regex:/^#[0-9A-F]{6}$/i',
            'description' => 'nullable|string',
        ]);

        $validated['color'] = $request->color ?? $seatCategory->color;
        $seatCategory->update($validated);

        return redirect()->route('admin.seat-categories.index', $venue)->with('success', 'Seat category updated!');
    }

    public function destroy(Venue $venue, SeatCategory $seatCategory)
    {
        if ((int) $seatCategory->venue_id !== (int) $venue->id) {
            abort(404);
        }

        if ($seatCategory->seats()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete category with assigned seats!');
        }

        $seatCategory->delete();

        return redirect()->route('admin.seat-categories.index', $venue)->with('success', 'Seat category deleted!');
    }
}
