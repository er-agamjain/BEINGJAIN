@extends('layouts.app')

@section('content')
<div class="py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="card-luxury rounded-lg p-6 mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-4xl font-bold text-white mb-2">Show Timings</h1>
                    <p class="text-gray-400">Manage show timings for your events</p>
                </div>
                <a href="{{ route('organiser.show-timings.create') }}" class="px-6 py-3 bg-gradient-to-r from-emerald-500 to-teal-500 text-white rounded-lg hover:shadow-lg hover:shadow-emerald-500/20 font-semibold transition">
                    <i class="fas fa-plus mr-2"></i> Add Show Timing
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="card-luxury rounded-lg p-6 mb-6">
            <form method="GET" action="{{ route('organiser.show-timings.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-amber-400 font-bold mb-2">Event</label>
                    <select name="event_id" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
                        <option value="">All Events</option>
                        @foreach($events as $event)
                            <option value="{{ $event->id }}" {{ request('event_id') == $event->id ? 'selected' : '' }} class="bg-slate-800">
                                {{ $event->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-amber-400 font-bold mb-2">Venue</label>
                    <select name="venue_id" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
                        <option value="">All Venues</option>
                        @foreach($venues as $venue)
                            <option value="{{ $venue->id }}" {{ request('venue_id') == $venue->id ? 'selected' : '' }} class="bg-slate-800">
                                {{ $venue->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-amber-400 font-bold mb-2">Status</label>
                    <select name="status" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
                        <option value="">All Status</option>
                        <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }} class="bg-slate-800">Scheduled</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }} class="bg-slate-800">Cancelled</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }} class="bg-slate-800">Completed</option>
                    </select>
                </div>
                
                <div class="flex items-end">
                    <button type="submit" class="px-6 py-2 bg-gradient-to-r from-emerald-500 to-teal-500 text-white rounded-lg hover:shadow-lg hover:shadow-emerald-500/20 w-full font-semibold transition">
                        <i class="fas fa-filter mr-2"></i> Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Show Timings Table -->
        <div class="overflow-x-auto rounded-lg">
            <div class="card-luxury">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-600">
                            <th class="px-6 py-4 text-left text-amber-400 font-semibold">Event</th>
                            <th class="px-6 py-4 text-left text-amber-400 font-semibold">Venue</th>
                            <th class="px-6 py-4 text-left text-amber-400 font-semibold">Date & Time</th>
                            <th class="px-6 py-4 text-left text-amber-400 font-semibold">Seats</th>
                            <th class="px-6 py-4 text-left text-amber-400 font-semibold">Duration</th>
                            <th class="px-6 py-4 text-left text-amber-400 font-semibold">Status</th>
                            <th class="px-6 py-4 text-left text-amber-400 font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($showTimings as $timing)
                            <tr class="border-b border-slate-600/50 hover:bg-slate-700/30 transition">
                                <td class="px-6 py-4 text-white font-semibold">{{ $timing->event->title ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-gray-300">{{ $timing->venue->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4">
                                    <div class="text-white">{{ $timing->show_date_time->format('M d, Y') }}</div>
                                    <div class="text-sm text-gray-400">{{ $timing->show_date_time->format('h:i A') }}</div>
                                </td>
                                <td class="px-6 py-4 text-gray-300">{{ $timing->available_seats }}</td>
                                <td class="px-6 py-4 text-gray-300">{{ $timing->duration_minutes }} min</td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-sm font-semibold
                                        {{ $timing->status === 'scheduled' ? 'bg-blue-500/20 text-blue-200 border border-blue-500/30' : '' }}
                                        {{ $timing->status === 'cancelled' ? 'bg-red-500/20 text-red-200 border border-red-500/30' : '' }}
                                        {{ $timing->status === 'completed' ? 'bg-green-500/20 text-green-200 border border-green-500/30' : '' }}">
                                        {{ ucfirst($timing->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('organiser.show-timings.edit', $timing) }}" class="text-emerald-400 hover:text-emerald-300 transition">
                                        <i class="fas fa-edit mr-1"></i> Edit
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="text-gray-400">
                                        <i class="fas fa-inbox text-4xl mb-4 block"></i>
                                        <p class="text-lg mb-4">No show timings yet.</p>
                                        <a href="{{ route('organiser.show-timings.create') }}" class="text-emerald-400 hover:text-emerald-300 transition font-semibold">Create one</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($showTimings->hasPages())
            <div class="mt-8">
                {{ $showTimings->links() }}
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
