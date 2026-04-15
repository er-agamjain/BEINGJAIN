@extends('layouts.app')

@section('title', 'Booking Details')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold text-white mb-2">
                    <i class="fas fa-ticket-alt text-amber-400"></i> Booking Details
                </h1>
                <p class="text-gray-400">Reference: <span class="text-cyan-300 font-mono">{{ $booking->booking_reference }}</span></p>
            </div>
            <a href="{{ route('admin.bookings.index') }}" class="px-4 py-3 bg-slate-700 hover:bg-slate-600 text-white rounded-lg font-semibold transition">
                <i class="fas fa-arrow-left mr-2"></i> Back to Bookings
            </a>
        </div>
    </div>

    <!-- Booking Information -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- User Information -->
        <div class="card-luxury p-6">
            <h2 class="text-2xl font-bold text-white mb-4 flex items-center">
                <i class="fas fa-user text-amber-400 mr-3"></i> User Information
            </h2>
            <div class="space-y-3">
                <div class="flex items-center">
                    <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-amber-400 to-yellow-400 flex items-center justify-center text-slate-900 font-bold text-2xl mr-4">
                        {{ strtoupper(substr($booking->user->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-white font-semibold text-lg">{{ $booking->user->name }}</p>
                        <p class="text-gray-400 text-sm">{{ $booking->user->email }}</p>
                    </div>
                </div>
                <div class="pt-3 border-t border-slate-700/50">
                    <p class="text-gray-400 text-sm">Phone</p>
                    <p class="text-white">{{ $booking->user->phone ?? 'Not provided' }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-sm">User Since</p>
                    <p class="text-white">{{ $booking->user->created_at->format('M d, Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Event Information -->
        <div class="card-luxury p-6">
            <h2 class="text-2xl font-bold text-white mb-4 flex items-center">
                <i class="fas fa-calendar-alt text-purple-400 mr-3"></i> Event Information
            </h2>
            <div class="space-y-3">
                <div>
                    <p class="text-gray-400 text-sm">Event Name</p>
                    <p class="text-white font-semibold text-lg">{{ $booking->event->title ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-sm">Organiser</p>
                    <p class="text-white">{{ $booking->event->organiser->name }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-sm">Date & Time</p>
                    <p class="text-white">{{ $booking->event->event_date->format('M d, Y') }} at {{ \Carbon\Carbon::parse($booking->event->event_time)->format('h:i A') }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-sm">Location</p>
                    <p class="text-white">{{ $booking->event->location }}</p>
                </div>
            </div>
        </div>

        <!-- Booking Information -->
        <div class="card-luxury p-6">
            <h2 class="text-2xl font-bold text-white mb-4 flex items-center">
                <i class="fas fa-receipt text-emerald-400 mr-3"></i> Booking Information
            </h2>
            <div class="space-y-3">
                <div>
                    <p class="text-gray-400 text-sm">Booking Reference</p>
                    <p class="text-white font-mono font-semibold">{{ $booking->booking_reference }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-sm">Ticket Type</p>
                    <p class="text-white">{{ $booking->ticket?->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-sm">Quantity</p>
                    <p class="text-white text-2xl font-bold">{{ $booking->quantity }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-sm">Total Price</p>
                    <p class="text-emerald-400 text-3xl font-bold">₹{{ number_format($booking->total_price, 2) }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-sm">Booking Date</p>
                    <p class="text-white">{{ $booking->created_at->format('M d, Y h:i A') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Information -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="card-luxury p-6">
            <h2 class="text-xl font-bold text-white mb-4">Booking Status</h2>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-gray-400">Status</span>
                    @switch($booking->status)
                        @case('confirmed')
                            <span class="px-4 py-2 bg-emerald-500/20 text-emerald-200 rounded-lg text-sm font-semibold border border-emerald-500/30">
                                <i class="fas fa-check"></i> Confirmed
                            </span>
                            @break
                        @case('cancelled')
                            <span class="px-4 py-2 bg-red-500/20 text-red-200 rounded-lg text-sm font-semibold border border-red-500/30">
                                <i class="fas fa-ban"></i> Cancelled
                            </span>
                            @break
                        @case('completed')
                            <span class="px-4 py-2 bg-blue-500/20 text-blue-200 rounded-lg text-sm font-semibold border border-blue-500/30">
                                <i class="fas fa-check-circle"></i> Completed
                            </span>
                            @break
                        @default
                            <span class="px-4 py-2 bg-slate-700/50 text-gray-300 rounded-lg text-sm font-semibold border border-slate-600/30">
                                {{ ucfirst($booking->status) }}
                            </span>
                    @endswitch
                </div>
            </div>
        </div>

        <div class="card-luxury p-6">
            <h2 class="text-xl font-bold text-white mb-4">Payment Status</h2>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-gray-400">Payment</span>
                    @switch($booking->payment_status)
                        @case('paid')
                            <span class="px-4 py-2 bg-emerald-500/20 text-emerald-200 rounded-lg text-sm font-semibold border border-emerald-500/30">
                                <i class="fas fa-credit-card"></i> Paid
                            </span>
                            @break
                        @case('pending')
                            <span class="px-4 py-2 bg-yellow-500/20 text-yellow-200 rounded-lg text-sm font-semibold border border-yellow-500/30">
                                <i class="fas fa-clock"></i> Pending
                            </span>
                            @break
                        @case('failed')
                            <span class="px-4 py-2 bg-red-500/20 text-red-200 rounded-lg text-sm font-semibold border border-red-500/30">
                                <i class="fas fa-exclamation"></i> Failed
                            </span>
                            @break
                        @default
                            <span class="px-4 py-2 bg-slate-700/50 text-gray-300 rounded-lg text-sm font-semibold border border-slate-600/30">
                                {{ ucfirst($booking->payment_status) }}
                            </span>
                    @endswitch
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Details if exists -->
    @if($booking->payment)
        <div class="card-luxury mb-8 p-6">
            <h2 class="text-2xl font-bold text-white mb-4 flex items-center">
                <i class="fas fa-money-bill-wave text-emerald-400 mr-3"></i> Payment Details
            </h2>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-700/50 border-b-2 border-slate-600/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-amber-400 uppercase">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-amber-400 uppercase">Method</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-amber-400 uppercase">Transaction ID</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-amber-400 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-amber-400 uppercase">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700/30">
                        <tr class="hover:bg-slate-700/20 transition-colors">
                            <td class="px-6 py-4 text-emerald-400 font-bold">₹{{ number_format($booking->payment->amount, 2) }}</td>
                            <td class="px-6 py-4 text-white">{{ ucfirst($booking->payment->payment_method) }}</td>
                            <td class="px-6 py-4 text-gray-400 font-mono text-sm">{{ $booking->payment->transaction_id ?? 'N/A' }}</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 bg-emerald-500/20 text-emerald-200 rounded-lg text-xs font-semibold border border-emerald-500/30">
                                    {{ ucfirst($booking->payment->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-400">{{ $booking->payment->payment_date->format('M d, Y h:i A') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- Activity Timeline -->
    <div class="card-luxury p-6">
        <h2 class="text-2xl font-bold text-white mb-4 flex items-center">
            <i class="fas fa-history text-blue-400 mr-3"></i> Activity Timeline
        </h2>
        <div class="space-y-4">
            <div class="flex items-start">
                <div class="w-10 h-10 rounded-full bg-blue-500/20 flex items-center justify-center text-blue-400 mr-4 flex-shrink-0">
                    <i class="fas fa-plus"></i>
                </div>
                <div>
                    <p class="text-white font-semibold">Booking Created</p>
                    <p class="text-gray-400 text-sm">{{ $booking->created_at->format('M d, Y h:i A') }}</p>
                </div>
            </div>
            @if($booking->updated_at != $booking->created_at)
                <div class="flex items-start">
                    <div class="w-10 h-10 rounded-full bg-purple-500/20 flex items-center justify-center text-purple-400 mr-4 flex-shrink-0">
                        <i class="fas fa-edit"></i>
                    </div>
                    <div>
                        <p class="text-white font-semibold">Last Updated</p>
                        <p class="text-gray-400 text-sm">{{ $booking->updated_at->format('M d, Y h:i A') }}</p>
                    </div>
                </div>
            @endif
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
