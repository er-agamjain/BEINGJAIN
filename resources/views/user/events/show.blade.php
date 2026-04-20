@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <!-- Hero -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-900 via-purple-900 to-slate-800 text-white shadow-2xl border border-purple-500/30">
        <div class="absolute inset-0 opacity-25" style="background: radial-gradient(circle at 15% 15%, rgba(255,215,0,0.25), transparent 35%), radial-gradient(circle at 80% 0%, rgba(255,255,255,0.15), transparent 30%);"></div>
        <div class="grid lg:grid-cols-2 gap-6 p-8 md:p-10 relative">
            <div class="space-y-4">
                <p class="inline-flex items-center space-x-2 bg-white/10 px-3 py-1 rounded-full text-amber-200 text-sm">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>{{ $event->category ?? 'Featured' }}</span>
                </p>
                <h1 class="text-4xl md:text-5xl font-bold leading-tight">{{ $event->name }}</h1>
                <p class="text-slate-200 text-lg">{{ $event->description }}</p>
                <div class="grid sm:grid-cols-2 gap-3 text-sm text-slate-200">
                    <div class="bg-white/5 border border-white/10 rounded-xl p-3">
                        <p class="text-xs text-slate-300">Date & Time</p>
                        <p class="font-semibold">{{ $event->event_date->format('M d, Y') }} · {{ \Carbon\Carbon::parse($event->event_time)->format('h:i A') }}</p>
                    </div>
                    <div class="bg-white/5 border border-white/10 rounded-xl p-3">
                        <p class="text-xs text-slate-300">Location</p>
                        <p class="font-semibold">{{ $event->city?->name ?? $event->location ?? 'N/A' }}</p>
                    </div>
                    <div class="bg-white/5 border border-white/10 rounded-xl p-3">
                        <p class="text-xs text-slate-300">Organiser</p>
                        <p class="font-semibold flex items-center gap-2"><i class="fas fa-user-tie text-purple-200"></i>{{ $event->organiser->name }}</p>
                    </div>
                    <div class="bg-white/5 border border-white/10 rounded-xl p-3">
                        <p class="text-xs text-slate-300">Capacity</p>
                        <p class="font-semibold">{{ $event->capacity }} seats</p>
                    </div>
                </div>
            </div>
            <div class="relative">
                <div class="absolute -left-10 -top-6 w-32 h-32 bg-amber-400/20 blur-3xl"></div>
                <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-cyan-400/20 blur-3xl"></div>
                <div class="relative bg-white/5 border border-white/10 rounded-2xl p-4 shadow-xl backdrop-blur">
                    @if($event->image)
                        <div class="relative h-72 rounded-2xl overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-b from-transparent via-slate-900/10 to-slate-900/50"></div>
                            <img src="{{ $event->image_url }}" alt="{{ $event->name }}" class="w-full h-full object-cover">
                            <div class="absolute bottom-3 left-3 text-white">
                                <p class="text-sm">{{ $event->city?->name ?? $event->location ?? '' }}</p>
                                <p class="font-semibold">{{ $event->event_date->format('M d') }} · {{ \Carbon\Carbon::parse($event->event_time)->format('h:i A') }}</p>
                            </div>
                        </div>
                    @else
                        <div class="h-72 rounded-2xl bg-gradient-to-br from-blue-500 via-purple-500 to-pink-500 flex items-center justify-center text-4xl">🎟️</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Seat map preview & info -->
    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 rounded-2xl border border-white/10 bg-white/5 text-white shadow-xl p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-2xl font-bold">Seat map preview</h2>
                @if($seatMapData)
                    <span class="text-xs text-slate-300">{{ $seatMapData['venue']->name }}</span>
                @else
                    <span class="text-xs text-slate-300">VIP · Gold · Silver zones</span>
                @endif
            </div>
            <div class="bg-slate-900/60 rounded-xl p-4 border border-white/10">
                <div class="text-center text-slate-300 text-sm mb-3">Screen</div>
                
                @if($seatMapData && $seatMapData['categories']->isNotEmpty())
                    <!-- Real seat map based on venue data -->
                    <div class="grid grid-cols-12 gap-1 text-center text-xs">
                        @php
                            $totalRows = ceil($seatMapData['venue']->total_capacity / 12);
                            $categoryIndex = 0;
                            $categories = $seatMapData['categories']->sortBy('name');
                        @endphp
                        @for($row = 1; $row <= min($totalRows, 8); $row++)
                            @for($col = 1; $col <= 12; $col++)
                                @php
                                    // Distribute categories across rows
                                    $categoryCount = $categories->count();
                                    $rowsPerCategory = ceil($totalRows / $categoryCount);
                                    $categoryIndex = min(floor(($row - 1) / max(1, $rowsPerCategory)), $categoryCount - 1);
                                    $category = $categories->values()[$categoryIndex] ?? $categories->first();
                                    
                                    $availability = $seatMapData['availability'][$category->id] ?? ['percentage' => 100];
                                    $isAvailable = $availability['percentage'] > 20;
                                    
                                    // Color based on category color or fallback
                                    $color = $category->color ?? '#94a3b8';
                                    $opacity = $isAvailable ? '60' : '30';
                                @endphp
                                <div class="rounded-sm h-6" style="background-color: {{ $color }}{{ $opacity }};" title="{{ $category->name }} - {{ $availability['available'] ?? 0 }} available"></div>
                            @endfor
                        @endfor
                    </div>
                    <div class="flex gap-4 mt-4 text-xs text-slate-200 flex-wrap">
                        @foreach($categories as $category)
                            @php
                                $availability = $seatMapData['availability'][$category->id] ?? ['available' => 0, 'total' => 0];
                            @endphp
                            <span class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-sm" style="background-color: {{ $category->color ?? '#94a3b8' }}60;"></span>
                                {{ $category->name }} ({{ $availability['available'] }}/{{ $availability['total'] }})
                            </span>
                        @endforeach
                    </div>
                @else
                    <!-- Fallback generic seat map -->
                    <div class="grid grid-cols-12 gap-1 text-center text-xs">
                        @for($row = 1; $row <= 6; $row++)
                            @for($col = 1; $col <= 12; $col++)
                                @php
                                    $zone = $row <= 2 ? 'VIP' : ($row <= 4 ? 'Gold' : 'Silver');
                                    $color = $zone === 'VIP' ? 'bg-amber-400/60' : ($zone === 'Gold' ? 'bg-emerald-400/60' : 'bg-slate-500/50');
                                @endphp
                                <div class="{{ $color }} rounded-sm h-6"></div>
                            @endfor
                        @endfor
                    </div>
                    <div class="flex gap-4 mt-4 text-xs text-slate-200">
                        <span class="flex items-center gap-2"><span class="w-3 h-3 bg-amber-400/60 rounded-sm"></span>VIP</span>
                        <span class="flex items-center gap-2"><span class="w-3 h-3 bg-emerald-400/60 rounded-sm"></span>Gold</span>
                        <span class="flex items-center gap-2"><span class="w-3 h-3 bg-slate-500/50 rounded-sm"></span>Silver</span>
                    </div>
                @endif
            </div>
        </div>
        <div class="rounded-2xl border border-white/10 bg-white/5 text-white shadow-xl p-6 space-y-3">
            <h3 class="text-xl font-semibold">Show Timings</h3>
            @if($event->showTimings->isNotEmpty())
                <div class="space-y-2 text-sm">
                    @foreach($event->showTimings->take(5) as $showTiming)
                        <div class="rounded-xl bg-slate-900/60 border border-white/10 p-3">
                            <p class="font-semibold">{{ $showTiming->show_date_time->format('M d, Y') }}</p>
                            <p class="text-slate-300">{{ $showTiming->show_date_time->format('h:i A') }}</p>
                            <p class="text-xs text-slate-400 mt-1">{{ $showTiming->venue->name ?? 'Venue' }} · {{ $showTiming->available_seats }} seats</p>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-slate-300">Check back soon for show timings</p>
            @endif
            
            <hr class="border-white/10 my-3">
            
            <h3 class="text-xl font-semibold">Booking steps</h3>
            <div class="space-y-2 text-sm text-slate-200">
                <p><i class="fas fa-check text-emerald-300 mr-2"></i>Select seats from the map.</p>
                <p><i class="fas fa-credit-card text-amber-300 mr-2"></i>Pay via UPI, cards, netbanking, or wallets.</p>
                <p><i class="fas fa-qrcode text-cyan-300 mr-2"></i>Get instant QR e-ticket & reminders.</p>
            </div>
            <a href="{{ route('user.events.index') }}" class="btn-secondary-luxury bg-slate-700/70 border border-slate-500 text-gray-100 hover:bg-slate-600 w-full text-center">Browse more</a>
        </div>
    </div>

    <!-- Pricing & Booking -->
    <div class="rounded-3xl border border-white/10 bg-white/5 text-white shadow-2xl p-6 space-y-4">
        <h2 class="text-2xl font-bold">Book Your Tickets</h2>
        
        @include('user.events.partials.seat-selector')
    </div>
</div>
@endsection
