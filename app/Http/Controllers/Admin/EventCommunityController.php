<?php

namespace App\Http\Controllers\Admin;

use App\Models\EventCommunity;
use App\Imports\EventCommunityImport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class EventCommunityController extends Controller
{
    public function index()
    {
        $communities = EventCommunity::withCount('events')->paginate(20);
        return view('admin.event-communities.index', compact('communities'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:event_communities,name',
            'description' => 'nullable|string',
        ]);

        EventCommunity::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'is_active' => true,
        ]);

        return redirect()->route('admin.event-communities.index')
            ->with('success', 'Community created successfully!');
    }

    public function update(Request $request, EventCommunity $community)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:event_communities,name,' . $community->id,
            'description' => 'nullable|string',
        ]);

        $community->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('admin.event-communities.index')
            ->with('success', 'Community updated successfully!');
    }

    public function destroy(EventCommunity $community)
    {
        if ($community->events()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete community with associated events!');
        }

        $community->delete();
        
        return redirect()->route('admin.event-communities.index')
            ->with('success', 'Community deleted successfully!');
    }

    public function toggleActive(EventCommunity $community)
    {
        $community->update(['is_active' => !$community->is_active]);
        
        return redirect()->back()
            ->with('success', 'Community status updated!');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048'
        ]);

        try {
            Excel::import(new EventCommunityImport, $request->file('file'));
            
            return redirect()->route('admin.event-communities.index')
                ->with('success', 'Communities imported successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error importing file: ' . $e->getMessage());
        }
    }
}
