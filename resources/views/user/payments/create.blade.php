@extends('layouts.app')

@section('content')
@php
    $rowLabel = function ($rowNumber) {
        $label = '';
        $number = (int) $rowNumber;

        while ($number > 0) {
            $remainder = ($number - 1) % 26;
            $label = chr(65 + $remainder) . $label;
            $number = intdiv($number - 1, 26);
        }

        return $label ?: 'A';
    };

    $displayCity = $booking->event->city->name
        ?? $booking->showTiming->venue->city->name
        ?? 'N/A';
@endphp
<div class="space-y-6">
    <!-- Booking Summary -->
    <div class="rounded-3xl border border-white/10 bg-white/5 text-white shadow-2xl p-6 md:p-8">
        <h1 class="text-3xl font-bold mb-4">Complete Payment</h1>
        <div class="grid md:grid-cols-2 gap-4">
            <div class="rounded-2xl bg-slate-900/60 border border-white/10 p-4">
                <p class="text-sm text-slate-300">Event</p>
                <p class="text-xl font-semibold">{{ $booking->event->title }}</p>
                <p class="text-slate-300 text-sm mt-2">Location: {{ $displayCity }}</p>
                <p class="text-slate-300 text-sm">Quantity: {{ $booking->quantity }}</p>
            </div>
            <div class="rounded-2xl bg-slate-900/60 border border-white/10 p-4 flex flex-col justify-between">
                <div>
                    <p class="text-sm text-slate-300">Total Amount</p>
                    <p class="text-3xl font-bold text-amber-300">₹{{ number_format($booking->total_price, 0) }}</p>
                </div>
                <p class="text-xs text-slate-400">Secure payments · Instant confirmation · QR e-ticket</p>
            </div>
        </div>
    </div>

    <!-- Seats Information (if applicable) -->
    @if($booking->seats && $booking->seats->count() > 0)
    <div class="rounded-3xl border border-cyan-400/30 bg-cyan-500/10 text-white shadow-2xl p-6 md:p-8">
        <h2 class="text-2xl font-bold mb-4 flex items-center">
            <i class="fas fa-chair text-cyan-400 mr-3"></i> Your Selected Seats
        </h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            @foreach($booking->seats as $seat)
            <div class="rounded-xl bg-slate-900/60 border border-cyan-400/30 p-4 text-center">
                <div class="text-2xl font-bold text-cyan-300 mb-1">Row {{ $rowLabel($seat->row_number) }}</div>
                <div class="text-sm text-slate-300">Seat {{ $seat->column_number }}</div>
                <div class="text-xs text-cyan-400 mt-1">{{ $seat->seatCategory->name ?? 'Standard' }}</div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Payment Method Selection -->
    <div class="rounded-3xl border border-white/10 bg-white/5 text-white shadow-2xl p-6 md:p-8">
        <h2 class="text-2xl font-bold mb-4">Select Payment Method</h2>
        <form action="{{ route('user.payments.store', $booking) }}" method="POST" class="space-y-3">
            @csrf

            @php
                $paymentMethods = \App\Helpers\PaymentHelper::getActivePaymentMethods();
            @endphp

            @forelse($paymentMethods as $key => $method)
            <label class="flex items-center p-4 rounded-2xl bg-white/5 border border-white/10 cursor-pointer hover:border-{{ $method['color'] }}-400/60 transition">
                <input type="radio" name="payment_method" value="{{ $key }}" class="mr-4" required>
                <div>
                    <p class="font-semibold flex items-center">
                        <i class="{{ $method['icon'] }} text-{{ $method['color'] }}-400 mr-2"></i> {{ $method['name'] }}
                    </p>
                    <p class="text-slate-300 text-sm">{{ $method['description'] }}</p>
                </div>
            </label>
            @empty
            <div class="p-4 rounded-2xl bg-red-500/10 border border-red-500/30">
                <p class="text-red-400 flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i> No payment methods are currently available. Please contact support.
                </p>
            </div>
            @endforelse

            @if(!empty($paymentMethods))
            <button type="submit" class="w-full px-6 py-3 btn-primary-luxury bg-gradient-to-r from-amber-400 to-yellow-400 text-slate-900 hover:from-amber-300 hover:to-yellow-300 text-center">
                <i class="fas fa-credit-card mr-2"></i> Proceed to pay
            </button>
            @endif

            <a href="{{ route('user.bookings.show', $booking) }}" class="block text-center px-4 py-2 rounded-xl bg-slate-800/80 border border-white/10 text-slate-200 font-semibold shadow-[0_4px_0_rgba(15,23,42,0.7),0_10px_18px_rgba(2,6,23,0.35)] hover:bg-slate-700 hover:text-white hover:-translate-y-px active:translate-y-[2px] active:shadow-[0_2px_0_rgba(15,23,42,0.75),0_6px_10px_rgba(2,6,23,0.25)] transition">
                ← Cancel and go back
            </a>
        </form>
    </div>

</div>
@endsection
