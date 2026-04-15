@extends('layouts.app')

@section('title', 'Refunds Management')

@section('content')
<div class="mb-8 animate-fade-in">
    <h1 class="text-4xl font-bold text-transparent bg-gradient-to-r from-red-400 via-pink-300 to-red-400 bg-clip-text mb-2" style="font-family: 'Playfair Display', serif;">
        <i class="fas fa-undo text-red-400"></i> Refunds Management
    </h1>
    <p class="text-gray-400">Handle customer refund requests and approvals</p>
</div>

<!-- Filters -->
<div class="card-luxury mb-8">
    <form method="GET" action="{{ route('admin.refunds.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="label">Status</label>
            <select name="status" class="input-field">
                <option value="">All Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
            </select>
        </div>
        
        <div>
            <label class="label">Date From</label>
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="input-field">
        </div>
        
        <div>
            <label class="label">Date To</label>
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="input-field">
        </div>
        
        <div class="flex items-end">
            <button type="submit" class="btn-primary w-full">
                <i class="fas fa-filter"></i> Filter
            </button>
        </div>
    </form>
</div>

<!-- Refunds Table -->
<div class="card-luxury">
    <div class="mb-6 flex items-center justify-between">
        <h2 class="text-2xl font-bold text-white flex items-center">
            <i class="fas fa-list text-red-400 mr-3"></i> Refund Requests
        </h2>
        <div class="text-sm text-gray-400">
            Total: <span class="text-red-400 font-bold">{{ $refunds->total() }}</span>
        </div>
    </div>

    @if($refunds->isEmpty())
        <div class="text-center py-12">
            <i class="fas fa-inbox text-gray-600 text-4xl mb-4"></i>
            <p class="text-gray-400">No refund requests found</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-700/50 border-b-2 border-slate-600/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-red-400 uppercase">User</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-red-400 uppercase">Amount</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-red-400 uppercase">Reason</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-red-400 uppercase">Method</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-red-400 uppercase">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-red-400 uppercase">Requested</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-red-400 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/30">
                    @foreach($refunds as $refund)
                        <tr class="hover:bg-slate-700/20 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-red-400 to-pink-400 flex items-center justify-center text-slate-900 font-bold mr-3">
                                        {{ strtoupper(substr($refund->user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-medium text-white">{{ $refund->user->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $refund->user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-lg font-bold text-white">₹{{ number_format($refund->amount, 2) }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-gray-400 text-sm max-w-xs">{{ $refund->reason ?? 'Not provided' }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-gray-400 text-sm capitalize">{{ $refund->refund_method ?? 'Original' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusColors = [
                                        'pending' => 'yellow',
                                        'approved' => 'blue',
                                        'rejected' => 'red',
                                        'completed' => 'green'
                                    ];
                                    $color = $statusColors[$refund->status] ?? 'gray';
                                @endphp
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-{{ $color }}-500/20 text-{{ $color }}-400">
                                    {{ ucfirst($refund->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-gray-400 text-sm">{{ $refund->created_at->format('M d, Y') }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.refunds.show', $refund) }}" class="btn-sm bg-blue-600 hover:bg-blue-700">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6 flex justify-center">
            {{ $refunds->links('pagination::tailwind') }}
        </div>
    @endif
</div>

@if($errors->any())
    <div class="mt-4 card-luxury border-l-4 border-red-500 bg-red-500/10">
        <h3 class="text-red-400 font-bold mb-2">Error:</h3>
        <p class="text-red-400">{{ $errors->first() }}</p>
    </div>
@endif

@if(session('success'))
    <div class="mt-4 card-luxury border-l-4 border-green-500 bg-green-500/10">
        <p class="text-green-400 flex items-center">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </p>
    </div>
@endif

<style>
    .btn-sm {
        @apply px-3 py-2 rounded-lg text-sm font-medium text-white transition-colors;
    }
</style>
@endsection
