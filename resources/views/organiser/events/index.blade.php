@extends('layouts.app')

@section('content')
<div class="py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="card-luxury rounded-lg p-6 mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-4xl font-bold text-white mb-2">My Events</h1>
                    <p class="text-gray-400">Create and manage all your upcoming events</p>
                </div>
                <a href="{{ route('organiser.events.create') }}" class="px-6 py-3 bg-gradient-to-r from-emerald-500 to-teal-500 text-white rounded-lg hover:shadow-lg hover:shadow-emerald-500/20 font-semibold transition">
                    <i class="fas fa-plus mr-2"></i> Create Event
                </a>
            </div>
        </div>

        <!-- Events Table -->
        <div class="overflow-x-auto rounded-lg">
            <div class="card-luxury">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-600">
                            <th class="px-6 py-4 text-left text-amber-400 font-semibold">Name</th>
                            <th class="px-6 py-4 text-left text-amber-400 font-semibold">Date</th>
                            <th class="px-6 py-4 text-left text-amber-400 font-semibold">Location</th>
                            <th class="px-6 py-4 text-left text-amber-400 font-semibold">Capacity</th>
                            <th class="px-6 py-4 text-left text-amber-400 font-semibold">Status</th>
                            <th class="px-6 py-4 text-left text-amber-400 font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($events as $event)
                            <tr class="border-b border-slate-600/50 hover:bg-slate-700/30 transition">
                                <td class="px-6 py-4 text-white font-semibold">{{ $event->title ?? $event->name }}</td>
                                <td class="px-6 py-4 text-gray-300">{{ $event->event_date->format('M d, Y') }}</td>
                                <td class="px-6 py-4 text-gray-300">{{ $event->city->name ?? $event->location ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-gray-300">{{ $event->capacity }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-sm font-semibold
                                        {{ $event->status === 'published' ? 'bg-green-500/20 text-green-200 border border-green-500/30' : 'bg-yellow-500/20 text-yellow-200 border border-yellow-500/30' }}">
                                        {{ ucfirst($event->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 space-x-3">
                                    <a href="{{ route('organiser.events.show', $event) }}" class="text-emerald-400 hover:text-emerald-300 transition">
                                        <i class="fas fa-eye mr-1"></i> View
                                    </a>
                                    <a href="{{ route('organiser.events.edit', $event) }}" class="text-amber-400 hover:text-amber-300 transition">
                                        <i class="fas fa-edit mr-1"></i> Edit
                                    </a>
                                    <form action="{{ route('organiser.events.destroy', $event) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-300 transition" onclick="return confirm('Delete this event?')">
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
                                        <p class="text-lg mb-4">No events yet.</p>
                                        <a href="{{ route('organiser.events.create') }}" class="text-emerald-400 hover:text-emerald-300 transition font-semibold">Create one</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($events->hasPages())
            <div class="mt-8">
                {{ $events->links() }}
            </div>
        @endif
    </div>
</div>

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
        box-shadow: 0 25px 35px -5px rgba(217, 119, 6, 0.15);
    }
</style>
@endsection
