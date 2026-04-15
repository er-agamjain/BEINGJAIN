@extends('layouts.app')

@section('title', 'Transactions')

@section('content')
<div class="mb-8 animate-fade-in">
    <h1 class="text-4xl font-bold text-white mb-2" style="font-family: 'Playfair Display', serif;">
        <i class="fas fa-credit-card text-emerald-400"></i> Transactions
    </h1>
    <p class="text-gray-400">Monitor all payment transactions and financial activities</p>
</div>

<!-- Filters -->
<div class="card-luxury mb-8">
    <div class="p-6">
        <form method="GET" action="{{ route('admin.transactions.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-gray-300 font-bold mb-2">Status</label>
                <select name="status" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                    <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>Refunded</option>
                </select>
            </div>
            
            <div>
                <label class="block text-gray-300 font-bold mb-2">From Date</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            
            <div>
                <label class="block text-gray-300 font-bold mb-2">To Date</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            
            <div class="flex items-end">
                <button type="submit" class="px-4 py-2 bg-gradient-to-r from-emerald-500 to-teal-500 hover:shadow-lg hover:shadow-emerald-500/20 text-white font-medium rounded-lg transition-all w-full">
                    <i class="fas fa-filter"></i> Filter
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Transactions Table -->
<div class="card-luxury mb-8">
    <div class="p-6">
        <div class="mb-6 flex items-center justify-between">
            <h2 class="text-2xl font-bold text-white flex items-center">
                <i class="fas fa-list text-emerald-400 mr-3"></i> Transaction History
            </h2>
            <div class="text-sm text-gray-400">
                Found <span class="text-emerald-400 font-bold">{{ $transactions->total() }}</span> transaction(s)
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-700/50 border-b-2 border-slate-600/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-amber-400 uppercase">Transaction ID</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-amber-400 uppercase">User</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-amber-400 uppercase">Booking Ref</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-amber-400 uppercase">Amount</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-amber-400 uppercase">Method</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-amber-400 uppercase">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-amber-400 uppercase">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/30">
                    @forelse($transactions as $transaction)
                        <tr class="hover:bg-slate-700/20 transition-colors group">
                            <td class="px-6 py-4 font-mono text-sm text-amber-400">{{ substr($transaction->transaction_id, 0, 12) }}...</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-amber-400 to-orange-400 flex items-center justify-center text-slate-900 font-bold mr-3">
                                        {{ strtoupper(substr($transaction->user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-medium text-white">{{ $transaction->user->name }}</div>
                                        <div class="text-xs text-gray-400">{{ $transaction->user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.bookings.index', ['id' => $transaction->booking_id]) }}" class="text-emerald-400 hover:text-emerald-300 font-medium transition-colors">
                                    {{ $transaction->booking->booking_reference }}
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-lg font-bold text-emerald-400">₹{{ number_format($transaction->amount, 2) }}</span>
                            </td>
                            <td class="px-6 py-4 text-gray-300">
                                <span class="px-3 py-1 bg-blue-500/20 text-blue-200 rounded-lg text-xs font-semibold border border-blue-500/30">
                                    {{ ucfirst($transaction->payment_method) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @switch($transaction->status)
                                    @case('success')
                                        <span class="px-3 py-1 bg-green-500/20 text-green-200 rounded-lg text-xs font-semibold border border-green-500/30">
                                            <i class="fas fa-check-circle"></i> Success
                                        </span>
                                        @break
                                    @case('pending')
                                        <span class="px-3 py-1 bg-yellow-500/20 text-yellow-200 rounded-lg text-xs font-semibold border border-yellow-500/30">
                                            <i class="fas fa-clock"></i> Pending
                                        </span>
                                        @break
                                    @case('failed')
                                        <span class="px-3 py-1 bg-red-500/20 text-red-200 rounded-lg text-xs font-semibold border border-red-500/30">
                                            <i class="fas fa-times-circle"></i> Failed
                                        </span>
                                        @break
                                    @default
                                        <span class="px-3 py-1 bg-gray-500/20 text-gray-200 rounded-lg text-xs font-semibold border border-gray-500/30">
                                            {{ ucfirst($transaction->status) }}
                                        </span>
                                @endswitch
                            </td>
                            <td class="px-6 py-4 text-gray-400 text-sm">{{ $transaction->created_at->format('M d, Y h:i A') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-16 text-center text-gray-400">
                                <i class="fas fa-credit-card text-6xl text-gray-600 mb-4 block"></i>
                                <p class="text-lg">No transactions found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-6">
            {{ $transactions->links() }}
        </div>
    </div>
</div>

<!-- Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
    @php
        $totalAmount = $transactions->sum('amount');
        $successCount = $transactions->where('status', 'success')->count();
        $failedCount = $transactions->where('status', 'failed')->count();
    @endphp
    
    <div class="card-luxury p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-400 text-sm mb-2">Total Amount</p>
                <p class="text-4xl font-bold text-emerald-400">₹{{ number_format($totalAmount, 2) }}</p>
            </div>
            <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-emerald-500/20 to-teal-600/20 flex items-center justify-center text-emerald-400 text-3xl border border-emerald-500/30">
                <i class="fas fa-coins"></i>
            </div>
        </div>
    </div>
    
    <div class="card-luxury p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-400 text-sm mb-2">Successful</p>
                <p class="text-4xl font-bold text-blue-400">{{ $successCount }}</p>
            </div>
            <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-blue-500/20 to-blue-600/20 flex items-center justify-center text-blue-400 text-3xl border border-blue-500/30">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
    </div>
    
    <div class="card-luxury p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-400 text-sm mb-2">Failed</p>
                <p class="text-4xl font-bold text-red-400">{{ $failedCount }}</p>
            </div>
            <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-red-500/20 to-red-600/20 flex items-center justify-center text-red-400 text-3xl border border-red-500/30">
                <i class="fas fa-exclamation-circle"></i>
            </div>
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
        box-shadow: 0 25px 30px -5px rgba(217, 119, 6, 0.1);
    }
</style>
@endsection
