@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Pending Status Header -->
    <div class="rounded-3xl border border-amber-400/30 bg-gradient-to-br from-amber-500/20 to-orange-500/20 text-white shadow-2xl p-8 md:p-12 text-center">
        <div class="mb-6">
            <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-amber-500/30 border-4 border-amber-400/50 mb-4 animate-pulse">
                <i class="fas fa-clock text-5xl text-amber-300"></i>
            </div>
        </div>
        <h1 class="text-4xl font-bold mb-4">Payment Submitted!</h1>
        <p class="text-xl text-amber-100 mb-2">Awaiting Admin Verification</p>
        <p class="text-slate-300">Your payment has been received and is pending verification by our admin team.</p>
    </div>

    <!-- Payment Details -->
    <div class="rounded-3xl border border-white/10 bg-white/5 text-white shadow-2xl p-8">
        <h2 class="text-2xl font-bold mb-6 flex items-center">
            <i class="fas fa-receipt text-amber-400 mr-3"></i> Payment Details
        </h2>
        
        <div class="grid md:grid-cols-2 gap-6">
            <div class="bg-slate-900/60 border border-white/10 p-4 rounded-lg">
                <p class="text-slate-400 text-sm mb-1">Booking Reference</p>
                <p class="text-xl font-mono font-bold text-cyan-300">{{ $booking->booking_reference }}</p>
            </div>

            <div class="bg-slate-900/60 border border-white/10 p-4 rounded-lg">
                <p class="text-slate-400 text-sm mb-1">Payment Method</p>
                <p class="text-xl font-semibold capitalize">{{ ucfirst($payment->payment_method) }}</p>
            </div>

            <div class="bg-slate-900/60 border border-white/10 p-4 rounded-lg">
                <p class="text-slate-400 text-sm mb-1">Amount Paid</p>
                <p class="text-2xl font-bold text-amber-300">₹{{ number_format($payment->amount, 0) }}</p>
            </div>

            <div class="bg-slate-900/60 border border-white/10 p-4 rounded-lg">
                <p class="text-slate-400 text-sm mb-1">Payment Status</p>
                <div class="flex items-center">
                    <span class="px-3 py-1 bg-amber-500/20 text-amber-300 rounded-full text-sm font-semibold border border-amber-400/30">
                        <i class="fas fa-clock mr-1"></i> Pending Verification
                    </span>
                </div>
            </div>

            <div class="bg-slate-900/60 border border-white/10 p-4 rounded-lg">
                <p class="text-slate-400 text-sm mb-1">Submitted On</p>
                <p class="font-semibold">{{ $payment->payment_date->format('M d, Y · h:i A') }}</p>
            </div>

            <div class="bg-slate-900/60 border border-white/10 p-4 rounded-lg">
                <p class="text-slate-400 text-sm mb-1">Booking Status</p>
                <span class="px-3 py-1 bg-slate-700 text-slate-300 rounded-full text-sm font-semibold">
                    <i class="fas fa-hourglass-half mr-1"></i> {{ ucfirst($booking->status) }}
                </span>
            </div>
        </div>
    </div>

    <!-- Event Details -->
    <div class="rounded-3xl border border-white/10 bg-white/5 text-white shadow-2xl p-8">
        <h2 class="text-2xl font-bold mb-6 flex items-center">
            <i class="fas fa-ticket-alt text-amber-400 mr-3"></i> Booking Details
        </h2>
        
        <div class="space-y-4">
            <div>
                <p class="text-slate-400 text-sm mb-1">Event</p>
                <p class="text-2xl font-bold">{{ $booking->event->title }}</p>
            </div>

            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <p class="text-slate-400 text-sm mb-1">Date & Time</p>
                    @if($booking->showTiming)
                        <p class="font-semibold text-lg">{{ $booking->showTiming->show_date_time->format('M d, Y · h:i A') }}</p>
                    @else
                        <p class="font-semibold text-lg">{{ $booking->event->event_date->format('M d, Y') }} · {{ \Carbon\Carbon::parse($booking->event->event_time)->format('h:i A') }}</p>
                    @endif
                </div>

                <div>
                    <p class="text-slate-400 text-sm mb-1">Venue</p>
                    @if($booking->showTiming && $booking->showTiming->venue)
                        <p class="font-semibold text-lg">{{ $booking->showTiming->venue->name }}</p>
                    @else
                        <p class="font-semibold text-lg text-slate-400">Not specified</p>
                    @endif
                </div>
            </div>

            @if($booking->seats && $booking->seats->count() > 0)
            <div>
                <p class="text-slate-400 text-sm mb-2">Selected Seats</p>
                <div class="flex flex-wrap gap-2">
                    @foreach($booking->seats as $seat)
                    <div class="px-3 py-2 bg-amber-500/20 text-amber-300 rounded text-sm border border-amber-400/30 font-semibold">
                        Row {{ chr(64 + $seat->row_number) }}, Seat {{ $seat->column_number }}
                    </div>
                    @endforeach
                </div>
            </div>
            @else
            <div>
                <p class="text-slate-400 text-sm mb-1">Tickets</p>
                <p class="font-semibold text-lg">{{ $booking->quantity }} × General Admission</p>
            </div>
            @endif
        </div>
    </div>

    <!-- What Happens Next -->
    <div class="rounded-3xl border border-cyan-400/30 bg-cyan-500/10 text-white shadow-2xl p-8">
        <h2 class="text-2xl font-bold mb-6 flex items-center">
            <i class="fas fa-info-circle text-cyan-400 mr-3"></i> What Happens Next?
        </h2>
        
        <div class="space-y-4">
            <div class="flex items-start">
                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-cyan-500/30 border-2 border-cyan-400/50 flex items-center justify-center mr-4 mt-1">
                    <span class="text-sm font-bold">1</span>
                </div>
                <div>
                    <h3 class="font-bold text-lg mb-1">Admin Verification</h3>
                    <p class="text-slate-300">Our admin team will verify your payment details. This usually takes 15-30 minutes during business hours.</p>
                </div>
            </div>

            <div class="flex items-start">
                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-cyan-500/30 border-2 border-cyan-400/50 flex items-center justify-center mr-4 mt-1">
                    <span class="text-sm font-bold">2</span>
                </div>
                <div>
                    <h3 class="font-bold text-lg mb-1">Email Notification</h3>
                    <p class="text-slate-300">You'll receive an email once your payment is verified and your booking is confirmed.</p>
                </div>
            </div>

            <div class="flex items-start">
                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-cyan-500/30 border-2 border-cyan-400/50 flex items-center justify-center mr-4 mt-1">
                    <span class="text-sm font-bold">3</span>
                </div>
                <div>
                    <h3 class="font-bold text-lg mb-1">Get Your Tickets</h3>
                    <p class="text-slate-300">After verification, you can download your e-tickets from your bookings page.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Important Notes -->
    <div class="rounded-3xl border border-orange-400/30 bg-orange-500/10 text-white shadow-2xl p-6">
        <h3 class="font-bold text-lg mb-3 flex items-center">
            <i class="fas fa-exclamation-triangle text-orange-400 mr-2"></i> Important Notes
        </h3>
        <ul class="space-y-2 text-sm text-slate-300">
            <li class="flex items-start">
                <i class="fas fa-check text-emerald-400 mr-2 mt-1"></i>
                <span>Please keep your booking reference number for future communication</span>
            </li>
            <li class="flex items-start">
                <i class="fas fa-check text-emerald-400 mr-2 mt-1"></i>
                <span>Do not make duplicate payments for the same booking</span>
            </li>
            <li class="flex items-start">
                <i class="fas fa-check text-emerald-400 mr-2 mt-1"></i>
                <span>You cannot download tickets until payment is verified</span>
            </li>
            <li class="flex items-start">
                <i class="fas fa-check text-emerald-400 mr-2 mt-1"></i>
                <span>If payment is not verified within 24 hours, your seats will be released</span>
            </li>
        </ul>
    </div>

    <!-- Action Buttons -->
    <div class="flex flex-col sm:flex-row gap-4">
        <a href="{{ route('user.bookings.history') }}" class="flex-1 px-6 py-4 btn-primary-luxury bg-gradient-to-r from-amber-400 to-yellow-400 text-slate-900 hover:from-amber-300 hover:to-yellow-300 text-center">
            <i class="fas fa-list mr-2"></i> View My Bookings
        </a>
        <a href="{{ route('user.events.index') }}" class="flex-1 px-6 py-4 btn-secondary-luxury text-center">
            <i class="fas fa-home mr-2"></i> Browse Events
        </a>
    </div>

    <p class="text-center text-sm text-slate-400">
        Have questions? Contact us at <a href="mailto:support@eventbooking.com" class="text-cyan-400 hover:text-cyan-300">support@eventbooking.com</a>
    </p>
</div>
@endsection
