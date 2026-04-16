@extends('layouts.app')

@section('title', 'Admin Venues')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="card-luxury rounded-lg p-6 mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-4xl font-bold text-white mb-2">Venue Management</h1>
                    <p class="text-gray-400">Create and manage venues available for all admin-managed events</p>
                </div>
                <a href="{{ route('admin.venues.create') }}" class="px-6 py-3 bg-gradient-to-r from-emerald-500 to-teal-500 text-white rounded-lg hover:shadow-lg hover:shadow-emerald-500/20 font-semibold transition">
                    <i class="fas fa-plus mr-2"></i> Add Venue
                </a>
            </div>
        </div>

        <div class="overflow-x-auto rounded-lg">
            <div class="card-luxury">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-600">
                            <th class="px-6 py-4 text-left text-amber-400 font-semibold">Name</th>
                            <th class="px-6 py-4 text-left text-amber-400 font-semibold">City</th>
                            <th class="px-6 py-4 text-left text-amber-400 font-semibold">Owner</th>
                            <th class="px-6 py-4 text-left text-amber-400 font-semibold">Address</th>
                            <th class="px-6 py-4 text-left text-amber-400 font-semibold">Capacity</th>
                            <th class="px-6 py-4 text-left text-amber-400 font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($venues as $venue)
                            <tr class="border-b border-slate-600/50 hover:bg-slate-700/30 transition">
                                <td class="px-6 py-4 font-semibold text-white">{{ $venue->name }}</td>
                                <td class="px-6 py-4 text-gray-300">{{ $venue->city->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-gray-300">{{ $venue->organiser->name ?? 'Admin' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-400">{{ Str::limit($venue->address, 50) }}</td>
                                <td class="px-6 py-4 text-gray-300">{{ $venue->total_capacity }}</td>
                                <td class="px-6 py-4 space-x-3">
                                    <a href="{{ route('admin.venues.edit', $venue) }}" class="text-emerald-400 hover:text-emerald-300 transition">
                                        <i class="fas fa-edit mr-1"></i> Edit
                                    </a>
                                    <a href="{{ route('admin.seat-categories.index', $venue) }}" class="text-amber-400 hover:text-amber-300 transition">
                                        <i class="fas fa-chair mr-1"></i> Seats
                                    </a>
                                    <form action="{{ route('admin.venues.destroy', $venue) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-300 transition" onclick="return confirm('Delete this venue?')">
                                            <i class="fas fa-trash mr-1"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="text-gray-400">
                                        <i class="fas fa-inbox text-4xl mb-4 block"></i>
                                        <p class="text-lg mb-4">No venues yet.</p>
                                        <a href="{{ route('admin.venues.create') }}" class="text-emerald-400 hover:text-emerald-300 transition font-semibold">Create one</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($venues->hasPages())
            <div class="mt-8">
                {{ $venues->links() }}
            </div>
        @endif
    </div>
</div>

<style>
.card-luxury {
    background: linear-gradient(135deg, rgba(15, 23, 42, 0.8) 0%, rgba(30, 41, 59, 0.8) 100%);
    border: 1px solid rgba(148, 113, 113, 0.2);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3);
}
</style>
@endsection
