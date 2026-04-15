<?php

namespace App\Http\Controllers\Admin;

use App\Models\EventCategory;
use App\Models\EventCommunity;
use App\Models\EventGacchh;
use App\Models\EventTag;
use Illuminate\Http\Request;

class EventCategoryController extends Controller
{
    public function index()
    {
        $categories = EventCategory::paginate(20);
        $communities = EventCommunity::paginate(20);
        $gacchs = EventGacchh::paginate(20);
        $tags = EventTag::paginate(20);
        return view('admin.event-categories.index', compact('categories', 'communities', 'gacchs', 'tags'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_name' => 'nullable|string|unique:event_categories,category_name',
            'community' => 'nullable|string',
            'gacchh' => 'nullable|string',
            'tags' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $data = [
            'category_name' => $validated['category_name'],
            'community' => $validated['community'] ?? null,
            'gacchh' => $validated['gacchh'] ?? null,
            'tags' => $validated['tags'] ?? null,
            'description' => $validated['description'] ?? null,
            'is_active' => true,
        ];

        EventCategory::create($data);
        return redirect()->route('admin.event-categories.index')->with('success', 'Category created successfully!');
    }

    public function update(Request $request, EventCategory $category)
    {
        $validated = $request->validate([
            'category_name' => 'nullable|string|unique:event_categories,category_name,' . $category->id,
            'community' => 'nullable|string',
            'gacchh' => 'nullable|string',
            'tags' => 'nullable|string',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $data = [
            'category_name' => $validated['category_name'],
            'community' => $validated['community'] ?? null,
            'gacchh' => $validated['gacchh'] ?? null,
            'tags' => $validated['tags'] ?? null,
            'description' => $validated['description'] ?? null,
        ];

        if (array_key_exists('is_active', $validated)) {
            $data['is_active'] = $validated['is_active'];
        }

        $category->update($data);
        return redirect()->route('admin.event-categories.index')->with('success', 'Category updated successfully!');
    }

    public function destroy(EventCategory $category)
    {
        if ($category->events()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete category with associated events!');
        }

        $category->delete();
        return redirect()->route('admin.event-categories.index')->with('success', 'Category deleted successfully!');
    }

    public function toggleActive(EventCategory $category)
    {
        $category->update(['is_active' => !$category->is_active]);
        return redirect()->back()->with('success', 'Category status updated!');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls'
        ]);

        $file = $request->file('file');
        $path = $file->store('imports');

        try {
            $import = new \App\Imports\EventCategoryImport();
            \Maatwebsite\Excel\Facades\Excel::import($import, storage_path('app/' . $path));
            
            \File::delete(storage_path('app/' . $path));
            
            return redirect()->route('admin.event-categories.index')->with('success', 'Categories imported successfully!');
        } catch (\Exception $e) {
            \File::delete(storage_path('app/' . $path));
            return redirect()->back()->with('error', 'Error importing file: ' . $e->getMessage());
        }
    }
}
