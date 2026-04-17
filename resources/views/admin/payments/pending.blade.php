@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-3">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Payments Review</h1>
            <p class="text-slate-600 dark:text-slate-400 mt-1">Review all organiser and admin event payments with status controls</p>
        </div>
        <div class="bg-amber-500/20 border border-amber-400/30 px-4 py-2 rounded-lg">
            <p class="text-amber-700 dark:text-amber-300 font-semibold">
                {{ $pendingCount }} Pending
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

    <!-- Filters -->
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg p-4 md:p-6">
        <form method="GET" action="{{ route('admin.payments.pending') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
            <div>
                <label for="scope" class="block text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase mb-2">Show Payments Of</label>
                <select id="scope" name="scope" class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white text-sm">
                    <option value="my_events" {{ $scope === 'my_events' ? 'selected' : '' }}>Only My Events</option>
                    <option value="all" {{ $scope === 'all' ? 'selected' : '' }}>All Payments</option>
                    <option value="organiser_events" {{ $scope === 'organiser_events' ? 'selected' : '' }}>One Organiser Events</option>
                </select>
            </div>

            <div>
                <label for="organiser_id" class="block text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase mb-2">Organiser</label>
                <select id="organiser_id" name="organiser_id" class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white text-sm">
                    <option value="">All Organisers</option>
                    @foreach($organisers as $organiser)
                        <option value="{{ $organiser->id }}" {{ (string) $organiserId === (string) $organiser->id ? 'selected' : '' }}>{{ $organiser->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="event_id" class="block text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase mb-2">Event</label>
                <select id="event_id" name="event_id" class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white text-sm">
                    <option value="">All Events</option>
                    @foreach($events as $event)
                        <option value="{{ $event->id }}" {{ (string) $eventId === (string) $event->id ? 'selected' : '' }}>
                            {{ Str::limit($event->title, 38) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="year" class="block text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase mb-2">Year</label>
                <select id="year" name="year" class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white text-sm">
                    <option value="">Any Year</option>
                    @foreach($yearOptions as $yearOption)
                        <option value="{{ $yearOption }}" {{ (string) $year === (string) $yearOption ? 'selected' : '' }}>{{ $yearOption }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="payment_date" class="block text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase mb-2">Specific Date</label>
                <input id="payment_date" type="date" name="payment_date" value="{{ $paymentDate }}" class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white text-sm" />
            </div>

            <div class="flex items-end gap-2">
                <button type="submit" class="px-4 py-2.5 bg-cyan-600 hover:bg-cyan-700 text-white rounded-lg text-sm font-semibold transition w-full">
                    <i class="fas fa-filter mr-1"></i> Apply
                </button>
                <a href="{{ route('admin.payments.pending') }}" class="px-4 py-2.5 bg-slate-500 hover:bg-slate-600 text-white rounded-lg text-sm font-semibold transition whitespace-nowrap">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Analytics -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg p-5 border-l-4 border-emerald-500">
            <p class="text-xs font-semibold uppercase text-slate-500 dark:text-slate-400">Total Revenue</p>
            <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400 mt-2">₹{{ number_format($analytics['total_revenue'], 2) }}</p>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg p-5 border-l-4 border-cyan-500">
            <p class="text-xs font-semibold uppercase text-slate-500 dark:text-slate-400">Confirmed Payments</p>
            <p class="text-2xl font-bold text-cyan-600 dark:text-cyan-400 mt-2">{{ $analytics['confirmed_payments'] }}</p>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg p-5 border-l-4 border-red-500">
            <p class="text-xs font-semibold uppercase text-slate-500 dark:text-slate-400">Rejected</p>
            <p class="text-2xl font-bold text-red-600 dark:text-red-400 mt-2">{{ $analytics['rejected_payments'] }}</p>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg p-5 border-l-4 border-amber-500">
            <p class="text-xs font-semibold uppercase text-slate-500 dark:text-slate-400">Payment Not Received</p>
            <p class="text-2xl font-bold text-amber-600 dark:text-amber-400 mt-2">{{ $analytics['not_received_payments'] }}</p>
        </div>
    </div>

    <!-- Payments Table -->
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
                            Organiser
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
                            Status
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
                                    {{ $payment->booking->user->name }}
                                </p>
                                <p class="text-xs text-slate-500 dark:text-slate-400">
                                    {{ $payment->booking->user->email }}
                                </p>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-medium text-slate-900 dark:text-slate-100">
                                {{ Str::limit($payment->booking->event->title, 30) }}
                            </p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm text-slate-700 dark:text-slate-300">
                                {{ $payment->booking->event->organiser->name ?? 'N/A' }}
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
                                @php
                                    $submittedAt = $payment->payment_date ?? $payment->created_at;
                                @endphp
                                <p>{{ $submittedAt->format('M d, Y') }}</p>
                                <p class="text-xs">{{ $submittedAt->format('h:i A') }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($payment->status === 'success')
                                <span class="px-2 py-1 bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-300 rounded text-xs font-semibold">Confirmed</span>
                            @elseif($payment->status === 'failed')
                                <span class="px-2 py-1 bg-red-100 text-red-700 dark:bg-red-500/20 dark:text-red-300 rounded text-xs font-semibold">Rejected</span>
                            @else
                                <span class="px-2 py-1 bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-300 rounded text-xs font-semibold">Not Received</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center space-x-2">
                                <!-- Approve Button -->
                                <form method="POST" action="{{ route('admin.payments.approve', $payment) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded text-sm font-semibold transition"
                                            onclick="return confirm('Mark this payment as confirmed?')">
                                        <i class="fas fa-check mr-1"></i> Confirm
                                    </button>
                                </form>

                                <!-- Reject Button -->
                                <form method="POST" action="{{ route('admin.payments.reject', $payment) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            class="px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white rounded text-sm font-semibold transition"
                                            onclick="return confirm('Mark this payment as rejected?')">
                                        <i class="fas fa-times mr-1"></i> Reject
                                    </button>
                                </form>

                                <!-- Not Received Button -->
                                <form method="POST" action="{{ route('admin.payments.not-received', $payment) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                            class="px-3 py-1.5 bg-amber-500 hover:bg-amber-600 text-white rounded text-sm font-semibold transition"
                                            onclick="return confirm('Mark this payment as not received?')">
                                        <i class="fas fa-hourglass-half mr-1"></i> Not Received
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
        <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-2">No Payments Found</h3>
        <p class="text-slate-600 dark:text-slate-400">No payments match your selected filters.</p>
    </div>
    @endif
</div>
@endsection
