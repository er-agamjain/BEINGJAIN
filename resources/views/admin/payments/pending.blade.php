@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Pending Payments</h1>
            <p class="text-slate-600 dark:text-slate-400 mt-1">Verify and approve payment submissions</p>
        </div>
        <div class="bg-amber-500/20 border border-amber-400/30 px-4 py-2 rounded-lg">
            <p class="text-amber-700 dark:text-amber-300 font-semibold">
                {{ $pendingPayments->total() }} Pending
            </p>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-emerald-500/20 border border-emerald-400/30 text-emerald-700 dark:text-emerald-300 px-4 py-3 rounded-lg">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-500/20 border border-red-400/30 text-red-700 dark:text-red-300 px-4 py-3 rounded-lg">
            <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
        </div>
    @endif

    <!-- Pending Payments Table -->
    @if($pendingPayments->count() > 0)
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 dark:bg-slate-700 border-b border-slate-200 dark:border-slate-600">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                            Payment ID
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                            User
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                            Event
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                            Booking Ref
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                            Amount
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                            Method
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                            Submitted
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                    @foreach($pendingPayments as $payment)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-mono font-semibold text-slate-900 dark:text-slate-100">
                                #{{ $payment->id }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div>
                                <p class="text-sm font-semibold text-slate-900 dark:text-slate-100">
                                    {{ $payment->user->name }}
                                </p>
                                <p class="text-xs text-slate-500 dark:text-slate-400">
                                    {{ $payment->user->email }}
                                </p>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-medium text-slate-900 dark:text-slate-100">
                                {{ Str::limit($payment->booking->event->title, 30) }}
                            </p>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-mono text-cyan-600 dark:text-cyan-400">
                                {{ $payment->booking->booking_reference }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-lg font-bold text-emerald-600 dark:text-emerald-400">
                                ₹{{ number_format($payment->amount, 0) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 bg-slate-200 dark:bg-slate-600 text-slate-700 dark:text-slate-300 rounded text-xs font-semibold uppercase">
                                {{ $payment->payment_method }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-slate-600 dark:text-slate-400">
                                <p>{{ $payment->created_at->format('M d, Y') }}</p>
                                <p class="text-xs">{{ $payment->created_at->format('h:i A') }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center space-x-2">
                                <!-- Approve Button -->
                                <form method="POST" action="{{ route('admin.payments.approve', $payment) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded text-sm font-semibold transition"
                                            onclick="return confirm('Are you sure you want to approve this payment? This will confirm the booking and send tickets to the user.')">
                                        <i class="fas fa-check mr-1"></i> Approve
                                    </button>
                                </form>

                                <!-- Reject Button -->
                                <form method="POST" action="{{ route('admin.payments.reject', $payment) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            class="px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white rounded text-sm font-semibold transition"
                                            onclick="return confirm('Are you sure you want to reject this payment? This will cancel the booking and release the seats.')">
                                        <i class="fas fa-times mr-1"></i> Reject
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($pendingPayments->hasPages())
        <div class="px-6 py-4 bg-slate-50 dark:bg-slate-700 border-t border-slate-200 dark:border-slate-600">
            {{ $pendingPayments->links() }}
        </div>
        @endif
    </div>
    @else
    <!-- Empty State -->
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg p-12 text-center">
        <div class="mb-6">
            <i class="fas fa-check-circle text-6xl text-emerald-400"></i>
        </div>
        <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-2">All Caught Up!</h3>
        <p class="text-slate-600 dark:text-slate-400">There are no pending payments to review at the moment.</p>
    </div>
    @endif
</div>
@endsection
