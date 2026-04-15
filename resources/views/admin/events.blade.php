@extends('layouts.app')

@section('title', 'Events Management')

@section('content')
<div class="mb-8 animate-fade-in">
    <div class="flex justify-between items-start">
        <div>
            <h1 class="text-3xl font-bold mb-2" style="font-family: 'Playfair Display', serif; color: #ffffff;">
                <i class="fas fa-calendar-alt text-amber-400"></i> Events Management
            </h1>
            <p class="text-amber-400 mt-2">Approve, manage, and monitor all events</p>
        </div>
        <a href="{{ route('admin.events.create') }}" class="btn-primary event">
            <i class="fas fa-plus mr-2"></i> Create Event
        </a>
    </div>
</div>

<!-- Filter Buttons -->
<div class="mb-8 flex flex-wrap gap-3">
    <button onclick="filterEvents('all')" class="px-6 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 text-gray-700 hover:text-gray-900 transition-all border border-gray-300 font-semibold" id="filter-all">
        <i class="fas fa-list mr-2"></i> All Events
    </button>
    <button onclick="filterEvents('pending')" class="px-6 py-2 rounded-lg bg-yellow-100 hover:bg-yellow-200 text-yellow-700 hover:text-yellow-900 transition-all border border-yellow-300 font-semibold" id="filter-pending">
        <i class="fas fa-clock mr-2"></i> Pending
    </button>
    <button onclick="filterEvents('approved')" class="px-6 py-2 rounded-lg bg-emerald-100 hover:bg-emerald-200 text-emerald-700 hover:text-emerald-900 transition-all border border-emerald-300 font-semibold" id="filter-approved">
        <i class="fas fa-check mr-2"></i> Approved
    </button>
    <button onclick="filterEvents('rejected')" class="px-6 py-2 rounded-lg bg-red-100 hover:bg-red-200 text-red-700 hover:text-red-900 transition-all border border-red-300 font-semibold" id="filter-rejected">
        <i class="fas fa-ban mr-2"></i> Rejected
    </button>
</div>

<!-- Events Grid -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    @forelse($events as $event)
        <div class="bg-white rounded-lg shadow p-6 event-card group" data-status="{{ $event->status }}">
            @if($event->image)
                <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}" class="w-full h-52 object-cover rounded-xl mb-4 group-hover:opacity-90 transition-opacity">
            @else
                <div class="w-full h-52 bg-purple-100 rounded-xl mb-4 flex items-center justify-center border border-purple-300">
                    <i class="fas fa-calendar-alt text-amber-400 text-6xl"></i>
                </div>
            @endif
            
            <div class="flex justify-between items-start mb-3">
                <h3 class="text-xl font-bold text-gray-900">{{ $event->title }}</h3>
                @if($event->status === 'pending')
                    <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-lg text-xs font-semibold border border-yellow-300">
                        <i class="fas fa-clock mr-1"></i> Pending
                    </span>
                @elseif($event->status === 'approved' || $event->status === 'published')
                    <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-lg text-xs font-semibold border border-emerald-300">
                         <i class="fas fa-check mr-1"></i> Published
                    </span>
                @elseif($event->status === 'draft')
                    <span class="px-3 py-1 bg-slate-100 text-slate-700 rounded-lg text-xs font-semibold border border-slate-300">
                        <i class="fas fa-file-alt mr-1"></i> Draft
                    </span>
                @elseif($event->status === 'cancelled')
                    <span class="px-3 py-1 bg-orange-100 text-orange-700 rounded-lg text-xs font-semibold border border-orange-300">
                        <i class="fas fa-times-circle mr-1"></i> Cancelled
                    </span>
                    
                @else
                    <span class="px-3 py-1 bg-red-100 text-red-700 rounded-lg text-xs font-semibold border border-red-300">
                        <i class="fas fa-times mr-1"></i> Rejected
                    </span>
                @endif    
            </div>
            
            <p class="text-gray-600 mb-4 line-clamp-2 text-sm">{{ $event->description }}</p>
            
            <div class="grid grid-cols-2 gap-4 mb-4 text-sm">
                <div class="flex items-center text-gray-700">
                    <i class="fas fa-user-tie text-purple-500 mr-2"></i>
                    <span class="font-medium">{{ $event->organiser->name }}</span>
                </div>
                <div class="flex items-center text-gray-700">
                    <i class="fas fa-map-marker-alt text-blue-500 mr-2"></i>
                    <span>{{ $event->city->name ?? $event->location ?? '—' }}</span>
                </div>
                <div class="flex items-center text-gray-700">
                    <i class="fas fa-calendar text-amber-500 mr-2"></i>
                    <span>{{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}</span>
                </div>
                <div class="flex items-center text-gray-700">
                    <i class="fas fa-tag text-emerald-500 mr-2"></i>
                    <span>{{ $event->is_free ? 'Free Event' : '₹' . number_format($event->base_price, 2) }}</span>
                </div>
            </div>
            
            <div class="flex flex-wrap gap-2 pt-4 border-t border-gray-200">
                <a href="{{ route('admin.events.show', $event) }}" class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-lg text-sm font-medium transition">
                    <i class="fas fa-eye mr-1"></i> View
                </a>
                <a href="{{ route('admin.events.edit', $event) }}" class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-amber-100 hover:bg-amber-200 text-amber-700 rounded-lg text-sm font-medium transition">
                    <i class="fas fa-edit mr-1"></i> Edit
                </a>
                @if($event->status === 'pending')
                    <form action="{{ route('admin.events.approve', $event) }}" method="POST" class="flex-1">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn-success w-full text-sm">
                            <i class="fas fa-check mr-1"></i> Approve
                        </button>
                    </form>
                    <form action="{{ route('admin.events.reject', $event) }}" method="POST" class="flex-1">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn-danger w-full text-sm">
                            <i class="fas fa-times mr-1"></i> Reject
                        </button>
                    </form>
                @endif
                <form action="{{ route('admin.events.destroy', $event) }}" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Delete this event?')" class="btn-danger w-full text-sm">
                        <i class="fas fa-trash mr-1"></i> Delete
                    </button>
                </form>
            </div>
        </div>
    @empty
        <div class="col-span-2 text-center py-16">
            <i class="fas fa-calendar-alt text-8xl text-slate-700 mb-4"></i>
            <p class="text-xl text-gray-400">No events found</p>
        </div>
    @endforelse
</div>

<div class="mt-8">
    {{ $events->links() }}
</div>

<style>
    
    .event {
    background: black;
    padding: 10px 10px 10px 10px;
    border-radius: 10px;
}
    
</style>

<script>
function filterEvents(status) {
    const cards = document.querySelectorAll('.event-card');
    cards.forEach(card => {
        if (status === 'all' || card.dataset.status === status) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}
</script>
@endsection
