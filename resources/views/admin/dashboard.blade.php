@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="py-12">
    <!-- Header Section -->
    <div class="mb-12 animate-fade-in">
        <div class="flex items-center justify-between mb-2">
            <h1 class="text-3xl font-bold text-white" style="font-family: 'Playfair Display', serif;">
                <i class="fas fa-crown text-amber-400"></i> Admin Dashboard
            </h1>
            <div class="text-gray-400 text-sm">{{ now()->format('l, F j, Y') }}</div>
        </div>
        <p class="text-gray-400">Welcome back, {{ auth()->user()->name }}. Here's your system overview.</p>
    </div>

    <!-- KPI Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
        <!-- Total Users -->
        <div class="card-luxury p-6 group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-blue-500/20 to-blue-600/20 flex items-center justify-center group-hover:shadow-lg group-hover:shadow-blue-500/20 transition-all border border-blue-500/30">
                    <i class="fas fa-users text-2xl text-blue-400"></i>
                </div>
                <span class="text-xs font-semibold text-green-400 bg-green-500/20 px-3 py-1 rounded-full border border-green-500/30">Active</span>
            </div>
            <p class="text-gray-400 text-sm mb-2">Total Users</p>
            <p class="text-5xl font-bold text-white">{{ $totalUsers }}</p>
            <p class="text-xs text-gray-400 mt-4"><i class="fas fa-arrow-up text-emerald-400"></i> Growing community</p>
        </div>

        <!-- Total Organisers -->
        <div class="card-luxury p-6 group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-purple-500/20 to-purple-600/20 flex items-center justify-center group-hover:shadow-lg group-hover:shadow-purple-500/20 transition-all border border-purple-500/30">
                    <i class="fas fa-user-tie text-2xl text-purple-400"></i>
                </div>
                <span class="text-xs font-semibold text-purple-400 bg-purple-500/20 px-3 py-1 rounded-full border border-purple-500/30">Partners</span>
            </div>
            <p class="text-gray-400 text-sm mb-2">Total Organisers</p>
            <p class="text-5xl font-bold text-white">{{ $totalOrganisers }}</p>
            <p class="text-xs text-gray-400 mt-4"><i class="fas fa-sparkles text-purple-400"></i> Event creators</p>
        </div>

        <!-- Total Bookings -->
        <div class="card-luxury p-6 group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-amber-500/20 to-amber-600/20 flex items-center justify-center group-hover:shadow-lg group-hover:shadow-amber-500/20 transition-all border border-amber-500/30">
                    <i class="fas fa-ticket-alt text-2xl text-amber-400"></i>
                </div>
                <span class="text-xs font-semibold text-amber-400 bg-amber-500/20 px-3 py-1 rounded-full border border-amber-500/30">Bookings</span>
            </div>
            <p class="text-gray-400 text-sm mb-2">Total Bookings</p>
            <p class="text-5xl font-bold text-white">{{ $totalBookings }}</p>
            <p class="text-xs text-gray-400 mt-4"><i class="fas fa-chart-line text-amber-400"></i> Increasing demand</p>
        </div>

        <!-- Total Revenue -->
        <div class="card-luxury p-6 group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-emerald-500/20 to-teal-600/20 flex items-center justify-center group-hover:shadow-lg group-hover:shadow-emerald-500/20 transition-all border border-emerald-500/30">
                    <i class="fas fa-money-bill-wave text-2xl text-emerald-400"></i>
                </div>
                <span class="text-xs font-semibold text-emerald-400 bg-emerald-500/20 px-3 py-1 rounded-full border border-emerald-500/30">Revenue</span>
            </div>
            <p class="text-gray-400 text-sm mb-2">Total Revenue</p>
            <p class="text-4xl font-bold text-white">₹{{ number_format($totalRevenue, 0) }}</p>
            <p class="text-xs text-gray-400 mt-4"><i class="fas fa-arrow-up text-emerald-400"></i> Strong performance</p>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
        <!-- Recent Bookings Section -->
        <div class="lg:col-span-2">
            <div class="card-luxury p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-white flex items-center">
                        <i class="fas fa-clock text-emerald-400 mr-3"></i> Recent Bookings
                    </h2>
                    <a href="{{ route('admin.bookings.index') }}" class="text-emerald-400 hover:text-emerald-300 text-sm font-semibold">View All →</a>
                </div>
                
                <div class="space-y-3">
                    @forelse($recentBookings as $booking)
                        <div class="flex items-center justify-between p-4 bg-slate-700/20 rounded-xl hover:bg-slate-700/40 transition-all border border-slate-700/30 group">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-amber-400 to-yellow-400 flex items-center justify-center text-slate-900 font-bold">
                                    {{ strtoupper(substr($booking->user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-white">{{ $booking->user->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $booking->event->name }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-emerald-400 text-lg">₹{{ number_format($booking->total_price, 2) }}</p>
                                <span class="text-xs {{ $booking->status === 'confirmed' ? 'text-green-400 bg-green-500/20 border border-green-500/30' : 'text-yellow-400 bg-yellow-500/20 border border-yellow-500/30' }} px-2 py-1 rounded-full">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <i class="fas fa-inbox text-4xl text-gray-600 mb-3"></i>
                            <p class="text-gray-400">No bookings yet</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Quick Actions Section -->
        <div class="lg:col-span-1">
            <div class="card-luxury p-6">
                <h2 class="text-2xl font-bold text-white flex items-center mb-6">
                    <i class="fas fa-bolt text-amber-400 mr-3"></i> Quick Actions
                </h2>
                
                <div class="space-y-3">
                    <a href="{{ route('admin.organisers.index') }}" class="block px-6 py-4 bg-gradient-to-r from-blue-500/20 to-blue-600/20 text-blue-300 rounded-xl text-center hover:from-blue-500/40 hover:to-blue-600/40 transition-all border border-blue-500/30 font-semibold group">
                        <i class="fas fa-user-tie group-hover:scale-110 transition-transform"></i> Manage Organisers
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="block px-6 py-4 bg-gradient-to-r from-purple-500/20 to-purple-600/20 text-purple-300 rounded-xl text-center hover:from-purple-500/40 hover:to-purple-600/40 transition-all border border-purple-500/30 font-semibold group">
                        <i class="fas fa-users group-hover:scale-110 transition-transform"></i> View Users
                    </a>
                    <a href="{{ route('admin.events.index') }}" class="block px-6 py-4 bg-gradient-to-r from-orange-500/20 to-orange-600/20 text-orange-300 rounded-xl text-center hover:from-orange-500/40 hover:to-orange-600/40 transition-all border border-orange-500/30 font-semibold group">
                        <i class="fas fa-calendar-alt group-hover:scale-110 transition-transform"></i> Manage Events
                    </a>

                    <a href="{{ route('admin.transactions.index') }}" class="block px-6 py-4 bg-gradient-to-r from-emerald-500/20 to-teal-600/20 text-emerald-300 rounded-xl text-center hover:from-emerald-500/40 hover:to-teal-600/40 transition-all border border-emerald-500/30 font-semibold group">
                        <i class="fas fa-credit-card group-hover:scale-110 transition-transform"></i> View Transactions
                    </a>
                    <a href="{{ route('admin.bookings.index') }}" class="block px-6 py-4 bg-gradient-to-r from-rose-500/20 to-rose-600/20 text-rose-300 rounded-xl text-center hover:from-rose-500/40 hover:to-rose-600/40 transition-all border border-rose-500/30 font-semibold group">
                        <i class="fas fa-ticket-alt group-hover:scale-110 transition-transform"></i> View Bookings
                    </a>
                    <a href="{{ route('admin.payments.pending') }}" class="block px-6 py-4 bg-gradient-to-r from-amber-500/20 to-yellow-600/20 text-amber-300 rounded-xl text-center hover:from-amber-500/40 hover:to-yellow-600/40 transition-all border border-amber-500/30 font-semibold group">
                        <i class="fas fa-wallet group-hover:scale-110 transition-transform"></i> View Payments
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- System Stats Summary -->
    <div class="card-luxury p-6">
        <h3 class="text-xl font-bold text-white mb-4 flex items-center">
            <i class="fas fa-chart-bar text-amber-400 mr-3"></i> System Overview
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="text-center p-4 bg-gradient-to-br from-amber-500/20 to-orange-600/20 rounded-lg border border-amber-500/30">
                <p class="text-4xl font-bold text-amber-400">{{ number_format(($totalBookings > 0 ? ($totalRevenue / $totalBookings) : 0), 2) }}</p>
                <p class="text-gray-400 text-sm mt-2">Average Booking Value</p>
            </div>
            <div class="text-center p-4 bg-gradient-to-br from-purple-500/20 to-purple-600/20 rounded-lg border border-purple-500/30">
                <p class="text-4xl font-bold text-purple-400">{{ number_format(($totalOrganisers > 0 ? ($totalBookings / $totalOrganisers) : 0), 1) }}</p>
                <p class="text-gray-400 text-sm mt-2">Bookings per Organiser</p>
            </div>
            <div class="text-center p-4 bg-gradient-to-br from-emerald-500/20 to-teal-600/20 rounded-lg border border-emerald-500/30">
                <p class="text-4xl font-bold text-emerald-400">{{ $totalUsers + $totalOrganisers }}</p>
                <p class="text-gray-400 text-sm mt-2">Total Active Users</p>
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
