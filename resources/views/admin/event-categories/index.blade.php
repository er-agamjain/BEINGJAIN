@extends('layouts.app')

@section('title', 'Event Categories')

@section('content')
<div class="mb-8 animate-fade-in">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-white mb-2" style="font-family: 'Playfair Display', serif;">
                <i class="fas fa-tags text-amber-400"></i> Category Management
            </h1>
            <p class="text-gray-400">Manage event categories, communities, gacchs, and tags</p>
        </div>
    </div>
</div>

<!-- Navigation Tabs -->
<div class="flex gap-4 mb-8 overflow-x-auto pb-2">
    <button onclick="showTab('categories')" class="tab-btn active px-6 py-3 bg-gradient-to-r from-amber-500/20 to-amber-600/20 text-amber-300 rounded-lg font-semibold transition-all border border-amber-500/30 whitespace-nowrap">
        <i class="fas fa-list mr-2"></i> Categories
    </button>
    <button onclick="showTab('categoryname')" class="tab-btn px-6 py-3 bg-slate-700/50 text-gray-300 rounded-lg font-semibold transition-all border border-slate-600/30 whitespace-nowrap hover:border-green-500/30">
        <i class="fas fa-tags mr-2"></i> Category Name
    </button>
    <button onclick="showTab('communities')" class="tab-btn px-6 py-3 bg-slate-700/50 text-gray-300 rounded-lg font-semibold transition-all border border-slate-600/30 whitespace-nowrap hover:border-cyan-500/30">
        <i class="fas fa-users mr-2"></i> Communities
    </button>
    <button onclick="showTab('gacchs')" class="tab-btn px-6 py-3 bg-slate-700/50 text-gray-300 rounded-lg font-semibold transition-all border border-slate-600/30 whitespace-nowrap hover:border-indigo-500/30">
        <i class="fas fa-layer-group mr-2"></i> Gacchs
    </button>
    <button onclick="showTab('tags')" class="tab-btn px-6 py-3 bg-slate-700/50 text-gray-300 rounded-lg font-semibold transition-all border border-slate-600/30 whitespace-nowrap hover:border-red-500/30">
        <i class="fas fa-hashtag mr-2"></i> Tags
    </button>
</div>

<!-- Categories Tab -->
<div id="categories-tab" class="tab-content">
<div class="card-luxury mb-8">
    <div class="p-6">
        <div class="mb-6 flex items-center justify-between">
            <div></div>
            <div class="flex gap-3">
                <button onclick="document.getElementById('uploadCategoryModal').classList.remove('hidden')" class="px-6 py-2 bg-gradient-to-r from-blue-500 to-blue-600 hover:shadow-lg hover:shadow-blue-500/20 text-white rounded-lg font-semibold transition-all">
                    <i class="fas fa-upload"></i> Upload CSV/Excel
                </button>
                <button onclick="document.getElementById('addCategoryModal').classList.remove('hidden')" class="px-6 py-2 bg-gradient-to-r from-emerald-500 to-teal-500 hover:shadow-lg hover:shadow-emerald-500/20 text-white rounded-lg font-semibold transition-all">
                    <i class="fas fa-plus"></i> Add Category
                </button>
            </div>
        </div>
        <div class="mb-6 flex items-center justify-between">
            <h2 class="text-2xl font-bold text-white flex items-center">
                <i class="fas fa-list text-amber-400 mr-3"></i> Categories List
            </h2>
            <span class="text-sm text-gray-400">Total: <span class="text-amber-400 font-bold">{{ $categories->total() }}</span></span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-700/50 border-b-2 border-slate-600/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-amber-400 uppercase">Category Name</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-amber-400 uppercase">Community</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-amber-400 uppercase">Gacchh</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-amber-400 uppercase">Tags</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-amber-400 uppercase">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-amber-400 uppercase">Events</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-amber-400 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/30">
                    @forelse($categories as $category)
                        <tr class="hover:bg-slate-700/20 transition-colors">
                            <td class="px-6 py-4">
                                <span class="text-white font-semibold">{{ $category->category_name }}</span>
                            </td>
                            <td class="px-6 py-4 text-gray-300">
                                @if($category->community)
                                    <span class="px-3 py-1 bg-blue-500/20 text-blue-200 rounded-lg text-xs font-semibold border border-blue-500/30">
                                        {{ $category->community }}
                                    </span>
                                @else
                                    <span class="text-gray-500 italic">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-gray-300">
                                @if($category->gacchh)
                                    <span class="px-3 py-1 bg-purple-500/20 text-purple-200 rounded-lg text-xs font-semibold border border-purple-500/30">
                                        {{ $category->gacchh }}
                                    </span>
                                @else
                                    <span class="text-gray-500 italic">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-gray-300">
                                @if($category->tags)
                                    <div class="flex flex-wrap gap-2">
                                        @foreach(explode(',', $category->tags) as $tag)
                                            <span class="px-2 py-1 bg-amber-500/20 text-amber-200 rounded text-xs font-semibold border border-amber-500/30">
                                                {{ trim($tag) }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-gray-500 italic">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($category->is_active)
                                    <span class="px-3 py-1 bg-green-500/20 text-green-200 rounded-lg text-xs font-semibold border border-green-500/30">
                                        <i class="fas fa-check-circle"></i> Active
                                    </span>
                                @else
                                    <span class="px-3 py-1 bg-red-500/20 text-red-200 rounded-lg text-xs font-semibold border border-red-500/30">
                                        <i class="fas fa-times-circle"></i> Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-amber-400 font-bold">{{ $category->events()->count() }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    <button onclick="openEditModal({{ $category->id }}, '{{ addslashes($category->category_name) }}', '{{ addslashes($category->community ?? '') }}', '{{ addslashes($category->gacchh ?? '') }}', '{{ addslashes($category->tags ?? '') }}', '{{ addslashes($category->description ?? '') }}')" class="text-emerald-400 hover:text-emerald-300 transition-colors" title="Edit">
                                        <i class="fas fa-edit text-lg"></i>
                                    </button>
                                    <form action="{{ route('admin.event-categories.toggle', $category) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="{{ $category->is_active ? 'text-amber-400 hover:text-amber-300' : 'text-green-400 hover:text-green-300' }} transition-colors" title="{{ $category->is_active ? 'Deactivate' : 'Activate' }}">
                                            <i class="fas {{ $category->is_active ? 'fa-toggle-on' : 'fa-toggle-off' }} text-lg"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.event-categories.destroy', $category) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Delete this category?')" class="text-red-400 hover:text-red-300 transition-colors" title="Delete">
                                            <i class="fas fa-trash text-lg"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-16 text-center text-gray-400">
                                <i class="fas fa-tags text-6xl text-gray-600 mb-4 block"></i>
                                <p class="text-lg">No categories found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $categories->links() }}
        </div>
    </div>
