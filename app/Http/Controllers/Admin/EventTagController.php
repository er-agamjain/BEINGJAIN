<?php

namespace App\Http\Controllers\Admin;

use App\Models\EventTag;
use App\Imports\EventTagImport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class EventTagController extends Controller
{
    public function index()
    {
        $tags = EventTag::paginate(20);
        return view('admin.event-tags.index', compact('tags'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:event_tags,name',
            'description' => 'nullable|string',
        ]);

        EventTag::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'is_active' => true,
        ]);

        return redirect()->route('admin.event-tags.index')
            ->with('success', 'Tag created successfully!');
    }

    public function update(Request $request, EventTag $tag)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:event_tags,name,' . $tag->id,
            'description' => 'nullable|string',
        ]);

        $tag->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('admin.event-tags.index')
            ->with('success', 'Tag updated successfully!');
    }

    public function destroy(EventTag $tag)
    {
        $tag->delete();
        
        return redirect()->route('admin.event-tags.index')
            ->with('success', 'Tag deleted successfully!');
    }

    public function toggleActive(EventTag $tag)
    {
        $tag->update(['is_active' => !$tag->is_active]);
        
        return redirect()->back()
            ->with('success', 'Tag status updated!');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048'
        ]);

        try {
            Excel::import(new EventTagImport, $request->file('file'));
            
            return redirect()->route('admin.event-tags.index')
                ->with('success', 'Tags imported successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error importing file: ' . $e->getMessage());
        }
    }
}
