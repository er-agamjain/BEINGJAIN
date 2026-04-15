@extends('layouts.app')

@section('title', 'Experience Luxury Jain Religious Events')

@section('content')
<div class="space-y-10">
    <!-- Hero -->
    <section class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-900 via-purple-900 to-slate-800 text-white shadow-2xl border border-purple-500/30">
        <div class="absolute inset-0 opacity-30" style="background: radial-gradient(circle at 20% 20%, rgba(255,215,0,0.25), transparent 35%), radial-gradient(circle at 80% 0%, rgba(255,255,255,0.15), transparent 30%), radial-gradient(circle at 70% 60%, rgba(0,255,255,0.1), transparent 25%);"></div>
        <div class="grid lg:grid-cols-2 gap-10 p-10 relative">
            <div class="space-y-6">
                <div class="inline-flex items-center space-x-3 bg-white/10 px-4 py-2 rounded-full text-amber-200 shadow-lg">
                    <span class="text-sm font-semibold">Luxury Dark Mode</span>
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                </div>
                <h1 class="text-4xl md:text-5xl font-bold leading-tight">Book premium events, movies, and concerts in minutes.</h1>
                <p class="text-slate-200 text-lg max-w-xl">Choose your city, explore curated experiences, and lock seats with one-tap payments across UPI, cards, netbanking, and wallets.</p>

                <div class="grid sm:grid-cols-3 gap-3">
                    <div class="bg-white/10 border border-white/10 rounded-2xl p-4 shadow-lg">
                        <p class="text-3xl font-bold text-amber-300">180+</p>
                        <p class="text-sm text-slate-200">Live shows this week</p>
                    </div>
                    <div class="bg-white/10 border border-white/10 rounded-2xl p-4 shadow-lg">
                        <p class="text-3xl font-bold text-emerald-300">45</p>
                        <p class="text-sm text-slate-200">Cities covered</p>
                    </div>
                    <div class="bg-white/10 border border-white/10 rounded-2xl p-4 shadow-lg">
                        <p class="text-3xl font-bold text-cyan-300">4.9★</p>
                        <p class="text-sm text-slate-200">User satisfaction</p>
                    </div>
                </div>

                <div class="bg-white/5 border border-white/10 rounded-2xl p-5 backdrop-blur space-y-3">
                    <div class="flex flex-col sm:flex-row gap-3">
                        <div class="flex flex-col gap-1 sm:w-1/4">
                            <label class="text-xs font-semibold text-slate-300 uppercase tracking-wide">City</label>
                            <select class="w-full px-4 py-3 rounded-xl bg-slate-800/80 border-2 border-slate-600 text-white focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition-all">
                                @foreach($cities as $city)
                                    <option class="text-slate-900 bg-white">{{ $city }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex flex-col gap-1 flex-1">
                            <label class="text-xs font-semibold text-slate-300 uppercase tracking-wide">Search</label>
                            <div class="relative">
                                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                                <input type="text" placeholder="Movies, concerts, venues..." class="w-full pl-11 pr-4 py-3 rounded-xl bg-slate-800/80 border-2 border-slate-600 text-white placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition-all" />
                            </div>
                        </div>
                        <div class="flex flex-col gap-1 sm:w-auto justify-end">
                            <label class="text-xs font-semibold text-transparent uppercase tracking-wide hidden sm:block">Go</label>
                            <a href="{{ route('user.events.index') }}" class="inline-flex items-center justify-center gap-2 px-8 py-3 bg-gradient-to-r from-amber-400 to-yellow-400 hover:from-amber-300 hover:to-yellow-300 text-slate-900 font-bold rounded-xl transition-all duration-200 shadow-lg hover:shadow-amber-400/30 whitespace-nowrap">
                                <i class="fas fa-search text-sm"></i> Explore Events
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="relative">
                <div class="absolute -left-10 -top-6 w-32 h-32 bg-amber-400/20 blur-3xl"></div>
                <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-cyan-400/20 blur-3xl"></div>
                <div class="relative bg-white/5 border border-white/10 rounded-3xl p-6 shadow-2xl backdrop-blur">
                    <div class="flex justify-between items-center mb-4">
                        <div>
                            <p class="text-sm text-slate-200">Tonight · IMAX Laser</p>
                            <h3 class="text-2xl font-bold">Nebula Nights</h3>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs bg-emerald-500/20 text-emerald-200 border border-emerald-400/30">Trending</span>
                    </div>
                    <div class="relative h-52 rounded-2xl overflow-hidden mb-4">
                        <div class="absolute inset-0 bg-gradient-to-b from-transparent via-slate-900/20 to-slate-900/60"></div>
                        <img src="https://images.unsplash.com/photo-1464375117522-1311d6a5b81f?auto=format&fit=crop&w=1000&q=80" alt="Hero" class="w-full h-full object-cover">
                        <div class="absolute bottom-3 left-3 text-white">
                            <p class="text-sm">Mumbai · 9:30 PM</p>
                            <p class="font-semibold">Seats filling fast</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-3 text-sm">
                        <div class="bg-white/5 border border-white/10 rounded-xl p-3 text-center">
                            <p class="text-amber-300 font-bold text-lg">VIP</p>
                            <p class="text-slate-200">Recliners</p>
                        </div>
                        <div class="bg-white/5 border border-white/10 rounded-xl p-3 text-center">
                            <p class="text-emerald-300 font-bold text-lg">Gold</p>
                            <p class="text-slate-200">Snacks + Lounge</p>
                        </div>
                        <div class="bg-white/5 border border-white/10 rounded-xl p-3 text-center">
                            <p class="text-cyan-300 font-bold text-lg">Silver</p>
                            <p class="text-slate-200">Value seats</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Offers & Banners -->
    <section class="grid lg:grid-cols-3 gap-4">
        @foreach($offers as $offer)
            <div class="rounded-2xl p-5 border border-white/10 bg-gradient-to-br from-slate-800/80 to-slate-900/80 text-white shadow-xl relative overflow-hidden">
                <div class="absolute right-4 top-4 px-3 py-1 text-xs rounded-full bg-amber-400/20 text-amber-200 border border-amber-400/40">{{ $offer['badge'] }}</div>
                <h3 class="text-xl font-bold mb-2">{{ $offer['title'] }}</h3>
                <p class="text-slate-200">{{ $offer['subtitle'] }}</p>
            </div>
        @endforeach
    </section>

    <!-- Categories -->
    <section class="bg-white/5 border border-white/10 rounded-2xl p-6 shadow-xl">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-2xl font-bold text-white">Browse by Category</h2>
            <a href="{{ route('user.events.index') }}" class="text-amber-300 hover:text-amber-200">View all</a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-3">
            @foreach($categories as $category)
                <a href="{{ route('user.events.index', ['category' => $category]) }}" class="group rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-center text-slate-200 hover:border-amber-300/70 hover:text-amber-200 transition">
                    <span class="block text-sm font-semibold">{{ $category }}</span>
                    <span class="block text-xs text-slate-400 group-hover:text-amber-200/80">Curated picks</span>
                </a>
            @endforeach
        </div>
    </section>

    <!-- Trending -->
    <section class="space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-white">Trending near you</h2>
            <a href="{{ route('user.events.index') }}" class="text-amber-300 hover:text-amber-200">See all</a>
        </div>
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach($trending as $item)
                <div class="group rounded-2xl border border-white/10 bg-white/5 p-4 text-white shadow-lg hover:-translate-y-1 transition">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <p class="text-xs text-slate-300">{{ $item['city'] }}</p>
                            <h3 class="text-lg font-bold">{{ $item['name'] }}</h3>
                        </div>
                        <span class="px-3 py-1 rounded-full text-[10px] bg-purple-500/20 text-purple-100 border border-purple-400/30">{{ $item['tag'] }}</span>
                    </div>
                    <p class="text-slate-200 text-sm mb-2">Genre: {{ $item['genre'] }}</p>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-amber-300 font-semibold">₹{{ $item['price'] }}</span>
                        <span class="text-emerald-300">★ {{ number_format($item['rating'], 1) }}</span>
                    </div>
                    @if(isset($item['id']))
                        <a href="{{ route('user.events.show', $item['id']) }}" class="mt-3 flex items-center justify-center gap-2 px-4 py-2.5 bg-gradient-to-r from-amber-400 to-yellow-400 hover:from-amber-300 hover:to-yellow-300 text-slate-900 font-bold text-xs rounded-xl transition-all duration-200 shadow-md hover:shadow-amber-400/30">
                            <i class="fas fa-ticket-alt text-xs"></i> View Details
                        </a>
                    @endif
                </div>
            @endforeach
        </div>
    </section>

    <!-- How it works -->
    <section class="grid md:grid-cols-3 gap-4 bg-white/5 border border-white/10 rounded-2xl p-6 text-white shadow-xl">
        <div class="space-y-2">
            <div class="w-10 h-10 rounded-full bg-amber-400/20 border border-amber-400/50 flex items-center justify-center text-amber-100">1</div>
            <h3 class="text-xl font-semibold">Select city & showtime</h3>
            <p class="text-slate-200 text-sm">Find screens or venues near you with live availability.</p>
        </div>
        <div class="space-y-2">
            <div class="w-10 h-10 rounded-full bg-emerald-400/20 border border-emerald-400/50 flex items-center justify-center text-emerald-100">2</div>
            <h3 class="text-xl font-semibold">Choose seats</h3>
            <p class="text-slate-200 text-sm">VIP, Gold, Silver with dynamic pricing and seat map highlights.</p>
        </div>
        <div class="space-y-2">
            <div class="w-10 h-10 rounded-full bg-cyan-400/20 border border-cyan-400/50 flex items-center justify-center text-cyan-100">3</div>
            <h3 class="text-xl font-semibold">Pay & get QR</h3>
            <p class="text-slate-200 text-sm">Instant confirmation, wallet/UPI/cards, and QR e-ticket.</p>
        </div>
    </section>
</div>
@endsection