</div>

<!-- Summary Stats -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="card-luxury p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-400 text-sm mb-2">Total Categories</p>
                <p class="text-4xl font-bold text-amber-400">{{ $categories->total() }}</p>
            </div>
            <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-amber-500/20 to-orange-600/20 flex items-center justify-center text-amber-400 text-3xl border border-amber-500/30">
                <i class="fas fa-tags"></i>
            </div>
        </div>
    </div>

    <div class="card-luxury p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-400 text-sm mb-2">Active Categories</p>
                <p class="text-4xl font-bold text-emerald-400">{{ \App\Models\EventCategory::where('is_active', true)->count() }}</p>
            </div>
            <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-emerald-500/20 to-teal-600/20 flex items-center justify-center text-emerald-400 text-3xl border border-emerald-500/30">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
    </div>

    <div class="card-luxury p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-400 text-sm mb-2">Total Events</p>
                <p class="text-4xl font-bold text-blue-400">{{ \App\Models\Event::count() }}</p>
            </div>
            <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-blue-500/20 to-blue-600/20 flex items-center justify-center text-blue-400 text-3xl border border-blue-500/30">
                <i class="fas fa-calendar-alt"></i>
            </div>
        </div>
    </div>
</div>
</div>

<!-- Category Name Tab -->
<div id="categoryname-tab" class="tab-content hidden">
<div class="card-luxury mb-8">
    <div class="p-6">
        <div class="mb-6 flex items-center justify-between">
            <div></div>
            <div class="flex gap-3">
                <button onclick="document.getElementById('uploadCategoryNameModal').classList.remove('hidden')" class="px-6 py-2 bg-gradient-to-r from-blue-500 to-blue-600 hover:shadow-lg hover:shadow-blue-500/20 text-white rounded-lg font-semibold transition-all">
                    <i class="fas fa-upload"></i> Upload CSV/Excel
                </button>
                <button onclick="document.getElementById('addCategoryNameModal').classList.remove('hidden')" class="px-6 py-2 bg-gradient-to-r from-emerald-500 to-teal-500 hover:shadow-lg hover:shadow-emerald-500/20 text-white rounded-lg font-semibold transition-all">
                    <i class="fas fa-plus"></i> Add Category Name
                </button>
            </div>
        </div>
        <div class="mb-6 flex items-center justify-between">
            <h2 class="text-2xl font-bold text-white flex items-center">
                <i class="fas fa-tags text-green-400 mr-3"></i> Category Names List
            </h2>
            <span class="text-sm text-gray-400">Total: <span class="text-green-400 font-bold" id="categoryname-count">{{ $categories->total() }}</span></span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-700/50 border-b-2 border-slate-600/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-green-400 uppercase">Category Name</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-green-400 uppercase">Description</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-green-400 uppercase">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-green-400 uppercase">Events</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-green-400 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/30">
                    @forelse($categories as $category)
                        <tr class="hover:bg-slate-700/20 transition-colors">
                            <td class="px-6 py-4">
                                <span class="text-white font-semibold">{{ $category->category_name }}</span>
                            </td>
                            <td class="px-6 py-4 text-gray-300">
                                {{ $category->description ?? '-' }}
                            </td>
                            <td class="px-6 py-4">
                                @if($category->is_active)
                                    <span class="px-3 py-1 bg-green-500/20 text-green-200 rounded-lg text-xs font-semibold border border-green-500/30">
                                        <i class="fas fa-check-circle"></i> Active
                                    </span>
                                @else
                                    <span class="px-3 py-1 bg-red-500/20 text-red-200 rounded-lg text-xs font-semibold border border-red-500/30">
                                        <i class="fas fa-times-circle"></i> Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-green-400 font-bold">{{ $category->events()->count() }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    <button onclick="openEditCategoryNameModal({{ $category->id }}, '{{ addslashes($category->category_name) }}', '{{ addslashes($category->description ?? '') }}')" class="text-emerald-400 hover:text-emerald-300 transition-colors" title="Edit">
                                        <i class="fas fa-edit text-lg"></i>
                                    </button>
                                    <form action="{{ route('admin.event-categories.toggle', $category) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="{{ $category->is_active ? 'text-amber-400 hover:text-amber-300' : 'text-green-400 hover:text-green-300' }} transition-colors" title="{{ $category->is_active ? 'Deactivate' : 'Activate' }}">
                                            <i class="fas {{ $category->is_active ? 'fa-toggle-on' : 'fa-toggle-off' }} text-lg"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.event-categories.destroy', $category) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Delete this category?')" class="text-red-400 hover:text-red-300 transition-colors" title="Delete">
                                            <i class="fas fa-trash text-lg"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center text-gray-400">
                                <i class="fas fa-tags text-6xl text-gray-600 mb-4 block"></i>
                                <p class="text-lg">No category names found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $categories->links() }}
        </div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="card-luxury p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-400 text-sm mb-2">Total Category Names</p>
                <p class="text-4xl font-bold text-green-400">{{ $categories->total() }}</p>
            </div>
            <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-green-500/20 to-green-600/20 flex items-center justify-center text-green-400 text-3xl border border-green-500/30">
                <i class="fas fa-tags"></i>
            </div>
        </div>
    </div>

    <div class="card-luxury p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-400 text-sm mb-2">Active Category Names</p>
                <p class="text-4xl font-bold text-emerald-400">{{ \App\Models\EventCategory::where('is_active', true)->count() }}</p>
            </div>
            <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-emerald-500/20 to-teal-600/20 flex items-center justify-center text-emerald-400 text-3xl border border-emerald-500/30">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
    </div>

    <div class="card-luxury p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-400 text-sm mb-2">Total Events</p>
                <p class="text-4xl font-bold text-blue-400">{{ \App\Models\Event::count() }}</p>
            </div>
            <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-blue-500/20 to-blue-600/20 flex items-center justify-center text-blue-400 text-3xl border border-blue-500/30">
                <i class="fas fa-calendar-alt"></i>
            </div>
        </div>
    </div>
