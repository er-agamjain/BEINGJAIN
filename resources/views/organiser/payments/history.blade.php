@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-white mb-2">Payment History</h1>
        <p class="text-gray-400">Complete payment history from all your event bookings</p>
    </div>

    <!-- Status Messages -->
    @if ($message = Session::get('success'))
        <div class="mb-6 p-4 bg-emerald-500/20 border border-emerald-500/30 rounded-lg text-emerald-400">
            <i class="fas fa-check-circle mr-2"></i> {{ $message }}
        </div>
    @endif

    <!-- Navigation Tabs -->
    <div class="mb-8 flex gap-4 border-b border-slate-700">
        <a href="{{ route('organiser.payments.pending') }}" class="px-4 py-2 text-gray-400 hover:text-white transition">
            Pending Verification
        </a>
        <a href="{{ route('organiser.payments.history') }}" class="px-4 py-2 text-amber-400 border-b-2 border-amber-400 font-semibold">
            Payment History
        </a>
    </div>

    <!-- Filters -->
    <div class="mb-6 flex flex-wrap gap-4">
        <form method="GET" action="{{ route('organiser.payments.history') }}" class="flex gap-2">
            <select name="status" class="bg-slate-700 text-white px-4 py-2 rounded-lg border border-slate-600 hover:border-amber-400 transition">
                <option value="">All Statuses</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="success" {{ request('status') === 'success' ? 'selected' : '' }}>Confirmed</option>
                <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Rejected</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-lg font-semibold transition">
                Filter
            </button>
        </form>
    </div>

    <!-- Payments Table -->
    @if ($payments->count() > 0)
        <div class="overflow-x-auto rounded-lg">
            <div class="card-luxury">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-600">
                            <th class="px-6 py-4 text-left text-xs font-semibold text-amber-400 uppercase">Amount</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-amber-400 uppercase">Event</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-amber-400 uppercase">User</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-amber-400 uppercase">Method</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-amber-400 uppercase">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-amber-400 uppercase">Date</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-amber-400 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700/30">
                        @foreach ($payments as $payment)
                            <tr class="hover:bg-slate-700/40 hover:shadow-lg hover:shadow-emerald-500/10 transition-all duration-200 rounded-lg">
                                <td class="px-6 py-4 text-emerald-400 font-bold">₹{{ number_format($payment->amount, 2) }}</td>
                                <td class="px-6 py-4 text-white">
                                    <div class="font-semibold">{{ $payment->booking->event->title }}</div>
                                    <div class="text-gray-400 text-xs">{{ $payment->booking->booking_reference }}</div>
                                </td>
                                <td class="px-6 py-4 text-white">
                                    <div>{{ $payment->booking->user->name }}</div>
                                    <div class="text-gray-400 text-xs">{{ $payment->booking->user->email }}</div>
                                </td>
                                <td class="px-6 py-4 text-white capitalize">
                                    {{ ucfirst($payment->payment_method) }}
                                </td>
                                <td class="px-6 py-4">
                                    @if ($payment->status === 'success')
                                        <span class="inline-flex items-center px-3 py-1 bg-emerald-500/20 text-emerald-200 rounded-full text-xs font-semibold border border-emerald-500/30">
                                            <i class="fas fa-check-circle mr-1"></i> Confirmed
                                        </span>
                                    @elseif ($payment->status === 'failed')
                                        <span class="inline-flex items-center px-3 py-1 bg-red-500/20 text-red-200 rounded-full text-xs font-semibold border border-red-500/30">
                                            <i class="fas fa-times-circle mr-1"></i> Rejected
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 bg-amber-500/20 text-amber-200 rounded-full text-xs font-semibold border border-amber-500/30">
                                            <i class="fas fa-clock mr-1"></i> Pending
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-gray-400 text-sm">
                                    {{ $payment->payment_date->format('M d, Y') }}
                                    <div class="text-xs text-gray-500">{{ $payment->payment_date->format('h:i A') }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($payment->status === 'pending')
                                        <button type="button" class="px-3 py-1 bg-amber-500/20 hover:bg-amber-500/30 text-amber-400 text-xs rounded transition border border-amber-500/30 font-semibold" data-toggle="modal" data-target="#detailsModal{{ $payment->id }}">
                                            Review
                                        </button>
                                    @else
                                        <span class="text-gray-500 text-xs">Processed</span>
                                    @endif
                                </td>
                            </tr>

                        <!-- Details Modal -->
                        <div id="detailsModal{{ $payment->id }}" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center" style="display: none;">
                            <div class="bg-slate-800 rounded-lg p-8 w-full max-w-md max-h-[90vh] overflow-y-auto">
                                <h3 class="text-2xl font-bold text-white mb-6">Payment Details</h3>
                                
                                <div class="space-y-4 mb-8">
                                    <div>
                                        <p class="text-gray-400 text-sm">Amount</p>
                                        <p class="text-white text-2xl font-bold text-emerald-400">₹{{ number_format($payment->amount, 2) }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-400 text-sm">Payment Method</p>
                                        <p class="text-white capitalize">{{ ucfirst($payment->payment_method) }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-400 text-sm">Transaction ID</p>
                                        <p class="text-white font-mono text-sm">{{ $payment->transaction_id ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-400 text-sm">Status</p>
                                        <p class="text-white">
                                            @if ($payment->status === 'success')
                                                <span class="text-emerald-400 font-semibold">Confirmed</span>
                                            @elseif ($payment->status === 'failed')
                                                <span class="text-red-400 font-semibold">Rejected</span>
                                            @else
                                                <span class="text-amber-400 font-semibold">Pending</span>
                                            @endif
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-gray-400 text-sm">Event</p>
                                        <p class="text-white">{{ $payment->booking->event->title }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-400 text-sm">User</p>
                                        <p class="text-white">{{ $payment->booking->user->name }}</p>
                                        <p class="text-gray-400 text-xs">{{ $payment->booking->user->email }}</p>
                                    </div>
                                    @if ($payment->notes)
                                        <div>
                                            <p class="text-gray-400 text-sm">Notes</p>
                                            <p class="text-white text-sm">{{ $payment->notes }}</p>
                                        </div>
                                    @endif
                                </div>

                                @if ($payment->status === 'pending')
                                    <div class="space-y-3">
                                        <form action="{{ route('organiser.payments.approve', $payment) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="w-full px-4 py-3 bg-gradient-to-r from-emerald-500 to-teal-500 hover:shadow-lg hover:shadow-emerald-500/20 text-white rounded-lg font-semibold transition">
                                                <i class="fas fa-check mr-2"></i> Confirm Payment
                                            </button>
                                        </form>
                                        <button type="button" class="w-full px-4 py-3 bg-red-500/20 hover:bg-red-500/30 text-red-400 rounded-lg font-semibold transition border border-red-500/30" onclick="document.getElementById('rejectForm{{ $payment->id }}').style.display='block'">
                                            <i class="fas fa-times mr-2"></i> Reject
                                        </button>
                                    </div>

                                    <div id="rejectForm{{ $payment->id }}" style="display: none;" class="mt-4 pt-4 border-t border-slate-700">
                                        <form action="{{ route('organiser.payments.reject', $payment) }}" method="POST">
                                            @csrf
                                            <label class="block text-gray-400 text-sm mb-2">Reason</label>
                                            <textarea name="reason" rows="3" class="w-full bg-slate-700 text-white rounded px-3 py-2 mb-3 border border-slate-600 focus:border-red-400 focus:outline-none"></textarea>
                                            <button type="submit" class="w-full px-4 py-2 bg-red-500 text-white rounded font-semibold hover:bg-red-600 transition">
                                                Confirm Rejection
                                            </button>
                                        </form>
                                    </div>
                                @endif

                                <button type="button" class="w-full mt-4 px-4 py-2 bg-slate-700 text-white rounded hover:bg-slate-600 transition" onclick="document.getElementById('detailsModal{{ $payment->id }}').style.display='none'">
                                    Close
                                </button>
                            </div>
                        </div>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $payments->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <i class="fas fa-inbox text-gray-600 text-6xl mb-4"></i>
            <h3 class="text-2xl font-bold text-white mb-2">No Payments Yet</h3>
            <p class="text-gray-400">You haven't received any payments for your events yet.</p>
        </div>
    @endif
</div>

<script>
    document.querySelectorAll('[data-toggle="modal"]').forEach(button => {
        button.addEventListener('click', function() {
            const target = this.getAttribute('data-target');
            const modal = document.querySelector(target);
            if (modal) {
                modal.style.display = 'flex';
            }
        });
    });
    
    document.querySelectorAll('[style*="display: flex"]').forEach(modal => {
        if (modal.id.includes('Modal')) {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    this.style.display = 'none';
                }
            });
        }
    });
</script>

<style>
    .card-luxury {
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.8) 0%, rgba(30, 41, 59, 0.8) 100%);
        border: 1px solid rgba(148, 113, 113, 0.2);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3);
    }
</style>
@endsection
