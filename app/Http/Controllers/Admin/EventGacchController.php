<?php

namespace App\Http\Controllers\Admin;

use App\Models\EventGacchh;
use App\Imports\EventGacchImport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class EventGacchController extends Controller
{
    public function index()
    {
        $gacchs = EventGacchh::withCount('events')->paginate(20);
        return view('admin.event-gacchs.index', compact('gacchs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:event_gacchs,name',
            'description' => 'nullable|string',
        ]);

        EventGacchh::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'is_active' => true,
        ]);

        return redirect()->route('admin.event-gacchs.index')
            ->with('success', 'Gacchh created successfully!');
    }

    public function update(Request $request, EventGacchh $gacchh)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:event_gacchs,name,' . $gacchh->id,
            'description' => 'nullable|string',
        ]);

        $gacchh->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('admin.event-gacchs.index')
            ->with('success', 'Gacchh updated successfully!');
    }

    public function destroy(EventGacchh $gacchh)
    {
        if ($gacchh->events()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete gacchh with associated events!');
        }

        $gacchh->delete();
        
        return redirect()->route('admin.event-gacchs.index')
            ->with('success', 'Gacchh deleted successfully!');
    }

    public function toggleActive(EventGacchh $gacchh)
    {
        $gacchh->update(['is_active' => !$gacchh->is_active]);
        
        return redirect()->back()
            ->with('success', 'Gacchh status updated!');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048'
        ]);

        try {
            Excel::import(new EventGacchImport, $request->file('file'));
            
            return redirect()->route('admin.event-gacchs.index')
                ->with('success', 'Gacchs imported successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error importing file: ' . $e->getMessage());
        }
    }
}
