<?php

namespace App\Http\Controllers\Admin;

use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function index()
    {
        $cities = City::paginate(20);
        return view('admin.cities.index', compact('cities'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:cities,name',
            'description' => 'nullable|string',
        ]);

        $validated['slug'] = str()->slug($validated['name']);
        City::create($validated);

        return redirect()->route('admin.cities.index')->with('success', 'City created successfully!');
    }

    public function update(Request $request, City $city)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:cities,name,' . $city->id,
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = str()->slug($validated['name']);
        $city->update($validated);

        return redirect()->route('admin.cities.index')->with('success', 'City updated successfully!');
    }

    public function destroy(City $city)
    {
        if ($city->venues()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete city with associated venues!');
        }

        $city->delete();
        return redirect()->route('admin.cities.index')->with('success', 'City deleted successfully!');
    }

    public function toggleActive(City $city)
    {
        $city->update(['is_active' => !$city->is_active]);
        return redirect()->back()->with('success', 'City status updated!');
    }
}