</div>
</div>

<!-- Communities Tab -->
<div id="communities-tab" class="tab-content hidden">
<div class="card-luxury mb-8">
    <div class="p-6">
        <div class="mb-6 flex items-center justify-between">
            <div></div>
            <div class="flex gap-3">
                <button onclick="document.getElementById('uploadCommunityModal').classList.remove('hidden')" class="px-6 py-2 bg-gradient-to-r from-blue-500 to-blue-600 hover:shadow-lg hover:shadow-blue-500/20 text-white rounded-lg font-semibold transition-all">
                    <i class="fas fa-upload"></i> Upload CSV/Excel
                </button>
                <button onclick="document.getElementById('addCommunityModal').classList.remove('hidden')" class="px-6 py-2 bg-gradient-to-r from-emerald-500 to-teal-500 hover:shadow-lg hover:shadow-emerald-500/20 text-white rounded-lg font-semibold transition-all">
                    <i class="fas fa-plus"></i> Add Community
                </button>
            </div>
        </div>
        <div class="mb-6 flex items-center justify-between">
            <h2 class="text-2xl font-bold text-white flex items-center">
                <i class="fas fa-users text-cyan-400 mr-3"></i> Communities List
            </h2>
            <span class="text-sm text-gray-400">Total: <span class="text-cyan-400 font-bold" id="communities-count">{{ $communities->total() }}</span></span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-700/50 border-b-2 border-slate-600/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-cyan-400 uppercase">Community Name</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-cyan-400 uppercase">Description</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-cyan-400 uppercase">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-cyan-400 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/30">
                    @forelse($communities as $community)
                        <tr class="hover:bg-slate-700/20 transition-colors">
                            <td class="px-6 py-4">
                                <span class="text-white font-semibold">{{ $community->name }}</span>
                            </td>
                            <td class="px-6 py-4 text-gray-300">
                                {{ $community->description ?? '-' }}
                            </td>
                            <td class="px-6 py-4">
                                @if($community->is_active)
                                    <span class="px-3 py-1 bg-green-500/20 text-green-200 rounded-lg text-xs font-semibold border border-green-500/30">
                                        <i class="fas fa-check-circle"></i> Active
                                    </span>
                                @else
                                    <span class="px-3 py-1 bg-red-500/20 text-red-200 rounded-lg text-xs font-semibold border border-red-500/30">
                                        <i class="fas fa-times-circle"></i> Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    <button onclick="openEditCommunityModal({{ $community->id }}, '{{ addslashes($community->name) }}', '{{ addslashes($community->description ?? '') }}')" class="text-emerald-400 hover:text-emerald-300 transition-colors" title="Edit">
                                        <i class="fas fa-edit text-lg"></i>
                                    </button>
                                    <form action="{{ route('admin.event-communities.toggle', $community) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="{{ $community->is_active ? 'text-amber-400 hover:text-amber-300' : 'text-green-400 hover:text-green-300' }} transition-colors" title="{{ $community->is_active ? 'Deactivate' : 'Activate' }}">
                                            <i class="fas {{ $community->is_active ? 'fa-toggle-on' : 'fa-toggle-off' }} text-lg"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.event-communities.destroy', $community) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Delete this community?')" class="text-red-400 hover:text-red-300 transition-colors" title="Delete">
                                            <i class="fas fa-trash text-lg"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-16 text-center text-gray-400">
                                <i class="fas fa-users text-6xl text-gray-600 mb-4 block"></i>
                                <p class="text-lg">No communities found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $communities->links() }}
        </div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="card-luxury p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-400 text-sm mb-2">Total Communities</p>
                <p class="text-4xl font-bold text-cyan-400">{{ $communities->total() }}</p>
            </div>
            <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-cyan-500/20 to-blue-600/20 flex items-center justify-center text-cyan-400 text-3xl border border-cyan-500/30">
                <i class="fas fa-users"></i>
            </div>
        </div>
    </div>

    <div class="card-luxury p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-400 text-sm mb-2">Active Communities</p>
                <p class="text-4xl font-bold text-emerald-400">{{ \App\Models\EventCommunity::where('is_active', true)->count() }}</p>
            </div>
            <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-emerald-500/20 to-teal-600/20 flex items-center justify-center text-emerald-400 text-3xl border border-emerald-500/30">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
    </div>
</div>
</div>

