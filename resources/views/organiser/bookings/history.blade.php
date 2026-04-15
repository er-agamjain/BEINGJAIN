@extends('layouts.app')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4">
        <div class="mb-8">
            <h1 class="text-3xl font-bold mb-2">Booking History</h1>
            <p class="text-gray-600">Complete history of all bookings for your events</p>
        </div>

        <!-- Bookings Table -->
        <div class="overflow-x-auto bg-white rounded-lg shadow">
            <table class="w-full">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left">Reference</th>
                        <th class="px-6 py-3 text-left">Event</th>
                        <th class="px-6 py-3 text-left">Customer</th>
                        <th class="px-6 py-3 text-left">Seats/Quantity</th>
                        <th class="px-6 py-3 text-left">Total Price</th>
                        <th class="px-6 py-3 text-left">Status</th>
                        <th class="px-6 py-3 text-left">Payment</th>
                        <th class="px-6 py-3 text-left">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-6 py-3 font-mono text-sm">
                                {{ $booking->booking_reference }}
                            </td>
                            <td class="px-6 py-3">
                                {{ $booking->event->title ?? $booking->event->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-3">
                                <div>{{ $booking->user->name }}</div>
                                <div class="text-sm text-gray-500">{{ $booking->user->email }}</div>
                            </td>
                            <td class="px-6 py-3">
                                @if($booking->seats && $booking->seats->count() > 0)
                                    <div class="text-sm">
                                        {{ $booking->seats->count() }} seat(s)
                                        <div class="text-gray-500">
                                            {{ $booking->seats->pluck('seat_number')->implode(', ') }}
                                        </div>
                                    </div>
                                @else
                                    {{ $booking->quantity }} ticket(s)
                                @endif
                            </td>
                            <td class="px-6 py-3 font-semibold">
                                ₹{{ number_format($booking->total_price, 2) }}
                            </td>
                            <td class="px-6 py-3">
                                <span class="px-3 py-1 rounded-full text-sm font-semibold
                                    {{ $booking->status === 'confirmed' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $booking->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-3">
                                <span class="px-3 py-1 rounded-full text-sm font-semibold
                                    {{ $booking->payment_status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $booking->payment_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $booking->payment_status === 'failed' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $booking->payment_status === 'refunded' ? 'bg-purple-100 text-purple-800' : '' }}">
                                    {{ ucfirst($booking->payment_status) }}
                                </span>
                            </td>
                            <td class="px-6 py-3 text-sm text-gray-600">
                                {{ $booking->created_at->format('M d, Y') }}
                                <div class="text-xs">{{ $booking->created_at->format('h:i A') }}</div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-8 text-center text-gray-600">
                                No booking history available.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($bookings->hasPages())
            <div class="mt-8">
                {{ $bookings->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
