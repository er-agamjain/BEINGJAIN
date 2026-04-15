@extends('layouts.app')

@section('title', 'Bookings')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-white mb-2">
            <i class="fas fa-ticket-alt text-amber-400"></i> Bookings Management
        </h1>
        <p class="text-gray-400">Manage all event bookings and track their status</p>
    </div>

    <!-- Filters -->
    <div class="card-luxury p-6 mb-8">
        <form method="GET" action="{{ route('admin.bookings.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-gray-300 font-semibold mb-2">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Reference, user, event..." class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500">
            </div>
            
            <div>
                <label class="block text-gray-300 font-semibold mb-2">Status</label>
                <select name="status" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500">
                    <option value="">All Status</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>
            
            <div>
                <label class="block text-gray-300 font-semibold mb-2">From Date</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500">
            </div>
            
            <div>
                <label class="block text-gray-300 font-semibold mb-2">To Date</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500">
            </div>
            
            <div class="flex items-end gap-2">
                <button type="submit" class="px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold rounded-lg transition flex-1">
                    <i class="fas fa-filter mr-2"></i> Filter
                </button>
                <a href="{{ route('admin.bookings.index') }}" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-white rounded-lg transition">
                    <i class="fas fa-redo"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Export Button -->
    <div class="mb-4 flex justify-end">
        <a href="{{ route('admin.bookings.export', request()->all()) }}" class="px-4 py-2 bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 text-white font-semibold rounded-lg transition">
            <i class="fas fa-download mr-2"></i> Export CSV
        </a>
    </div>

    <!-- Bookings Table -->
    <div class="card-luxury overflow-hidden">
        <div class="p-6 border-b border-slate-700/30">
            <h2 class="text-2xl font-bold text-white flex items-center">
                <i class="fas fa-list text-amber-400 mr-3"></i> Booking Records
            </h2>
            <p class="text-gray-400 text-sm mt-1">Found <span class="text-amber-400 font-bold">{{ $bookings->total() }}</span> booking(s)</p>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-700/50 border-b-2 border-slate-600/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-amber-400 uppercase">Reference</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-amber-400 uppercase">User</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-amber-400 uppercase">Event</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-amber-400 uppercase">Ticket Type</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-amber-400 uppercase">Qty</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-amber-400 uppercase">Total Price</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-amber-400 uppercase">Payments Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-amber-400 uppercase">Date</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-amber-400 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/30">
                @forelse($bookings as $booking)
                    <tr class="hover:bg-slate-700/20 transition-colors group border-b border-slate-700/30">
                        <td class="px-6 py-4 font-mono text-sm font-semibold text-amber-400">#{{ $booking->id }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-amber-400 to-orange-400 flex items-center justify-center text-slate-900 font-bold mr-3">
                                    {{ strtoupper(substr($booking->user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-medium text-white">{{ $booking->user->name }}</div>
                                    <div class="text-xs text-gray-400">{{ $booking->user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div>
                                <div class="font-medium text-white">{{ $booking->event->title }}</div>
                                <div class="text-xs text-gray-400">{{ optional($booking->event->event_date ? \Carbon\Carbon::parse($booking->event->event_date) : null)->format('M d, Y') }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-300">
                            <span class="px-3 py-1 bg-purple-500/20 text-purple-200 rounded-lg text-xs font-semibold border border-purple-500/30">
                                {{ optional($booking->ticket)->name ?? 'N/A' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center font-semibold text-white">{{ $booking->quantity ?? 'N/A' }}</td>
                        <td class="px-6 py-4">
                            <span class="text-lg font-bold text-emerald-400">₹{{ number_format($booking->total_amount ?? $booking->total_price ?? 0, 2) }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @switch($booking->status)
                                @case('confirmed')
                                    <span class="px-3 py-1 bg-green-500/20 text-green-200 rounded-lg text-xs font-semibold border border-green-500/30">
                                        <i class="fas fa-check"></i> Confirmed
                                    </span>
                                    @break
                                @case('cancelled')
                                    <span class="px-3 py-1 bg-red-500/20 text-red-200 rounded-lg text-xs font-semibold border border-red-500/30">
                                        <i class="fas fa-ban"></i> Cancelled
                                    </span>
                                    @break
                                @case('completed')
                                    <span class="px-3 py-1 bg-blue-500/20 text-blue-200 rounded-lg text-xs font-semibold border border-blue-500/30">
                                        <i class="fas fa-check-circle"></i> Completed
                                    </span>
                                    @break
                                @default
                                    <span class="px-3 py-1 bg-yellow-500/20 text-yellow-200 rounded-lg text-xs font-semibold border border-yellow-500/30">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                            @endswitch
                        </td>
                        <td class="px-6 py-4 text-gray-400 text-sm">{{ $booking->created_at->format('M d, Y h:i A') }}</td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.bookings.show', $booking) }}" class="px-4 py-2 bg-gradient-to-r from-emerald-500 to-teal-500 hover:shadow-lg hover:shadow-emerald-500/20 text-white font-medium rounded-lg transition-all text-xs px-3 py-1">
                                <i class="fas fa-eye"></i> View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-6 py-16 text-center text-gray-400">
                            <i class="fas fa-ticket-alt text-6xl text-gray-600 mb-4 block"></i>
                            <p class="text-lg">No bookings found</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-6">
        {{ $bookings->links() }}
    </div>
</div>

<!-- Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-8">
    @php
        $totalBookings = $bookings->count();
        $totalRevenue = $bookings->sum(fn($b) => $b->total_amount ?? $b->total_price ?? 0);
        $confirmedCount = $bookings->where('status', 'confirmed')->count();
        $paidCount = 0;
    @endphp
    
    <div class="card-luxury p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-400 text-sm mb-2">Total Bookings</p>
                <p class="text-4xl font-bold text-blue-400">{{ $totalBookings }}</p>
            </div>
            <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-blue-500/20 to-blue-600/20 flex items-center justify-center text-blue-400 text-3xl border border-blue-500/30">
                <i class="fas fa-ticket-alt"></i>
            </div>
        </div>
    </div>
    
    <div class="card-luxury p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-400 text-sm mb-2">Total Revenue</p>
                <p class="text-4xl font-bold text-emerald-400">₹{{ number_format($totalRevenue, 2) }}</p>
            </div>
            <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-emerald-500/20 to-teal-600/20 flex items-center justify-center text-emerald-400 text-3xl border border-emerald-500/30">
                <i class="fas fa-indian-rupee-sign"></i>
            </div>
        </div>
    </div>
    
    <div class="card-luxury p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-400 text-sm mb-2">Confirmed</p>
                <p class="text-4xl font-bold text-purple-400">{{ $confirmedCount }}</p>
            </div>
            <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-purple-500/20 to-purple-600/20 flex items-center justify-center text-purple-400 text-3xl border border-purple-500/30">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
    </div>
    
    <div class="card-luxury p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-400 text-sm mb-2">Paid</p>
                <p class="text-4xl font-bold text-indigo-400">{{ $paidCount }}</p>
            </div>
            <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-indigo-500/20 to-indigo-600/20 flex items-center justify-center text-indigo-400 text-3xl border border-indigo-500/30">
                <i class="fas fa-check"></i>
            </div>
        </div>
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