<!-- Gacchs Tab -->
<div id="gacchs-tab" class="tab-content hidden">
<div class="card-luxury mb-8">
    <div class="p-6">
        <div class="mb-6 flex items-center justify-between">
            <div></div>
            <div class="flex gap-3">
                <button onclick="document.getElementById('uploadGacchModal').classList.remove('hidden')" class="px-6 py-2 bg-gradient-to-r from-blue-500 to-blue-600 hover:shadow-lg hover:shadow-blue-500/20 text-white rounded-lg font-semibold transition-all">
                    <i class="fas fa-upload"></i> Upload CSV/Excel
                </button>
                <button onclick="document.getElementById('addGacchModal').classList.remove('hidden')" class="px-6 py-2 bg-gradient-to-r from-emerald-500 to-teal-500 hover:shadow-lg hover:shadow-emerald-500/20 text-white rounded-lg font-semibold transition-all">
                    <i class="fas fa-plus"></i> Add Gacch
                </button>
            </div>
        </div>
        <div class="mb-6 flex items-center justify-between">
            <h2 class="text-2xl font-bold text-white flex items-center">
                <i class="fas fa-layer-group text-indigo-400 mr-3"></i> Gacchs List
            </h2>
            <span class="text-sm text-gray-400">Total: <span class="text-indigo-400 font-bold" id="gacchs-count">{{ $gacchs->total() }}</span></span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-700/50 border-b-2 border-slate-600/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-indigo-400 uppercase">Gacch Name</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-indigo-400 uppercase">Description</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-indigo-400 uppercase">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-indigo-400 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/30">
                    @forelse($gacchs as $gacch)
                        <tr class="hover:bg-slate-700/20 transition-colors">
                            <td class="px-6 py-4">
                                <span class="text-white font-semibold">{{ $gacch->name }}</span>
                            </td>
                            <td class="px-6 py-4 text-gray-300">
                                {{ $gacch->description ?? '-' }}
                            </td>
                            <td class="px-6 py-4">
                                @if($gacch->is_active)
                                    <span class="px-3 py-1 bg-green-500/20 text-green-200 rounded-lg text-xs font-semibold border border-green-500/30">
                                        <i class="fas fa-check-circle"></i> Active
                                    </span>
                                @else
                                    <span class="px-3 py-1 bg-red-500/20 text-red-200 rounded-lg text-xs font-semibold border border-red-500/30">
                                        <i class="fas fa-times-circle"></i> Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    <button onclick="openEditGacchModal({{ $gacch->id }}, '{{ addslashes($gacch->name) }}', '{{ addslashes($gacch->description ?? '') }}')" class="text-emerald-400 hover:text-emerald-300 transition-colors" title="Edit">
                                        <i class="fas fa-edit text-lg"></i>
                                    </button>
                                    <form action="{{ route('admin.event-gacchs.toggle', $gacch) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="{{ $gacch->is_active ? 'text-amber-400 hover:text-amber-300' : 'text-green-400 hover:text-green-300' }} transition-colors" title="{{ $gacch->is_active ? 'Deactivate' : 'Activate' }}">
                                            <i class="fas {{ $gacch->is_active ? 'fa-toggle-on' : 'fa-toggle-off' }} text-lg"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.event-gacchs.destroy', $gacch) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Delete this gacch?')" class="text-red-400 hover:text-red-300 transition-colors" title="Delete">
                                            <i class="fas fa-trash text-lg"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-16 text-center text-gray-400">
                                <i class="fas fa-layer-group text-6xl text-gray-600 mb-4 block"></i>
                                <p class="text-lg">No gacchs found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $gacchs->links() }}
        </div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="card-luxury p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-400 text-sm mb-2">Total Gacchs</p>
                <p class="text-4xl font-bold text-indigo-400">{{ $gacchs->total() }}</p>
            </div>
            <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-indigo-500/20 to-indigo-600/20 flex items-center justify-center text-indigo-400 text-3xl border border-indigo-500/30">
                <i class="fas fa-layer-group"></i>
            </div>
        </div>
    </div>

    <div class="card-luxury p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-400 text-sm mb-2">Active Gacchs</p>
                <p class="text-4xl font-bold text-emerald-400">{{ \App\Models\EventGacchh::where('is_active', true)->count() }}</p>
            </div>
            <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-emerald-500/20 to-teal-600/20 flex items-center justify-center text-emerald-400 text-3xl border border-emerald-500/30">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
    </div>
</div>
</div>

<!-- Tags Tab -->
<div id="tags-tab" class="tab-content hidden">
<div class="card-luxury mb-8">
    <div class="p-6">
        <div class="mb-6 flex items-center justify-between">
            <div></div>
            <div class="flex gap-3">
                <button onclick="document.getElementById('uploadTagModal').classList.remove('hidden')" class="px-6 py-2 bg-gradient-to-r from-blue-500 to-blue-600 hover:shadow-lg hover:shadow-blue-500/20 text-white rounded-lg font-semibold transition-all">
                    <i class="fas fa-upload"></i> Upload CSV/Excel
                </button>
                <button onclick="document.getElementById('addTagModal').classList.remove('hidden')" class="px-6 py-2 bg-gradient-to-r from-emerald-500 to-teal-500 hover:shadow-lg hover:shadow-emerald-500/20 text-white rounded-lg font-semibold transition-all">
                    <i class="fas fa-plus"></i> Add Tag
                </button>
            </div>
        </div>
        <div class="mb-6 flex items-center justify-between">
            <h2 class="text-2xl font-bold text-white flex items-center">
                <i class="fas fa-hashtag text-red-400 mr-3"></i> Tags List
            </h2>
            <span class="text-sm text-gray-400">Total: <span class="text-red-400 font-bold" id="tags-count">{{ $tags->total() }}</span></span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-700/50 border-b-2 border-slate-600/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-red-400 uppercase">Tag Name</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-red-400 uppercase">Description</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-red-400 uppercase">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-red-400 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/30">
                    @forelse($tags as $tag)
                        <tr class="hover:bg-slate-700/20 transition-colors">
                            <td class="px-6 py-4">
                                <span class="text-white font-semibold">{{ $tag->name }}</span>
                            </td>
                            <td class="px-6 py-4 text-gray-300">
                                {{ $tag->description ?? '-' }}
                            </td>
                            <td class="px-6 py-4">
                                @if($tag->is_active)
                                    <span class="px-3 py-1 bg-green-500/20 text-green-200 rounded-lg text-xs font-semibold border border-green-500/30">
                                        <i class="fas fa-check-circle"></i> Active
                                    </span>
                                @else
                                    <span class="px-3 py-1 bg-red-500/20 text-red-200 rounded-lg text-xs font-semibold border border-red-500/30">
                                        <i class="fas fa-times-circle"></i> Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    <button onclick="openEditTagModal({{ $tag->id }}, '{{ addslashes($tag->name) }}', '{{ addslashes($tag->description ?? '') }}')" class="text-emerald-400 hover:text-emerald-300 transition-colors" title="Edit">
                                        <i class="fas fa-edit text-lg"></i>
                                    </button>
                                    <form action="{{ route('admin.event-tags.toggle', $tag) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="{{ $tag->is_active ? 'text-amber-400 hover:text-amber-300' : 'text-green-400 hover:text-green-300' }} transition-colors" title="{{ $tag->is_active ? 'Deactivate' : 'Activate' }}">
                                            <i class="fas {{ $tag->is_active ? 'fa-toggle-on' : 'fa-toggle-off' }} text-lg"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.event-tags.destroy', $tag) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Delete this tag?')" class="text-red-400 hover:text-red-300 transition-colors" title="Delete">
                                            <i class="fas fa-trash text-lg"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-16 text-center text-gray-400">
                                <i class="fas fa-hashtag text-6xl text-gray-600 mb-4 block"></i>
                                <p class="text-lg">No tags found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $tags->links() }}
        </div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="card-luxury p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-400 text-sm mb-2">Total Tags</p>
                <p class="text-4xl font-bold text-red-400">{{ $tags->total() }}</p>
            </div>
            <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-red-500/20 to-red-600/20 flex items-center justify-center text-red-400 text-3xl border border-red-500/30">
                <i class="fas fa-hashtag"></i>
            </div>
        </div>
    </div>

    <div class="card-luxury p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-400 text-sm mb-2">Active Tags</p>
                <p class="text-4xl font-bold text-emerald-400">{{ \App\Models\EventTag::where('is_active', true)->count() }}</p>
            </div>
            <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-emerald-500/20 to-teal-600/20 flex items-center justify-center text-emerald-400 text-3xl border border-emerald-500/30">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
    </div>
