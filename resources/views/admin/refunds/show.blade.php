@extends('layouts.app')

@section('title', 'Refund Details')

@section('content')
<div class="mb-8 animate-fade-in flex items-center justify-between">
    <div>
        <h1 class="text-4xl font-bold text-transparent bg-gradient-to-r from-red-400 via-pink-300 to-red-400 bg-clip-text mb-2" style="font-family: 'Playfair Display', serif;">
            <i class="fas fa-undo text-red-400"></i> Refund Details
        </h1>
        <p class="text-gray-400">Manage refund request #{{ $refund->id }}</p>
    </div>
    <a href="{{ route('admin.refunds.index') }}" class="btn-secondary">
        <i class="fas fa-arrow-left"></i> Back
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Main Info -->
    <div class="lg:col-span-2">
        <div class="card-luxury">
            <h2 class="text-2xl font-bold text-white mb-6 flex items-center">
                <i class="fas fa-info-circle text-red-400 mr-3"></i> Refund Information
            </h2>

            <div class="grid grid-cols-2 gap-6 mb-6">
                <div class="border-b border-slate-700/50 pb-4">
                    <p class="text-gray-400 text-sm">Customer Name</p>
                    <p class="text-white text-lg font-semibold">{{ $refund->user->name }}</p>
                </div>
                <div class="border-b border-slate-700/50 pb-4">
                    <p class="text-gray-400 text-sm">Email</p>
                    <p class="text-white text-lg font-semibold">{{ $refund->user->email }}</p>
                </div>
                <div class="border-b border-slate-700/50 pb-4">
                    <p class="text-gray-400 text-sm">Booking ID</p>
                    <p class="text-white text-lg font-semibold">#{{ $refund->booking_id }}</p>
                </div>
                <div class="border-b border-slate-700/50 pb-4">
                    <p class="text-gray-400 text-sm">Refund Amount</p>
                    <p class="text-red-400 text-2xl font-bold">₹{{ number_format($refund->amount, 2) }}</p>
                </div>
            </div>

            <div class="bg-slate-700/30 rounded-lg p-4 mb-6">
                <h3 class="text-white font-semibold mb-3">Refund Reason</h3>
                <p class="text-gray-300">{{ $refund->reason ?? 'Not provided' }}</p>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <p class="text-gray-400 text-sm">Refund Method</p>
                    <p class="text-white font-semibold capitalize">{{ $refund->refund_method ?? 'Original Payment Method' }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-sm">Status</p>
                    @php
                        $statusColors = [
                            'pending' => ['bg-yellow-500/20', 'text-yellow-400'],
                            'approved' => ['bg-blue-500/20', 'text-blue-400'],
                            'rejected' => ['bg-red-500/20', 'text-red-400'],
                            'completed' => ['bg-green-500/20', 'text-green-400']
                        ];
                        $colors = $statusColors[$refund->status] ?? ['bg-gray-500/20', 'text-gray-400'];
                    @endphp
                    <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold {{ $colors[0] }} {{ $colors[1] }}">
                        {{ ucfirst($refund->status) }}
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-gray-400 text-sm">Requested</p>
                    <p class="text-white font-semibold">{{ $refund->created_at->format('M d, Y H:i') }}</p>
                </div>
                @if($refund->processed_at)
                    <div>
                        <p class="text-gray-400 text-sm">Processed</p>
                        <p class="text-white font-semibold">{{ $refund->processed_at->format('M d, Y H:i') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Actions Sidebar -->
    <div>
        <div class="card-luxury">
            <h2 class="text-xl font-bold text-white mb-6 flex items-center">
                <i class="fas fa-cogs text-red-400 mr-3"></i> Actions
            </h2>

            @if($refund->status === 'pending')
                <form method="POST" action="{{ route('admin.refunds.approve', $refund) }}" class="mb-4">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn-action bg-green-600 hover:bg-green-700 w-full">
                        <i class="fas fa-check"></i> Approve
                    </button>
                </form>

                <button onclick="openRejectModal()" class="btn-action bg-red-600 hover:bg-red-700 w-full">
                    <i class="fas fa-times"></i> Reject
                </button>
            @elseif($refund->status === 'approved')
                <form method="POST" action="{{ route('admin.refunds.complete', $refund) }}" class="mb-4">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn-action bg-blue-600 hover:bg-blue-700 w-full">
                        <i class="fas fa-check-double"></i> Mark Complete
                    </button>
                </form>
            @endif

            @if(in_array($refund->status, ['pending', 'approved']))
                <div class="mt-4 p-4 bg-yellow-500/10 border border-yellow-500/30 rounded-lg">
                    <p class="text-yellow-400 text-sm">
                        <i class="fas fa-exclamation-circle"></i> This refund is still being processed
                    </p>
                </div>
            @elseif($refund->status === 'completed')
                <div class="mt-4 p-4 bg-green-500/10 border border-green-500/30 rounded-lg">
                    <p class="text-green-400 text-sm">
                        <i class="fas fa-check-circle"></i> Refund completed on {{ $refund->completed_at->format('M d, Y') }}
                    </p>
                </div>
            @elseif($refund->status === 'rejected')
                <div class="mt-4 p-4 bg-red-500/10 border border-red-500/30 rounded-lg">
                    <p class="text-red-400 text-sm font-semibold">Rejected</p>
                    <p class="text-red-400 text-xs mt-2">User has been notified</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Timeline -->
<div class="card-luxury">
    <h2 class="text-2xl font-bold text-white mb-6 flex items-center">
        <i class="fas fa-history text-red-400 mr-3"></i> Timeline
    </h2>

    <div class="space-y-4">
        <div class="flex gap-4">
            <div class="flex flex-col items-center">
                <div class="w-4 h-4 rounded-full bg-yellow-400 mb-2"></div>
                <div class="w-1 h-12 bg-slate-700/50"></div>
            </div>
            <div>
                <p class="text-white font-semibold">Refund Requested</p>
                <p class="text-gray-400 text-sm">{{ $refund->created_at->format('M d, Y H:i A') }}</p>
            </div>
        </div>

        @if($refund->status !== 'pending')
            <div class="flex gap-4">
                <div class="flex flex-col items-center">
                    <div class="w-4 h-4 rounded-full {{ $refund->status === 'rejected' ? 'bg-red-400' : 'bg-blue-400' }} mb-2"></div>
                    <div class="w-1 h-12 bg-slate-700/50"></div>
                </div>
                <div>
                    <p class="text-white font-semibold">{{ $refund->status === 'rejected' ? 'Rejected' : 'Approved' }}</p>
                    <p class="text-gray-400 text-sm">{{ $refund->processed_at->format('M d, Y H:i A') }}</p>
                </div>
            </div>
        @endif

        @if($refund->status === 'completed')
            <div class="flex gap-4">
                <div class="flex flex-col items-center">
                    <div class="w-4 h-4 rounded-full bg-green-400"></div>
                </div>
                <div>
                    <p class="text-white font-semibold">Completed</p>
                    <p class="text-gray-400 text-sm">{{ $refund->completed_at->format('M d, Y H:i A') }}</p>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-slate-800 rounded-xl p-8 w-full max-w-md border border-slate-700/50 shadow-2xl">
        <h2 class="text-2xl font-bold text-white mb-6">Reject Refund</h2>

        <form method="POST" action="{{ route('admin.refunds.reject', $refund) }}">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="label">Rejection Reason</label>
                <textarea name="reason" class="input-field" placeholder="Explain why this refund is being rejected" rows="4" required></textarea>
            </div>

            <div class="flex gap-3 justify-end">
                <button type="button" onclick="closeRejectModal()" class="btn-secondary">Cancel</button>
                <button type="submit" class="btn-danger">Reject Refund</button>
            </div>
        </form>
    </div>
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

<script>
function openRejectModal() {
    document.getElementById('rejectModal').classList.remove('hidden');
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
}

document.getElementById('rejectModal').addEventListener('click', function(e) {
    if(e.target === this) closeRejectModal();
});
</script>

<style>
    .btn-action {
        @apply px-4 py-3 rounded-lg text-white font-medium transition-colors flex items-center justify-center gap-2;
    }

    .btn-danger {
        @apply px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white font-medium transition-colors;
    }

    .btn-secondary {
        @apply px-4 py-2 rounded-lg bg-slate-700 hover:bg-slate-600 text-white font-medium transition-colors;
    }
</style>
@endsection
