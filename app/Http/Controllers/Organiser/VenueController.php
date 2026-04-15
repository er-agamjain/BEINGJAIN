<?php

namespace App\Http\Controllers\Organiser;

use App\Http\Controllers\Controller;
use App\Models\Venue;
use App\Models\City;
use Illuminate\Http\Request;

class VenueController extends Controller
{
    public function searchCities(Request $request)
    {
        $search = $request->get('q', '');
        
        // Build the query for active cities
        $query = City::where('is_active', true);
        
        // If search term provided, filter by name
        if (strlen($search) > 0) {
            $query->where('name', 'like', "%{$search}%");
        }
        
        $cities = $query->select('id', 'name')
            ->orderBy('name')
            ->limit(20)
            ->get()
            ->map(function($city) {
                return [
                    'id' => $city->id,
                    'label' => $city->name,
                    'name' => $city->name
                ];
            });
        
        return response()->json($cities);
    }

    public function index()
    {
        $venues = Venue::where('organiser_id', auth()->id())
            ->with('city')
            ->paginate(20);
        return view('organiser.venues.index', compact('venues'));
    }

    public function create()
    {
        $cities = City::where('is_active', true)->get();
        return view('organiser.venues.create', compact('cities'));
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

        $validated['organiser_id'] = auth()->id();

        // Handle seating layout upload
        if ($request->hasFile('seating_layout')) {
            $file = $request->file('seating_layout');
            $path = $file->store('seating-layouts', 'public');
            $validated['seating_layout'] = $path;
        }

        Venue::create($validated);
        return redirect()->route('organiser.venues.index')->with('success', 'Venue created successfully!');
    }

    public function edit(Venue $venue)
    {
        if ((int)$venue->organiser_id !== (int)auth()->id()) {
            abort(403, 'Unauthorized');
        }
        $cities = City::where('is_active', true)->get();
        return view('organiser.venues.edit', compact('venue', 'cities'));
    }

    public function update(Request $request, Venue $venue)
    {
        if ((int)$venue->organiser_id !==(int) auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'name' => 'required|string',
            'city_id' => 'required|exists:cities,id',
            'address' => 'required|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'total_capacity' => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);

        // Handle seating layout upload
        if ($request->hasFile('seating_layout')) {
            $file = $request->file('seating_layout');
            $path = $file->store('seating-layouts', 'public');
            $validated['seating_layout'] = $path;
        }

        $venue->update($validated);
        return redirect()->route('organiser.venues.index')->with('success', 'Venue updated successfully!');
    }

    public function destroy(Venue $venue)
    {
        if ((int)$venue->organiser_id !==(int) auth()->id()) {
            abort(403, 'Unauthorized');
        }
        
        if ($venue->showTimings()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete venue with associated show timings!');
        }

        $venue->delete();
        return redirect()->route('organiser.venues.index')->with('success', 'Venue deleted successfully!');
    }
}