</div>
</div>

<!-- Modals for Communities -->
<div id="addCommunityModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50">
    <div class="card-luxury rounded-2xl p-8 max-w-md w-full mx-4">
        <h3 class="text-2xl font-bold text-white mb-6">Add New Community</h3>
        <form action="{{ route('admin.event-communities.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-gray-300 font-bold mb-2">Community Name</label>
                <input type="text" name="name" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" required>
            </div>
            <div>
                <label class="block text-gray-300 font-bold mb-2">Description</label>
                <textarea name="description" rows="3" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500"></textarea>
            </div>

            <div class="flex space-x-3 pt-4">
                <button type="submit" class="flex-1 px-6 py-2 bg-gradient-to-r from-emerald-500 to-teal-500 hover:shadow-lg hover:shadow-emerald-500/20 text-white rounded-lg font-semibold transition-all">
                    <i class="fas fa-check mr-2"></i> Create Community
                </button>
                <button type="button" onclick="document.getElementById('addCommunityModal').classList.add('hidden')" class="flex-1 px-6 py-2 bg-slate-700 hover:bg-slate-600 text-gray-300 rounded-lg font-semibold transition-all border border-slate-600">
                    <i class="fas fa-times mr-2"></i> Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<div id="editCommunityModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50">
    <div class="card-luxury rounded-2xl p-8 max-w-md w-full mx-4">
        <h3 class="text-2xl font-bold text-white mb-6">Edit Community</h3>
        <form id="editCommunityForm" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-gray-300 font-bold mb-2">Community Name</label>
                <input type="text" id="edit_community_name" name="name" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" required>
            </div>
            <div>
                <label class="block text-gray-300 font-bold mb-2">Description</label>
                <textarea id="edit_community_description" name="description" rows="3" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500"></textarea>
            </div>

            <div class="flex space-x-3 pt-4">
                <button type="submit" class="flex-1 px-6 py-2 bg-gradient-to-r from-emerald-500 to-teal-500 hover:shadow-lg hover:shadow-emerald-500/20 text-white rounded-lg font-semibold transition-all">
                    <i class="fas fa-save mr-2"></i> Update Community
                </button>
                <button type="button" onclick="document.getElementById('editCommunityModal').classList.add('hidden')" class="flex-1 px-6 py-2 bg-slate-700 hover:bg-slate-600 text-gray-300 rounded-lg font-semibold transition-all border border-slate-600">
                    <i class="fas fa-times mr-2"></i> Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modals for Gacchs -->
<div id="addGacchModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50">
    <div class="card-luxury rounded-2xl p-8 max-w-md w-full mx-4">
        <h3 class="text-2xl font-bold text-white mb-6">Add New Gacch</h3>
        <form action="{{ route('admin.event-gacchs.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-gray-300 font-bold mb-2">Gacch Name</label>
                <input type="text" name="name" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" required>
            </div>
            <div>
                <label class="block text-gray-300 font-bold mb-2">Description</label>
                <textarea name="description" rows="3" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500"></textarea>
            </div>

            <div class="flex space-x-3 pt-4">
                <button type="submit" class="flex-1 px-6 py-2 bg-gradient-to-r from-emerald-500 to-teal-500 hover:shadow-lg hover:shadow-emerald-500/20 text-white rounded-lg font-semibold transition-all">
                    <i class="fas fa-check mr-2"></i> Create Gacch
                </button>
                <button type="button" onclick="document.getElementById('addGacchModal').classList.add('hidden')" class="flex-1 px-6 py-2 bg-slate-700 hover:bg-slate-600 text-gray-300 rounded-lg font-semibold transition-all border border-slate-600">
                    <i class="fas fa-times mr-2"></i> Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<div id="editGacchModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50">
    <div class="card-luxury rounded-2xl p-8 max-w-md w-full mx-4">
        <h3 class="text-2xl font-bold text-white mb-6">Edit Gacch</h3>
        <form id="editGacchForm" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-gray-300 font-bold mb-2">Gacch Name</label>
                <input type="text" id="edit_gacch_name" name="name" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" required>
            </div>
            <div>
                <label class="block text-gray-300 font-bold mb-2">Description</label>
                <textarea id="edit_gacch_description" name="description" rows="3" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500"></textarea>
            </div>

            <div class="flex space-x-3 pt-4">
                <button type="submit" class="flex-1 px-6 py-2 bg-gradient-to-r from-emerald-500 to-teal-500 hover:shadow-lg hover:shadow-emerald-500/20 text-white rounded-lg font-semibold transition-all">
                    <i class="fas fa-save mr-2"></i> Update Gacch
                </button>
                <button type="button" onclick="document.getElementById('editGacchModal').classList.add('hidden')" class="flex-1 px-6 py-2 bg-slate-700 hover:bg-slate-600 text-gray-300 rounded-lg font-semibold transition-all border border-slate-600">
                    <i class="fas fa-times mr-2"></i> Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modals for Tags -->
