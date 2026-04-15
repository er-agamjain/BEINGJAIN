@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-white mb-2">Pending Payment Verification</h1>
        <p class="text-gray-400">Review and verify payments from your event bookings</p>
    </div>

    <!-- Status Messages -->
    @if ($message = Session::get('success'))
        <div class="mb-6 p-4 bg-emerald-500/20 border border-emerald-500/30 rounded-lg text-emerald-400">
            <i class="fas fa-check-circle mr-2"></i> {{ $message }}
        </div>
    @endif

    @if ($message = Session::get('error'))
        <div class="mb-6 p-4 bg-red-500/20 border border-red-500/30 rounded-lg text-red-400">
            <i class="fas fa-exclamation-circle mr-2"></i> {{ $message }}
        </div>
    @endif

    <!-- Navigation Tabs -->
    <div class="mb-8 flex gap-4 border-b border-slate-700">
        <a href="{{ route('organiser.payments.pending') }}" class="px-4 py-2 text-amber-400 border-b-2 border-amber-400 font-semibold">
            Pending <span class="ml-2 bg-amber-400/20 px-2 py-1 rounded text-xs">{{ $pendingPayments->total() }}</span>
        </a>
        <a href="{{ route('organiser.payments.history') }}" class="px-4 py-2 text-gray-400 hover:text-white transition">
            Payment History
        </a>
    </div>

    <!-- Payments Table -->
    @if ($pendingPayments->count() > 0)
        <div class="space-y-4">
            @foreach ($pendingPayments as $payment)
                <div class="card-luxury rounded-lg hover:shadow-xl hover:shadow-emerald-500/20 transition-all duration-200 transform hover:scale-[1.01]">
                    <div class="grid md:grid-cols-12 gap-6 p-6 items-start">
                        <!-- Payment Info -->
                        <div class="md:col-span-3">
                            <div class="mb-4">
                                <p class="text-gray-400 text-sm">Amount</p>
                                <p class="text-3xl font-bold text-emerald-400">₹{{ number_format($payment->amount, 2) }}</p>
                            </div>
                            <div>
                                <p class="text-gray-400 text-sm">Method</p>
                                <p class="text-white capitalize">{{ ucfirst($payment->payment_method) }}</p>
                            </div>
                        </div>

                        <!-- Booking & User Info -->
                        <div class="md:col-span-4">
                            <div class="mb-4">
                                <p class="text-gray-400 text-sm">Event</p>
                                <p class="text-white font-semibold">{{ $payment->booking->event->title }}</p>
                            </div>
                            <div class="mb-4">
                                <p class="text-gray-400 text-sm">User</p>
                                <p class="text-white">{{ $payment->booking->user->name }}</p>
                                <p class="text-gray-400 text-xs">{{ $payment->booking->user->email }}</p>
                            </div>
                            <div>
                                <p class="text-gray-400 text-sm">Booking Reference</p>
                                <p class="text-white font-mono text-xs">{{ $payment->booking->booking_reference }}</p>
                            </div>
                        </div>

                        <!-- Details -->
                        <div class="md:col-span-2">
                            <div class="mb-4">
                                <p class="text-gray-400 text-sm">Transaction ID</p>
                                <p class="text-white text-xs font-mono">{{ $payment->transaction_id ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-gray-400 text-sm">Submitted</p>
                                <p class="text-white text-sm">{{ $payment->payment_date->format('M d, Y') }}</p>
                                <p class="text-gray-500 text-xs">{{ $payment->payment_date->diffForHumans() }}</p>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="md:col-span-3 space-y-3">
                            <!-- Approve Button -->
                            <form action="{{ route('organiser.payments.approve', $payment) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full px-4 py-3 bg-gradient-to-r from-emerald-500 to-teal-500 hover:shadow-lg hover:shadow-emerald-500/20 text-white rounded-lg font-semibold transition flex items-center justify-center gap-2">
                                    <i class="fas fa-check"></i> Confirm Payment
                                </button>
                            </form>

                            <!-- Reject Button -->
                            <button type="button" class="w-full px-4 py-3 bg-red-500/20 hover:bg-red-500/30 text-red-400 rounded-lg font-semibold transition border border-red-500/30" data-toggle="modal" data-target="#rejectModal{{ $payment->id }}">
                                <i class="fas fa-times mr-2"></i> Reject
                            </button>

                            <!-- Mark as Not Received -->
                            <form action="{{ route('organiser.payments.not-received', $payment) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full px-4 py-3 bg-yellow-500/20 hover:bg-yellow-500/30 text-yellow-400 rounded-lg font-semibold transition border border-yellow-500/30">
                                    <i class="fas fa-exclamation-triangle mr-2"></i> Not Received
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Reject Modal -->
                <div id="rejectModal{{ $payment->id }}" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center" style="display: none;">
                    <div class="bg-slate-800 rounded-lg p-8 w-full max-w-md">
                        <h3 class="text-2xl font-bold text-white mb-4">Reject Payment</h3>
                        <form action="{{ route('organiser.payments.reject', $payment) }}" method="POST">
                            @csrf
                            <div class="mb-6">
                                <label class="block text-gray-400 text-sm mb-2">Reason (Optional)</label>
                                <textarea name="reason" rows="4" class="w-full bg-slate-700 text-white rounded-lg px-4 py-2 border border-slate-600 focus:border-amber-400 focus:outline-none" placeholder="Explain why you're rejecting this payment..."></textarea>
                            </div>
                            <div class="flex gap-4">
                                <button type="button" class="flex-1 px-4 py-2 bg-slate-700 text-white rounded-lg hover:bg-slate-600 transition" onclick="document.getElementById('rejectModal{{ $payment->id }}').style.display='none'">
                                    Cancel
                                </button>
                                <button type="submit" class="flex-1 px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition font-semibold">
                                    Confirm Rejection
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <script>
                    document.querySelectorAll('[data-toggle="modal"]').forEach(button => {
                        button.addEventListener('click', function() {
                            const target = this.getAttribute('data-target');
                            document.querySelector(target).style.display = 'flex';
                        });
                    });
                    
                    document.querySelectorAll('[style*="display: flex"]').forEach(modal => {
                        modal.addEventListener('click', function(e) {
                            if (e.target === this) {
                                this.style.display = 'none';
                            }
                        });
                    });
                </script>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $pendingPayments->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <i class="fas fa-check-circle text-emerald-400 text-6xl mb-4"></i>
            <h3 class="text-2xl font-bold text-white mb-2">All Payments Verified</h3>
            <p class="text-gray-400">There are no pending payments waiting for verification.</p>
        </div>
    @endif
</div>

<style>
    .card-luxury {
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.8) 0%, rgba(30, 41, 59, 0.8) 100%);
        border: 1px solid rgba(148, 113, 113, 0.2);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3);
    }
</style>
@endsection
