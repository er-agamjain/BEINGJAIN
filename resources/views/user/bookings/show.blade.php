@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="rounded-3xl border border-white/10 bg-white/5 text-white shadow-2xl p-6 md:p-8">
        <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
            <div>
                <h1 class="text-3xl font-bold">Booking Confirmed</h1>
                <p class="text-slate-300 text-sm">Reference: {{ $booking->booking_reference }}</p>
            </div>
            <span class="px-4 py-2 rounded-full bg-emerald-500/20 text-emerald-100 border border-emerald-400/30">
                @if($booking->status === 'confirmed')
                    <i class="fas fa-check-circle mr-2"></i>Confirmed
                @elseif($booking->status === 'pending')
                    <i class="fas fa-clock mr-2"></i>Pending Payment
                @else
                    <i class="fas fa-times-circle mr-2"></i>{{ ucfirst($booking->status) }}
                @endif
            </span>
        </div>
        <div class="grid md:grid-cols-3 gap-4">
            <div class="rounded-2xl bg-slate-900/60 border border-white/10 p-4">
                <p class="text-xs text-slate-300">Event</p>
                <p class="text-lg font-semibold">{{ $booking->event->title }}</p>
                <p class="text-slate-300 text-sm mt-1">{{ $booking->event->city?->name ?? $booking->event->location }}</p>
            </div>
            <div class="rounded-2xl bg-slate-900/60 border border-white/10 p-4">
                <p class="text-xs text-slate-300">Show Timing</p>
                @if($booking->showTiming)
                    <p class="text-lg font-semibold">{{ $booking->showTiming->show_date_time->format('M d, Y') }}</p>
                    <p class="text-slate-300 text-sm">{{ $booking->showTiming->show_date_time->format('h:i A') }}</p>
                @else
                    <p class="text-lg font-semibold">{{ $booking->event->event_date->format('M d, Y') }}</p>
                    <p class="text-slate-300 text-sm">{{ \Carbon\Carbon::parse($booking->event->event_time)->format('h:i A') }}</p>
                @endif
            </div>
            <div class="rounded-2xl bg-slate-900/60 border border-white/10 p-4">
                <p class="text-xs text-slate-300">Total Amount</p>
                <p class="text-2xl font-bold text-amber-300">₹{{ number_format($booking->total_price, 0) }}</p>
                <p class="text-slate-300 text-xs">{{ ucfirst($booking->payment_status) }}</p>
            </div>
        </div>
    </div>

    <div class="rounded-3xl border border-white/10 bg-white/5 text-white shadow-2xl p-6 md:p-8">
        <h3 class="text-2xl font-bold mb-4">Booking Details</h3>
        <div class="grid md:grid-cols-2 gap-4 text-sm text-slate-200">
            @if($booking->seats->isNotEmpty())
                <div class="rounded-xl bg-slate-900/60 border border-white/10 p-4 md:col-span-2">
                    <p class="text-xs text-slate-300 mb-3">Selected Seats</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($booking->seats->sortBy(function($seat) { return $seat->row_number; })->sortBy(function($seat) { return $seat->column_number; }) as $seat)
                            <span class="px-3 py-2 rounded-lg bg-emerald-500/20 border border-emerald-400/30 text-emerald-100 text-xs font-semibold">
                                {{ $seat->seatCategory->name }} - Row {{ chr(64 + $seat->row_number) }}, Seat {{ $seat->column_number }}
                                <br><span class="text-xs">₹{{ number_format($seat->current_price ?? $seat->seatCategory->base_price, 0) }}</span>
                            </span>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="rounded-xl bg-slate-900/60 border border-white/10 p-4">
                    <p class="text-xs text-slate-300">Quantity</p>
                    <p class="font-semibold">{{ $booking->quantity }} ticket(s)</p>
                </div>
                <div class="rounded-xl bg-slate-900/60 border border-white/10 p-4">
                    <p class="text-xs text-slate-300">Price per Ticket</p>
                    <p class="font-semibold">₹{{ number_format($booking->event->base_price, 0) }}</p>
                </div>
            @endif
            <div class="rounded-xl bg-slate-900/60 border border-white/10 p-4">
                <p class="text-xs text-slate-300">Booking Status</p>
                <p class="font-semibold">{{ ucfirst($booking->status) }}</p>
            </div>
            <div class="rounded-xl bg-slate-900/60 border border-white/10 p-4">
                <p class="text-xs text-slate-300">Booked On</p>
                <p class="font-semibold">{{ $booking->created_at->format('M d, Y h:i A') }}</p>
            </div>
        </div>
    </div>

    <div class="rounded-3xl border border-white/10 bg-white/5 text-white shadow-2xl p-6 md:p-8">
        <h3 class="text-2xl font-bold mb-4">Actions</h3>
        <div class="flex flex-wrap gap-3">
            @if($booking->status !== 'pending')
                <a href="{{ route('user.tickets.view', $booking) }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gradient-to-r from-amber-400 to-yellow-400 hover:from-amber-300 hover:to-yellow-300 text-slate-900 font-bold text-sm transition-all shadow-md">
                    <i class="fas fa-qrcode"></i> View Ticket
                </a>
                <a href="{{ route('user.tickets.download', $booking) }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-slate-700 border border-slate-500 text-gray-100 hover:bg-slate-600 font-semibold text-sm transition-all">
                    <i class="fas fa-file-download"></i> Download PDF
                </a>
                <a href="{{ route('user.events.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-slate-700 border border-slate-500 text-gray-100 hover:bg-slate-600 font-semibold text-sm transition-all">
                    <i class="fas fa-calendar-alt"></i> Browse More Events
                </a>
            @else
                <p class="text-sm text-slate-300"><i class="fas fa-clock text-amber-300 mr-2"></i>Your booking is awaiting admin approval. Ticket options will be available once confirmed.</p>
            @endif
        </div>
        <div class="mt-4 text-sm text-slate-200 space-y-1">
            <p><i class="fas fa-bell text-amber-300 mr-2"></i>You will receive reminders before showtime.</p>
            <p><i class="fas fa-undo text-emerald-300 mr-2"></i>Cancellation as per organiser rules; refunds may take 5-7 days.</p>
        </div>
    </div>
</div>
@endsection