<div id="addTagModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50">
    <div class="card-luxury rounded-2xl p-8 max-w-md w-full mx-4">
        <h3 class="text-2xl font-bold text-white mb-6">Add New Tag</h3>
        <form action="{{ route('admin.event-tags.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-gray-300 font-bold mb-2">Tag Name</label>
                <input type="text" name="name" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" required>
            </div>
            <div>
                <label class="block text-gray-300 font-bold mb-2">Description</label>
                <textarea name="description" rows="3" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500"></textarea>
            </div>

            <div class="flex space-x-3 pt-4">
                <button type="submit" class="flex-1 px-6 py-2 bg-gradient-to-r from-emerald-500 to-teal-500 hover:shadow-lg hover:shadow-emerald-500/20 text-white rounded-lg font-semibold transition-all">
                    <i class="fas fa-check mr-2"></i> Create Tag
                </button>
                <button type="button" onclick="document.getElementById('addTagModal').classList.add('hidden')" class="flex-1 px-6 py-2 bg-slate-700 hover:bg-slate-600 text-gray-300 rounded-lg font-semibold transition-all border border-slate-600">
                    <i class="fas fa-times mr-2"></i> Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<div id="editTagModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50">
    <div class="card-luxury rounded-2xl p-8 max-w-md w-full mx-4">
        <h3 class="text-2xl font-bold text-white mb-6">Edit Tag</h3>
        <form id="editTagForm" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-gray-300 font-bold mb-2">Tag Name</label>
                <input type="text" id="edit_tag_name" name="name" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" required>
            </div>
            <div>
                <label class="block text-gray-300 font-bold mb-2">Description</label>
                <textarea id="edit_tag_description" name="description" rows="3" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500"></textarea>
            </div>

            <div class="flex space-x-3 pt-4">
                <button type="submit" class="flex-1 px-6 py-2 bg-gradient-to-r from-emerald-500 to-teal-500 hover:shadow-lg hover:shadow-emerald-500/20 text-white rounded-lg font-semibold transition-all">
                    <i class="fas fa-save mr-2"></i> Update Tag
                </button>
                <button type="button" onclick="document.getElementById('editTagModal').classList.add('hidden')" class="flex-1 px-6 py-2 bg-slate-700 hover:bg-slate-600 text-gray-300 rounded-lg font-semibold transition-all border border-slate-600">
                    <i class="fas fa-times mr-2"></i> Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Upload Modal for Categories -->
<div id="uploadCategoryModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50">
    <div class="card-luxury rounded-2xl p-8 max-w-md w-full mx-4">
        <h3 class="text-2xl font-bold text-white mb-2">Upload Categories</h3>
        <p class="text-gray-400 text-sm mb-6">Upload CSV or Excel file (columns: category_name, description)</p>
        <form action="{{ route('admin.event-categories.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-gray-300 font-bold mb-2">Select File</label>
                <input type="file" name="file" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" accept=".csv,.xlsx,.xls" required>
                <p class="text-xs text-gray-400 mt-2">Accepted formats: CSV, XLS, XLSX</p>
            </div>

            <div class="flex space-x-3 pt-4">
                <button type="submit" class="flex-1 px-6 py-2 bg-gradient-to-r from-blue-500 to-blue-600 hover:shadow-lg hover:shadow-blue-500/20 text-white rounded-lg font-semibold transition-all">
                    <i class="fas fa-upload mr-2"></i> Upload
                </button>
                <button type="button" onclick="document.getElementById('uploadCategoryModal').classList.add('hidden')" class="flex-1 px-6 py-2 bg-slate-700 hover:bg-slate-600 text-gray-300 rounded-lg font-semibold transition-all border border-slate-600">
                    <i class="fas fa-times mr-2"></i> Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Upload Modal for Category Names -->
<div id="uploadCategoryNameModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50">
    <div class="card-luxury rounded-2xl p-8 max-w-md w-full mx-4">
        <h3 class="text-2xl font-bold text-white mb-2">Upload Category Names</h3>
        <p class="text-gray-400 text-sm mb-6">Upload CSV or Excel file (columns: category_name, description)</p>
        <form action="{{ route('admin.event-categories.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-gray-300 font-bold mb-2">Select File</label>
                <input type="file" name="file" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" accept=".csv,.xlsx,.xls" required>
                <p class="text-xs text-gray-400 mt-2">Accepted formats: CSV, XLS, XLSX</p>
            </div>

            <div class="flex space-x-3 pt-4">
                <button type="submit" class="flex-1 px-6 py-2 bg-gradient-to-r from-blue-500 to-blue-600 hover:shadow-lg hover:shadow-blue-500/20 text-white rounded-lg font-semibold transition-all">
                    <i class="fas fa-upload mr-2"></i> Upload
                </button>
                <button type="button" onclick="document.getElementById('uploadCategoryNameModal').classList.add('hidden')" class="flex-1 px-6 py-2 bg-slate-700 hover:bg-slate-600 text-gray-300 rounded-lg font-semibold transition-all border border-slate-600">
                    <i class="fas fa-times mr-2"></i> Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Upload Modal for Communities -->
