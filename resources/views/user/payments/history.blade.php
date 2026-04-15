@extends('layouts.app')

@section('content')
<div class="py-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold mb-8">Payment History</h1>

        <div class="overflow-x-auto bg-white rounded-lg shadow">
            <table class="w-full">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left">Date</th>
                        <th class="px-6 py-3 text-left">Booking Reference</th>
                        <th class="px-6 py-3 text-left">Amount</th>
                        <th class="px-6 py-3 text-left">Method</th>
                        <th class="px-6 py-3 text-left">Transaction ID</th>
                        <th class="px-6 py-3 text-left">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $payment)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-6 py-3">{{ $payment->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-3">{{ $payment->booking->booking_reference }}</td>
                            <td class="px-6 py-3">₹{{ number_format($payment->amount, 2) }}</td>
                            <td class="px-6 py-3">{{ ucfirst($payment->payment_method) }}</td>
                            <td class="px-6 py-3">{{ $payment->transaction_id ?? 'N/A' }}</td>
                            <td class="px-6 py-3">
                                <span class="px-3 py-1 rounded-full text-sm font-semibold
                                    {{ $payment->status === 'success' ? 'bg-green-100 text-green-800' : ($payment->status === 'failed' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                    {{ ucfirst($payment->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-600">
                                No payments yet
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($payments->hasPages())
            <div class="mt-8">
                {{ $payments->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
