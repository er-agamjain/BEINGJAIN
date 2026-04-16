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
@endphp
<div class="space-y-6">
    <!-- Success Header -->
    <div class="rounded-3xl border border-emerald-400/30 bg-emerald-500/10 text-emerald-100 shadow-2xl p-8 text-center">
        <div class="flex justify-center mb-4">
            <div class="w-20 h-20 rounded-full bg-emerald-500/20 border-2 border-emerald-400 flex items-center justify-center">
                <i class="fas fa-check text-4xl text-emerald-400"></i>
            </div>
        </div>
        <h1 class="text-3xl font-bold mb-2">Payment Successful!</h1>
        <p class="text-emerald-200 text-lg">Your booking is confirmed. Scan the QR code below to access your tickets.</p>
    </div>

    <!-- QR Code & Payment Details -->
    <div class="grid md:grid-cols-2 gap-6">
        <!-- QR Code Section -->
        <div class="rounded-3xl border border-white/10 bg-white/5 text-white shadow-2xl p-8">
            <h2 class="text-2xl font-bold mb-6 text-center">Your E-Ticket</h2>
            
            <div class="bg-white rounded-xl p-6 flex flex-col items-center justify-center mb-6">
                <div id="qrCodeContainer" class="flex items-center justify-center">
                    <!-- QR Code will be generated here -->
                    <div class="w-64 h-64 bg-slate-200 rounded-lg flex items-center justify-center">
                        <div class="text-center">
                            <i class="fas fa-qrcode text-6xl text-slate-400 mb-2"></i>
                            <p class="text-slate-600 font-medium">Ref: {{ $booking->booking_reference }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-3 text-sm">
                <div class="flex justify-between bg-slate-900/30 p-3 rounded-lg">
                    <span class="text-slate-300">Booking Reference</span>
                    <span class="font-mono font-bold text-amber-300">{{ $booking->booking_reference }}</span>
                </div>
                <div class="flex justify-between bg-slate-900/30 p-3 rounded-lg">
                    <span class="text-slate-300">Amount Paid</span>
                    <span class="font-bold text-amber-300">₹{{ number_format($booking->total_price, 0) }}</span>
                </div>
                <div class="flex justify-between bg-slate-900/30 p-3 rounded-lg">
                    <span class="text-slate-300">Payment Method</span>
                    <span class="capitalize font-semibold">{{ ucfirst($payment->payment_method) }}</span>
                </div>
                @if($payment->transaction_id)
                <div class="flex justify-between bg-slate-900/30 p-3 rounded-lg">
                    <span class="text-slate-300">Transaction ID</span>
                    <span class="font-mono text-xs text-cyan-300">{{ substr($payment->transaction_id, 0, 20) }}...</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Payment Details Section -->
        <div class="space-y-6">
            <!-- Event Details -->
            <div class="rounded-3xl border border-white/10 bg-white/5 text-white shadow-2xl p-8">
                <h3 class="text-xl font-bold mb-4 flex items-center">
                    <i class="fas fa-calendar-alt text-amber-400 mr-3"></i> Event Details
                </h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-slate-400 text-sm">Event Name</p>
                        <p class="text-lg font-semibold">{{ $booking->event->name }}</p>
                    </div>
                    <div>
                        <p class="text-slate-400 text-sm">Date & Time</p>
                        @if($booking->showTiming)
                            <p class="text-lg font-semibold">{{ $booking->showTiming->show_date_time->format('M d, Y · h:i A') }}</p>
                        @else
                            <p class="text-lg font-semibold">{{ $booking->event->event_date->format('M d, Y') }} · {{ \Carbon\Carbon::parse($booking->event->event_time)->format('h:i A') }}</p>
                        @endif
                    </div>
                    <div>
                        <p class="text-slate-400 text-sm">Location</p>
                        <p class="text-lg font-semibold">{{ $booking->event->location }}</p>
                    </div>
                </div>
            </div>

            <!-- Seats/Tickets Info -->
            <div class="rounded-3xl border border-white/10 bg-white/5 text-white shadow-2xl p-8">
                <h3 class="text-xl font-bold mb-4 flex items-center">
                    <i class="fas fa-chair text-cyan-400 mr-3"></i> Your Booking
                </h3>
                <div class="space-y-3">
                    @if($booking->seats && $booking->seats->count() > 0)
                        <div>
                            <p class="text-slate-400 text-sm mb-2">Seats</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach($booking->seats as $seat)
                                    <div class="px-4 py-2 bg-amber-500/20 text-amber-300 rounded-lg text-sm font-semibold border border-amber-400/30">
                                        <i class="fas fa-chair mr-1"></i>Row {{ $rowLabel($seat->row_number) }}, Seat {{ $seat->column_number }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div>
                            <p class="text-slate-400 text-sm">Tickets</p>
                            <p class="text-lg font-semibold">{{ $booking->quantity }} × {{ $booking->ticket->name ?? 'General Admission' }}</p>
                        </div>
                    @endif
                    <div class="pt-3 border-t border-white/10">
                        <p class="text-slate-400 text-sm">Total Amount</p>
                        <p class="text-2xl font-bold text-amber-300">₹{{ number_format($booking->total_price, 0) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Methods Display (Optional) -->
    @if($payment->payment_method === 'upi')
    <div class="rounded-3xl border border-white/10 bg-white/5 text-white shadow-2xl p-8">
        <h2 class="text-2xl font-bold mb-6">UPI Payment Details</h2>
        <div class="grid md:grid-cols-3 gap-6">
            <div class="rounded-2xl bg-slate-900/60 border border-white/10 p-6 text-center">
                <p class="text-slate-400 text-sm mb-2">UPI ID</p>
                <p class="text-xl font-mono font-bold text-cyan-300 break-all">eventbooking@upi</p>
                <button onclick="copyUPI()" class="mt-3 px-4 py-2 bg-cyan-600 hover:bg-cyan-700 rounded-lg text-sm transition-colors">
                    <i class="fas fa-copy mr-2"></i>Copy UPI
                </button>
            </div>
            <div class="rounded-2xl bg-slate-900/60 border border-white/10 p-6 text-center">
                <p class="text-slate-400 text-sm mb-2">Amount</p>
                <p class="text-2xl font-bold text-amber-300">₹{{ number_format($booking->total_price, 0) }}</p>
                <p class="text-xs text-slate-400 mt-2">Pay exactly this amount</p>
            </div>
            <div class="rounded-2xl bg-slate-900/60 border border-white/10 p-6 text-center">
                <p class="text-slate-400 text-sm mb-2">Reference</p>
                <p class="text-lg font-mono font-bold text-cyan-300 break-all">{{ $booking->booking_reference }}</p>
                <p class="text-xs text-slate-400 mt-2">Mention in payment notes</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Action Buttons -->
    <div class="rounded-3xl border border-white/10 bg-white/5 text-white shadow-2xl p-8 space-y-4">
        <div class="grid md:grid-cols-2 gap-4">
            <a href="{{ route('user.tickets.view', $booking) }}" class="btn-primary-luxury text-center">
                <i class="fas fa-ticket-alt mr-2"></i> View Ticket
            </a>
            <a href="{{ route('user.tickets.download', $booking) }}" class="btn-secondary-luxury text-center">
                <i class="fas fa-download mr-2"></i> Download PDF
            </a>
        </div>
        <div class="text-center">
            <p class="text-slate-400 text-sm mb-3">Check your email for confirmation and reminder</p>
            <a href="{{ route('user.events.index') }}" class="inline-block px-4 py-2 rounded-xl bg-slate-700/70 border border-slate-500 text-gray-100 font-semibold shadow-[0_4px_0_rgba(15,23,42,0.7),0_10px_18px_rgba(2,6,23,0.35)] hover:bg-slate-600 hover:-translate-y-px active:translate-y-[2px] active:shadow-[0_2px_0_rgba(15,23,42,0.75),0_6px_10px_rgba(2,6,23,0.25)] transition-all duration-200">
                ← Browse more events
            </a>
        </div>
    </div>
</div>

<script>
function copyUPI() {
    const upiId = 'eventbooking@upi';
    navigator.clipboard.writeText(upiId).then(() => {
        alert('UPI ID copied to clipboard: ' + upiId);
    }).catch(err => {
        console.error('Failed to copy:', err);
    });
}

// Simple QR Code generation (you can replace with actual library)
document.addEventListener('DOMContentLoaded', function() {
    // This is a placeholder. In production, use:
    // - QRCode.js library
    // - php-qrcode
    // - or API service like api.qrserver.com
    
    const bookingRef = '{{ $booking->booking_reference }}';
    const qrData = `BOOKING:${bookingRef}|AMOUNT:{{ $booking->total_price }}|EVENT:{{ $booking->event->id }}`;
    
    // Example using QR Server API
    const qrUrl = `https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=${encodeURIComponent(qrData)}`;
    document.getElementById('qrCodeContainer').innerHTML = `
        <img src="${qrUrl}" alt="QR Code" class="w-64 h-64 rounded-lg">
    `;
});
</script>
@endsection