<div id="uploadCommunityModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50">
    <div class="card-luxury rounded-2xl p-8 max-w-md w-full mx-4">
        <h3 class="text-2xl font-bold text-white mb-2">Upload Communities</h3>
        <p class="text-gray-400 text-sm mb-6">Upload CSV or Excel file (columns: name, description)</p>
        <form action="{{ route('admin.event-communities.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-gray-300 font-bold mb-2">Select File</label>
                <input type="file" name="file" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" accept=".csv,.xlsx,.xls" required>
                <p class="text-xs text-gray-400 mt-2">Accepted formats: CSV, XLS, XLSX</p>
            </div>

            <div class="flex space-x-3 pt-4">
                <button type="submit" class="flex-1 px-6 py-2 bg-gradient-to-r from-blue-500 to-blue-600 hover:shadow-lg hover:shadow-blue-500/20 text-white rounded-lg font-semibold transition-all">
                    <i class="fas fa-upload mr-2"></i> Upload
                </button>
                <button type="button" onclick="document.getElementById('uploadCommunityModal').classList.add('hidden')" class="flex-1 px-6 py-2 bg-slate-700 hover:bg-slate-600 text-gray-300 rounded-lg font-semibold transition-all border border-slate-600">
                    <i class="fas fa-times mr-2"></i> Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Upload Modal for Gacchs -->
<div id="uploadGacchModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50">
    <div class="card-luxury rounded-2xl p-8 max-w-md w-full mx-4">
        <h3 class="text-2xl font-bold text-white mb-2">Upload Gacchs</h3>
        <p class="text-gray-400 text-sm mb-6">Upload CSV or Excel file (columns: name, description)</p>
        <form action="{{ route('admin.event-gacchs.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-gray-300 font-bold mb-2">Select File</label>
                <input type="file" name="file" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" accept=".csv,.xlsx,.xls" required>
                <p class="text-xs text-gray-400 mt-2">Accepted formats: CSV, XLS, XLSX</p>
            </div>

            <div class="flex space-x-3 pt-4">
                <button type="submit" class="flex-1 px-6 py-2 bg-gradient-to-r from-blue-500 to-blue-600 hover:shadow-lg hover:shadow-blue-500/20 text-white rounded-lg font-semibold transition-all">
                    <i class="fas fa-upload mr-2"></i> Upload
                </button>
                <button type="button" onclick="document.getElementById('uploadGacchModal').classList.add('hidden')" class="flex-1 px-6 py-2 bg-slate-700 hover:bg-slate-600 text-gray-300 rounded-lg font-semibold transition-all border border-slate-600">
                    <i class="fas fa-times mr-2"></i> Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Upload Modal for Tags -->
<div id="uploadTagModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50">
    <div class="card-luxury rounded-2xl p-8 max-w-md w-full mx-4">
        <h3 class="text-2xl font-bold text-white mb-2">Upload Tags</h3>
        <p class="text-gray-400 text-sm mb-6">Upload CSV or Excel file (columns: name, description)</p>
        <form action="{{ route('admin.event-tags.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-gray-300 font-bold mb-2">Select File</label>
                <input type="file" name="file" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" accept=".csv,.xlsx,.xls" required>
                <p class="text-xs text-gray-400 mt-2">Accepted formats: CSV, XLS, XLSX</p>
            </div>

            <div class="flex space-x-3 pt-4">
                <button type="submit" class="flex-1 px-6 py-2 bg-gradient-to-r from-blue-500 to-blue-600 hover:shadow-lg hover:shadow-blue-500/20 text-white rounded-lg font-semibold transition-all">
                    <i class="fas fa-upload mr-2"></i> Upload
                </button>
                <button type="button" onclick="document.getElementById('uploadTagModal').classList.add('hidden')" class="flex-1 px-6 py-2 bg-slate-700 hover:bg-slate-600 text-gray-300 rounded-lg font-semibold transition-all border border-slate-600">
                    <i class="fas fa-times mr-2"></i> Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modals for Category Names -->
