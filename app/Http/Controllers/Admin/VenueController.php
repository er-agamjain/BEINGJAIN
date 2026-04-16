<?php

namespace App\Http\Controllers\Admin;

use App\Models\City;
use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VenueController extends Controller
{
    public function searchCities(Request $request)
    {
        $search = $request->get('q', '');

        $query = City::where('is_active', true);

        if (strlen($search) > 0) {
            $query->where('name', 'like', "%{$search}%");
        }

        $cities = $query->select('id', 'name')
            ->orderBy('name')
            ->limit(20)
            ->get()
            ->map(function ($city) {
                return [
                    'id' => $city->id,
                    'label' => $city->name,
                    'name' => $city->name,
                ];
            });

        return response()->json($cities);
    }

    public function index()
    {
        $venues = Venue::with(['city', 'organiser'])->latest()->paginate(20);

        return view('admin.venues.index', compact('venues'));
    }

    public function create()
    {
        $cities = City::where('is_active', true)->get();

        return view('admin.venues.create', compact('cities'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'city_id' => 'required|exists:cities,id',
            'address' => 'required|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'total_capacity' => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);

        $validated['organiser_id'] = Auth::id();

        if ($request->hasFile('seating_layout')) {
            $validated['seating_layout'] = $request->file('seating_layout')->store('seating-layouts', 'public');
        }

        Venue::create($validated);

        return redirect()->route('admin.venues.index')->with('success', 'Venue created successfully!');
    }

    public function edit(Venue $venue)
    {
        $cities = City::where('is_active', true)->get();

        return view('admin.venues.edit', compact('venue', 'cities'));
    }

    public function update(Request $request, Venue $venue)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'city_id' => 'required|exists:cities,id',
            'address' => 'required|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'total_capacity' => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);

        if ($request->hasFile('seating_layout')) {
            $validated['seating_layout'] = $request->file('seating_layout')->store('seating-layouts', 'public');
        }

        $venue->update($validated);

        return redirect()->route('admin.venues.index')->with('success', 'Venue updated successfully!');
    }

    public function destroy(Venue $venue)
    {
        if ($venue->showTimings()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete venue with associated show timings!');
        }

        $venue->delete();

        return redirect()->route('admin.venues.index')->with('success', 'Venue deleted successfully!');
    }
}
