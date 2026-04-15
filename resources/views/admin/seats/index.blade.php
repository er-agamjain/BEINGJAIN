@extends('layouts.app')

@section('title', 'Seats Management')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="card-luxury p-6">
        <h1 class="text-3xl font-bold mb-2 text-white">Seats Management</h1>
        <p class="text-gray-400">View, edit, and manage all event seats across venues</p>
    </div>

    <!-- Filters -->
    <div class="card-luxury p-6">
        <form method="GET" action="{{ route('admin.seats.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Show Timing</label>
                <select name="show_timing_id" class="w-full px-3 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="">All Show Timings</option>
                    @foreach($showTimings as $showTiming)
                        <option value="{{ $showTiming->id }}" {{ request('show_timing_id') == $showTiming->id ? 'selected' : '' }}>
                            {{ $showTiming->event->name ?? 'N/A' }} - {{ $showTiming->show_date_time->format('M d, Y H:i') }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Venue</label>
                <select name="venue_id" class="w-full px-3 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="">All Venues</option>
                    @foreach($venues as $venue)
                        <option value="{{ $venue->id }}" {{ request('venue_id') == $venue->id ? 'selected' : '' }}>
                            {{ $venue->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Status</label>
                <select name="status" class="w-full px-3 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="">All Statuses</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                            {{ ucfirst($status) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end">
                <button type="submit" class="w-full px-4 py-2 bg-gradient-to-r from-emerald-500 to-teal-500 hover:shadow-lg hover:shadow-emerald-500/20 text-white rounded-lg transition-all font-medium">
                    <i class="fas fa-search mr-2"></i>Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @php
            $available = $seats->where('status', 'available')->count();
            $reserved = $seats->where('status', 'reserved')->count();
            $booked = $seats->where('status', 'booked')->count();
            $blocked = $seats->where('status', 'blocked')->count();
        @endphp
        <div class="card-luxury p-4 border-l-4 border-emerald-500">
            <p class="text-gray-400 text-sm">Available</p>
            <p class="text-2xl font-bold text-emerald-400">{{ $available }}</p>
        </div>
        <div class="card-luxury p-4 border-l-4 border-amber-500">
            <p class="text-gray-400 text-sm">Reserved</p>
            <p class="text-2xl font-bold text-amber-400">{{ $reserved }}</p>
        </div>
        <div class="card-luxury p-4 border-l-4 border-blue-500">
            <p class="text-gray-400 text-sm">Booked</p>
            <p class="text-2xl font-bold text-blue-400">{{ $booked }}</p>
        </div>
        <div class="card-luxury p-4 border-l-4 border-red-500">
            <p class="text-gray-400 text-sm">Blocked</p>
            <p class="text-2xl font-bold text-red-400">{{ $blocked }}</p>
        </div>
    </div>

    <!-- Seats Table -->
    <div class="card-luxury overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-slate-700/50 border-b-2 border-slate-600/50">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-amber-400">Seat</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-amber-400">Event</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-amber-400">Show Timing</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-amber-400">Category</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-amber-400">Status</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-amber-400">Price</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-amber-400">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-700/30">
                @forelse($seats as $seat)
                    <tr class="hover:bg-slate-700/20 transition-colors">
                        <td class="px-6 py-4 text-sm font-medium text-white">
                            {{ $seat->seat_number }}
                            <br>
                            <span class="text-xs text-gray-400">Row {{ $seat->row_number }}, Col {{ $seat->column_number }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-300">
                            {{ $seat->showTiming->event->name ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-300">
                            {{ $seat->showTiming->show_date_time->format('M d, Y H:i') }}
                            <br>
                            <span class="text-xs text-gray-400">{{ $seat->showTiming->venue->name ?? 'N/A' }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-300">
                            <span class="px-2 py-1 rounded text-xs font-semibold" style="background-color: {{ $seat->seatCategory->color }}33; color: {{ $seat->seatCategory->color }};">
                                {{ $seat->seatCategory->name }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                @if($seat->status === 'available') bg-green-500/20 text-green-200 border border-green-500/30
                                @elseif($seat->status === 'reserved') bg-amber-500/20 text-amber-200 border border-amber-500/30
                                @elseif($seat->status === 'booked') bg-blue-500/20 text-blue-200 border border-blue-500/30
                                @else bg-red-500/20 text-red-200 border border-red-500/30
                                @endif
                            ">
                                {{ ucfirst($seat->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-emerald-400 font-semibold">
                            ₹{{ number_format($seat->current_price ?? $seat->seatCategory->base_price, 2) }}
                        </td>
                        <td class="px-6 py-4 text-sm space-x-2">
                            <a href="{{ route('admin.seats.show', $seat) }}" class="text-emerald-400 hover:text-emerald-300 transition-colors">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.seats.edit', $seat) }}" class="text-emerald-400 hover:text-emerald-300 transition-colors">
                                <i class="fas fa-edit"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-400">
                            <i class="fas fa-inbox text-3xl mb-2"></i>
                            <p>No seats found</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        @if($seats->hasPages())
            <div class="px-6 py-4 border-t border-slate-700/30">
                {{ $seats->links() }}
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
        box-shadow: 0 25px 30px -5px rgba(217, 119, 6, 0.1);
    }
</style>
@endsection
