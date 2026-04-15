@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-white mb-2">
            <i class="fas fa-file-invoice text-emerald-400"></i> Bookings Management
        </h1>
        <p class="text-gray-400">Track and manage all event bookings</p>
    </div>

    <!-- Filters -->
    <div class="card-luxury p-6 mb-6">
            <form method="GET" action="{{ route('organiser.bookings.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-gray-300 font-semibold mb-2">Booking Status</label>
                <select name="status" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="">All Statuses</option>
                    <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            <div>
                <label class="block text-gray-300 font-semibold mb-2">Payment Status</label>
                <select name="payment_status" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="">All Payment Statuses</option>
                    <option value="completed" {{ request('payment_status') === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="pending" {{ request('payment_status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="failed" {{ request('payment_status') === 'failed' ? 'selected' : '' }}>Failed</option>
                    <option value="refunded" {{ request('payment_status') === 'refunded' ? 'selected' : '' }}>Refunded</option>
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button type="submit" class="px-6 py-2 bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 text-white font-semibold rounded-lg transition flex-1">
                    <i class="fas fa-filter mr-2"></i> Filter
                </button>
                <a href="{{ route('organiser.bookings.index') }}" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-white rounded-lg transition">
                    <i class="fas fa-redo"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Bookings Table -->
    <div class="card-luxury overflow-hidden mb-8">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-700/50 border-b-2 border-slate-600/50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-amber-400 uppercase">Reference</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-amber-400 uppercase">Event</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-amber-400 uppercase">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-amber-400 uppercase">Seats/Quantity</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-amber-400 uppercase">Total Price</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-amber-400 uppercase">Booking Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-amber-400 uppercase">Payment Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-amber-400 uppercase">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/30">
                    @forelse($bookings as $booking)
                        <tr class="hover:bg-slate-700/20 transition-colors">
                            <td class="px-6 py-3 font-mono text-sm text-amber-400 font-semibold">
                                {{ $booking->booking_reference }}
                            </td>
                            <td class="px-6 py-3 text-gray-300">
                                {{ $booking->event->title ?? $booking->event->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-3">
                                <div class="text-white">{{ $booking->user->name }}</div>
                                <div class="text-xs text-gray-400">{{ $booking->user->email }}</div>
                            </td>
                            <td class="px-6 py-3">
                                @if($booking->seats && $booking->seats->count() > 0)
                                    <div class="text-sm text-white">
                                        {{ $booking->seats->count() }} seat(s)
                                        <div class="text-gray-400 text-xs">
                                            {{ $booking->seats->pluck('seat_number')->implode(', ') }}
                                        </div>
                                    </div>
                                @else
                                    <div class="text-white">{{ $booking->quantity }} ticket(s)</div>
                                    @if($booking->ticket)
                                        <div class="text-xs text-gray-400">{{ $booking->ticket->ticket_type }}</div>
                                    @endif
                                @endif
                            </td>
                            <td class="px-6 py-3 text-emerald-400 font-semibold">
                                ₹{{ number_format($booking->total_price, 2) }}
                            </td>
                            <td class="px-6 py-3">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    {{ $booking->status === 'confirmed' ? 'bg-green-500/20 text-green-200 border border-green-500/30' : '' }}
                                    {{ $booking->status === 'pending' ? 'bg-yellow-500/20 text-yellow-200 border border-yellow-500/30' : '' }}
                                    {{ $booking->status === 'cancelled' ? 'bg-red-500/20 text-red-200 border border-red-500/30' : '' }}">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-3">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    {{ $booking->payment_status === 'completed' ? 'bg-green-500/20 text-green-200 border border-green-500/30' : '' }}
                                    {{ $booking->payment_status === 'pending' ? 'bg-yellow-500/20 text-yellow-200 border border-yellow-500/30' : '' }}
                                    {{ $booking->payment_status === 'failed' ? 'bg-red-500/20 text-red-200 border border-red-500/30' : '' }}
                                    {{ $booking->payment_status === 'refunded' ? 'bg-purple-500/20 text-purple-200 border border-purple-500/30' : '' }}">
                                    {{ ucfirst($booking->payment_status) }}
                                </span>
                            </td>
                            <td class="px-6 py-3 text-sm text-gray-400">
                                {{ $booking->created_at->format('M d, Y') }}
                                <div class="text-xs">{{ $booking->created_at->format('h:i A') }}</div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-8 text-center text-gray-400">
                                <i class="fas fa-inbox text-2xl mb-2 block opacity-50"></i>
                                No bookings found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($bookings->hasPages())
            <div class="px-6 py-4 border-t border-slate-700/30">
                {{ $bookings->links() }}
            </div>
        @endif
    </div>

    <!-- Summary Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-8">
        <div class="card-luxury p-6">
            <div class="text-gray-400 text-sm mb-2">Total Bookings</div>
            <div class="text-3xl font-bold text-white">{{ $bookings->total() }}</div>
        </div>
        <div class="card-luxury p-6">
            <div class="text-gray-400 text-sm mb-2">Total Revenue</div>
            <div class="text-3xl font-bold text-emerald-400">₹{{ number_format($bookings->sum('total_price'), 2) }}</div>
        </div>
        <div class="card-luxury p-6">
            <div class="text-gray-400 text-sm mb-2">Confirmed</div>
            <div class="text-3xl font-bold text-green-400">
                {{ $bookings->where('status', 'confirmed')->count() }}
            </div>
        </div>
        <div class="card-luxury p-6">
            <div class="text-gray-400 text-sm mb-2">Pending</div>
            <div class="text-3xl font-bold text-yellow-400">
                {{ $bookings->where('status', 'pending')->count() }}
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
        box-shadow: 0 25px 35px -5px rgba(217, 119, 6, 0.15);
    }
</style>
@endsection