<div id="addCategoryNameModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50">
    <div class="card-luxury rounded-2xl p-8 max-w-md w-full mx-4">
        <h3 class="text-2xl font-bold text-white mb-6">Add New Category Name</h3>
        <form action="{{ route('admin.event-categories.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-gray-300 font-bold mb-2">Category Name</label>
                <input type="text" name="category_name" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" required>
            </div>
            <div>
                <label class="block text-gray-300 font-bold mb-2">Description</label>
                <textarea name="description" rows="3" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500"></textarea>
            </div>

            <div class="flex space-x-3 pt-4">
                <button type="submit" class="flex-1 px-6 py-2 bg-gradient-to-r from-emerald-500 to-teal-500 hover:shadow-lg hover:shadow-emerald-500/20 text-white rounded-lg font-semibold transition-all">
                    <i class="fas fa-check mr-2"></i> Create Category Name
                </button>
                <button type="button" onclick="document.getElementById('addCategoryNameModal').classList.add('hidden')" class="flex-1 px-6 py-2 bg-slate-700 hover:bg-slate-600 text-gray-300 rounded-lg font-semibold transition-all border border-slate-600">
                    <i class="fas fa-times mr-2"></i> Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<div id="editCategoryNameModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50">
    <div class="card-luxury rounded-2xl p-8 max-w-md w-full mx-4">
        <h3 class="text-2xl font-bold text-white mb-6">Edit Category Name</h3>
        <form id="editCategoryNameForm" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-gray-300 font-bold mb-2">Category Name</label>
                <input type="text" id="edit_categoryname_name" name="category_name" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" required>
            </div>
            <div>
                <label class="block text-gray-300 font-bold mb-2">Description</label>
                <textarea id="edit_categoryname_description" name="description" rows="3" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500"></textarea>
            </div>

            <div class="flex space-x-3 pt-4">
                <button type="submit" class="flex-1 px-6 py-2 bg-gradient-to-r from-emerald-500 to-teal-500 hover:shadow-lg hover:shadow-emerald-500/20 text-white rounded-lg font-semibold transition-all">
                    <i class="fas fa-save mr-2"></i> Update Category Name
                </button>
                <button type="button" onclick="document.getElementById('editCategoryNameModal').classList.add('hidden')" class="flex-1 px-6 py-2 bg-slate-700 hover:bg-slate-600 text-gray-300 rounded-lg font-semibold transition-all border border-slate-600">
                    <i class="fas fa-times mr-2"></i> Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<div id="addCategoryModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50">
    <div class="card-luxury rounded-2xl p-8 max-w-md w-full mx-4">
        <h3 class="text-2xl font-bold text-white mb-6">Add New Category</h3>
        <form action="{{ route('admin.event-categories.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-gray-300 font-bold mb-2">Category Name</label>
                <input type="text" name="category_name" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" required>
            </div>

            <div class="flex space-x-3 pt-4">
                <button type="submit" class="flex-1 px-6 py-2 bg-gradient-to-r from-emerald-500 to-teal-500 hover:shadow-lg hover:shadow-emerald-500/20 text-white rounded-lg font-semibold transition-all">
                    <i class="fas fa-check mr-2"></i> Create Category
                </button>
                <button type="button" onclick="document.getElementById('addCategoryModal').classList.add('hidden')" class="flex-1 px-6 py-2 bg-slate-700 hover:bg-slate-600 text-gray-300 rounded-lg font-semibold transition-all border border-slate-600">
                    <i class="fas fa-times mr-2"></i> Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Category Modal -->
<div id="editCategoryModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50">
    <div class="card-luxury rounded-2xl p-8 max-w-md w-full mx-4">
        <h3 class="text-2xl font-bold text-white mb-6">Edit Category</h3>
        <form id="editForm" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-gray-300 font-bold mb-2">Category Name</label>
                <input type="text" id="edit_category_name" name="category_name" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" required>
            </div>

            <div class="flex space-x-3 pt-4">
                <button type="submit" class="flex-1 px-6 py-2 bg-gradient-to-r from-emerald-500 to-teal-500 hover:shadow-lg hover:shadow-emerald-500/20 text-white rounded-lg font-semibold transition-all">
                    <i class="fas fa-save mr-2"></i> Update Category
                </button>
                <button type="button" onclick="document.getElementById('editCategoryModal').classList.add('hidden')" class="flex-1 px-6 py-2 bg-slate-700 hover:bg-slate-600 text-gray-300 rounded-lg font-semibold transition-all border border-slate-600">
                    <i class="fas fa-times mr-2"></i> Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function showTab(tabName) {
    // Hide all tabs
    const tabs = document.querySelectorAll('.tab-content');
    tabs.forEach(tab => tab.classList.add('hidden'));
    
    // Remove active state from all buttons
    const buttons = document.querySelectorAll('.tab-btn');
    buttons.forEach(btn => {
        btn.classList.remove('bg-gradient-to-r', 'from-amber-500/20', 'to-amber-600/20', 'text-amber-300', 'border-amber-500/30');
        btn.classList.add('bg-slate-700/50', 'text-gray-300', 'border-slate-600/30');
    });
    
    // Show selected tab
    document.getElementById(tabName + '-tab').classList.remove('hidden');
    
    // Highlight active button
    event.target.closest('.tab-btn').classList.remove('bg-slate-700/50', 'text-gray-300', 'border-slate-600/30');
    event.target.closest('.tab-btn').classList.add('bg-gradient-to-r', 'from-amber-500/20', 'to-amber-600/20', 'text-amber-300', 'border-amber-500/30');
}

function openEditModal(id, name, community, gacchh, tags, description) {
    document.getElementById('edit_category_name').value = name;
    document.getElementById('editForm').action = `/admin/event-categories/${id}`;
    document.getElementById('editCategoryModal').classList.remove('hidden');
}

function openEditCommunityModal(id, name, description) {
    document.getElementById('edit_community_name').value = name;
    document.getElementById('edit_community_description').value = description;
    document.getElementById('editCommunityForm').action = `/admin/event-communities/${id}`;
    document.getElementById('editCommunityModal').classList.remove('hidden');
}

function openEditGacchModal(id, name, description) {
    document.getElementById('edit_gacch_name').value = name;
    document.getElementById('edit_gacch_description').value = description;
    document.getElementById('editGacchForm').action = `/admin/event-gacchs/${id}`;
    document.getElementById('editGacchModal').classList.remove('hidden');
}

function openEditTagModal(id, name, description) {
    document.getElementById('edit_tag_name').value = name;
    document.getElementById('edit_tag_description').value = description;
    document.getElementById('editTagForm').action = `/admin/event-tags/${id}`;
    document.getElementById('editTagModal').classList.remove('hidden');
}

function openEditCategoryNameModal(id, name, description) {
    document.getElementById('edit_categoryname_name').value = name;
    document.getElementById('edit_categoryname_description').value = description;
    document.getElementById('editCategoryNameForm').action = `/admin/event-categories/${id}`;
    document.getElementById('editCategoryNameModal').classList.remove('hidden');
}
</script>

<style>
    .card-luxury {
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.8) 0%, rgba(30, 41, 59, 0.8) 100%);
        border: 1px solid rgba(148, 113, 113, 0.2);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3);
        border-radius: 0.75rem;
        transition: all 0.3s ease;
    }
    
    .card-luxury:hover {
        border-color: rgba(217, 119, 6, 0.4);
        box-shadow: 0 25px 30px -5px rgba(217, 119, 6, 0.1);
    }
    .fa-edit:before, .fa-pen-to-square:before{
        color: aqua;
    }
</style>
@endsection
