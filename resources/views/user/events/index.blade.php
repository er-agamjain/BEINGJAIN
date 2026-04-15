@extends('layouts.app')

@section('title', 'Browse Events')

@section('content')
<!-- Hero -->
<div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-900 via-purple-900 to-slate-800 text-white shadow-2xl border border-purple-500/30 mb-8">
    <div class="absolute inset-0 opacity-25" style="background: radial-gradient(circle at 10% 10%, rgba(255,215,0,0.25), transparent 35%), radial-gradient(circle at 80% 0%, rgba(255,255,255,0.15), transparent 30%);"></div>
    <div class="relative p-8 md:p-10 grid lg:grid-cols-2 gap-6">
        <div class="space-y-4">
            <p class="inline-flex items-center space-x-2 bg-white/10 px-3 py-1 rounded-full text-amber-200 text-sm">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>Trending this week</span>
            </p>
            <h1 class="text-4xl md:text-5xl font-bold leading-tight">Discover luxury screenings, concerts, and live shows near you.</h1>
            <p class="text-slate-200 text-lg max-w-2xl">Filter by category, date, or keyword, then lock seats with seamless checkout and QR e-tickets.</p>
        </div>
        <div class="bg-white/5 border border-white/10 rounded-2xl p-4 shadow-xl backdrop-blur">
            <form method="GET" action="{{ route('user.events.index') }}" class="space-y-4">
                <div>
                    <label class="label text-slate-200">Search</label>
                    <div class="relative flex flex-col sm:flex-row gap-2">
                        <div class="relative flex-1">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Movies, concerts, venues" class="w-full bg-white/10 border border-white/20 text-white placeholder:text-slate-300 pl-10 rounded-lg py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <i class="fas fa-search absolute left-3 top-3 text-slate-300"></i>
                        </div>
                        <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition">
                            <i class="fas fa-search"></i>
                        </button>
                        <button type="button" onclick="toggleFilterDrawer()" class="bg-white/10 hover:bg-white/20 border border-white/20 text-white px-4 py-2 rounded-lg transition flex items-center gap-2 justify-center">
                            <i class="fas fa-sliders-h"></i> <span class="sm:hidden md:inline">Filters</span>
                        </button>
                    </div>
                </div>
                
                <!-- Display Active Filters Summary -->
                @if(request()->anyFilled(['city', 'communities', 'gacchhs', 'categories', 'date_filter', 'event_type', 'booking_type']))
                <div class="flex flex-wrap gap-2 pt-2">
                    @if(request('city'))
                        <span class="bg-purple-500/20 text-purple-200 px-2 py-1 rounded text-xs border border-purple-500/30 flex items-center gap-1">
                            {{ request('city') }} <a href="{{ route('user.events.index', request()->except('city')) }}" class="hover:text-white"><i class="fas fa-times"></i></a>
                        </span>
                    @endif
                    <!-- Add more badges as needed or a generic 'Clear All' -->
                    <a href="{{ route('user.events.index') }}" class="text-xs text-slate-400 hover:text-white underline self-center">Clear all filters</a>
                </div>
                @endif

                <!-- Hidden inputs to preserve filters when searching -->
                @foreach(request()->except(['search', 'page']) as $key => $value)
                    @if(is_array($value))
                        @foreach($value as $v)
                            <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
                        @endforeach
                    @else
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endif
                @endforeach
            </form>
        </div>
    </div>
</div>

<!-- Events Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($events as $event)
        <div class="group rounded-2xl border border-white/10 bg-white/5 text-white shadow-xl hover:-translate-y-1 transition">
            <div class="relative">
                @if($event->image)
                    <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->name }}" class="w-full h-52 object-cover rounded-t-2xl">
                @else
                    <div class="w-full h-52 bg-gradient-to-br from-blue-500 via-purple-500 to-pink-500 rounded-t-2xl flex items-center justify-center">
                        <i class="fas fa-calendar-alt text-white text-5xl"></i>
                    </div>
                @endif
                <div class="absolute top-4 left-4 px-3 py-1 rounded-full bg-black/50 text-xs border border-white/20">{{ $event->eventCategory->category_name ?? 'Event' }}</div>
                <div class="absolute bottom-3 right-3 px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-100 text-xs border border-emerald-400/30">{{ $event->capacity }} seats</div>
            </div>
            <div class="p-5 space-y-3">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold">{{ $event->title }}</h3>
                    <span class="text-amber-300 text-sm flex items-center gap-1"><i class="fas fa-clock"></i> {{ \Carbon\Carbon::parse($event->start_time)->format('h:i A') }}</span>
                </div>
                <p class="text-slate-200 text-sm line-clamp-2">{{ $event->description }}</p>
                <div class="text-sm text-slate-200 space-y-1">
                    <div class="flex items-center gap-2"><i class="fas fa-map-marker-alt text-red-300 w-4"></i> {{ $event->city->name ?? $event->location ?? 'N/A' }}</div>
                    <div class="flex items-center gap-2"><i class="fas fa-calendar text-emerald-300 w-4"></i> {{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}</div>
                    <div class="flex items-center gap-2"><i class="fas fa-user-tie text-purple-300 w-4"></i> {{ $event->organiser->name }}</div>
                </div>
                <div class="pt-3 border-t border-white/10 flex items-center justify-between">
                    <span class="text-xs text-slate-300 flex items-center gap-1"><i class="fas fa-shield-alt"></i> Verified organiser</span>
                    <a href="{{ route('user.events.show', $event) }}" class="btn-primary-luxury bg-gradient-to-r from-amber-400 to-yellow-400 text-slate-900 hover:from-amber-300 hover:to-yellow-300 text-center text-sm px-4 py-2">View & book</a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-span-3 text-center py-16 text-white">
            <i class="fas fa-calendar-times text-6xl text-slate-500 mb-4"></i>
            <p class="text-xl text-slate-200">No events found</p>
            <p class="text-slate-400 mt-2">Try adjusting your search criteria</p>
        </div>
    @endforelse
</div>

<!-- Pagination -->
<div class="mt-8 text-white">
    {{ $events->links() }}
</div>

@include('user.events.partials.filter-drawer')

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection
