<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Experience Luxury Jain Religious Events')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900 min-h-screen font-[Inter]">
    <nav class="bg-gradient-to-r from-slate-800 to-slate-700 shadow-2xl border-b border-purple-500/30 backdrop-blur-lg">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-4 sm:space-x-8">
                    <a href="/" class="flex items-center">
                        <img src="{{ asset('/Being_Jain_Events_Logo.png') }}" alt="Logo" class="h-20 w-80 object-contain mr-2">
                    </a>
                    @auth
                        @if(auth()->user()->isAdmin())
                            <div class="hidden md:flex space-x-1">
                                <a href="{{ route('admin.dashboard') }}" class="nav-link-luxury {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                                    <i class="fas fa-tachometer-alt"></i> Dashboard
                                </a>
                                <a href="{{ route('admin.users.index') }}" class="nav-link-luxury {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                                    <i class="fas fa-users"></i> Users
                                </a>
                                <a href="{{ route('admin.organisers.index') }}" class="nav-link-luxury {{ request()->routeIs('admin.organisers.*') ? 'active' : '' }}">
                                    <i class="fas fa-user-tie"></i> Organisers
                                </a>
                                <a href="{{ route('admin.events.index') }}" class="nav-link-luxury {{ request()->routeIs('admin.events.*') ? 'active' : '' }}">
                                    <i class="fas fa-calendar-alt"></i> Events
                                </a>
                                <a href="{{ route('admin.venues.index') }}" class="nav-link-luxury {{ request()->routeIs('admin.venues.*') || request()->routeIs('admin.seat-categories.*') ? 'active' : '' }}">
                                    <i class="fas fa-building"></i> Venues
                                </a>
                                <a href="{{ route('admin.event-categories.index') }}" class="nav-link-luxury {{ request()->routeIs('admin.event-categories.*') ? 'active' : '' }}">
                                    <i class="fas fa-tags"></i> Categories
                                </a>
                                <a href="{{ route('admin.seats.index') }}" class="nav-link-luxury {{ request()->routeIs('admin.seats.*') ? 'active' : '' }}">
                                    <i class="fas fa-chair"></i> Seats
                                </a>
                                <a href="{{ route('admin.transactions.index') }}" class="nav-link-luxury {{ request()->routeIs('admin.transactions.*') ? 'active' : '' }}">
                                    <i class="fas fa-credit-card"></i> Transactions
                                </a>
                                <a href="{{ route('admin.bookings.index') }}" class="nav-link-luxury {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">
                                    <i class="fas fa-ticket-alt"></i> Bookings
                                </a>
                            </div>
                        @elseif(auth()->user()->isOrganiser())
                            <div class="hidden md:flex space-x-1">
                                <a href="{{ route('organiser.events.index') }}" class="nav-link-luxury {{ request()->routeIs('organiser.events.*') ? 'active' : '' }}">
                                    <i class="fas fa-calendar-alt"></i> My Events
                                </a>
                                <a href="{{ route('organiser.show-timings.index') }}" class="nav-link-luxury {{ request()->routeIs('organiser.show-timings.*') ? 'active' : '' }}">
                                    <i class="fas fa-clock"></i> Show Timings
                                </a>
                                <a href="{{ route('organiser.venues.index') }}" class="nav-link-luxury {{ request()->routeIs('organiser.venues.*') ? 'active' : '' }}">
                                    <i class="fas fa-building"></i> Venues
                                </a>
                                <a href="{{ route('organiser.bookings.index') }}" class="nav-link-luxury {{ request()->routeIs('organiser.bookings.*') ? 'active' : '' }}">
                                    <i class="fas fa-ticket-alt"></i> Bookings
                                </a>
                                <a href="{{ route('organiser.payments.pending') }}" class="nav-link-luxury {{ request()->routeIs('organiser.payments.*') ? 'active' : '' }}">
                                    <i class="fas fa-money-bill-wave"></i> Payments
                                </a>
                            </div>
                        @else
                            <div class="hidden md:flex space-x-1">
                                <a href="{{ route('user.events.index') }}" class="nav-link-luxury {{ request()->routeIs('user.events.*') ? 'active' : '' }}">
                                    <i class="fas fa-calendar-alt"></i> Events
                                </a>
                                <a href="{{ route('user.bookings.history') }}" class="nav-link-luxury {{ request()->routeIs('user.bookings.*') ? 'active' : '' }}">
                                    <i class="fas fa-ticket-alt"></i> My Bookings
                                </a>
                            </div>
                        @endif
                    @endauth
                </div>
                <div class="flex items-center space-x-2 sm:space-x-4">
                    @auth
                        <div class="hidden sm:flex items-center space-x-3">
                            <div class="text-right hidden lg:block">
                                <div class="text-sm font-semibold text-white">{{ auth()->user()->name }}</div>
                                <div class="text-xs text-gray-400">{{ auth()->user()->role->name }}</div>
                            </div>
                            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-gradient-to-r from-amber-400 to-yellow-400 flex items-center justify-center text-slate-900 font-bold shadow-lg text-sm sm:text-base">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <form action="{{ route('logout') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-red-400 hover:text-red-300 font-semibold transition-colors hidden lg:inline">
                                    <i class="fas fa-sign-out-alt"></i> Logout
                                </button>
                                <button type="submit" class="text-red-400 hover:text-red-300 font-semibold transition-colors lg:hidden" title="Logout">
                                    <i class="fas fa-sign-out-alt"></i>
                                </button>
                            </form>
                        </div>
                        <!-- Mobile Menu Button -->
                        <button id="mobile-menu-btn" class="md:hidden text-white hover:text-amber-400 transition-colors p-2">
                            <i class="fas fa-bars text-2xl"></i>
                        </button>
                    @else
                        <a href="{{ route('register') }}" class="btn-secondary-luxury text-xs sm:text-sm px-2 sm:px-4 py-1 sm:py-2">
                            <i class="fas fa-user-plus"></i> <span class="hidden sm:inline">Register</span>
                        </a>
                        <a href="{{ route('login') }}" class="btn-primary-luxury text-xs sm:text-sm px-2 sm:px-4 py-1 sm:py-2">
                            <i class="fas fa-sign-in-alt"></i> <span class="hidden sm:inline">Login</span>
                        </a>
                    @endauth
                </div>
            </div>
            
            <!-- Mobile Menu -->
            @auth
            <div id="mobile-menu" class="md:hidden hidden pb-4">
                <div class="flex flex-col space-y-2">
                    <!-- Mobile User Info -->
                    <div class="flex items-center space-x-3 px-4 py-3 bg-slate-700/50 rounded-lg mb-2">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-r from-amber-400 to-yellow-400 flex items-center justify-center text-slate-900 font-bold shadow-lg">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div>
                            <div class="text-sm font-semibold text-white">{{ auth()->user()->name }}</div>
                            <div class="text-xs text-gray-400">{{ auth()->user()->role->name }}</div>
                        </div>
                    </div>
                    
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="nav-link-mobile {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="fas fa-tachometer-alt w-6"></i> Dashboard
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="nav-link-mobile {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                            <i class="fas fa-users w-6"></i> Users
                        </a>
                        <a href="{{ route('admin.organisers.index') }}" class="nav-link-mobile {{ request()->routeIs('admin.organisers.*') ? 'active' : '' }}">
                            <i class="fas fa-user-tie w-6"></i> Organisers
                        </a>
                        <a href="{{ route('admin.events.index') }}" class="nav-link-mobile {{ request()->routeIs('admin.events.*') ? 'active' : '' }}">
                            <i class="fas fa-calendar-alt w-6"></i> Events
                        </a>
                        <a href="{{ route('admin.venues.index') }}" class="nav-link-mobile {{ request()->routeIs('admin.venues.*') || request()->routeIs('admin.seat-categories.*') ? 'active' : '' }}">
                            <i class="fas fa-building w-6"></i> Venues
                        </a>
                        <a href="{{ route('admin.event-categories.index') }}" class="nav-link-mobile {{ request()->routeIs('admin.event-categories.*') ? 'active' : '' }}">
                            <i class="fas fa-tags w-6"></i> Categories
                        </a>
                        <a href="{{ route('admin.seats.index') }}" class="nav-link-mobile {{ request()->routeIs('admin.seats.*') ? 'active' : '' }}">
                            <i class="fas fa-chair w-6"></i> Seats
                        </a>
                        <a href="{{ route('admin.transactions.index') }}" class="nav-link-mobile {{ request()->routeIs('admin.transactions.*') ? 'active' : '' }}">
                            <i class="fas fa-credit-card w-6"></i> Transactions
                        </a>
                        <a href="{{ route('admin.bookings.index') }}" class="nav-link-mobile {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">
                            <i class="fas fa-ticket-alt w-6"></i> Bookings
                        </a>
                    @elseif(auth()->user()->isOrganiser())
                        <a href="{{ route('organiser.events.index') }}" class="nav-link-mobile {{ request()->routeIs('organiser.events.*') ? 'active' : '' }}">
                            <i class="fas fa-calendar-alt w-6"></i> My Events
                        </a>
                        <a href="{{ route('organiser.show-timings.index') }}" class="nav-link-mobile {{ request()->routeIs('organiser.show-timings.*') ? 'active' : '' }}">
                            <i class="fas fa-clock w-6"></i> Show Timings
                        </a>
                        <a href="{{ route('organiser.venues.index') }}" class="nav-link-mobile {{ request()->routeIs('organiser.venues.*') ? 'active' : '' }}">
                            <i class="fas fa-building w-6"></i> Venues
                        </a>
                        <a href="{{ route('organiser.bookings.index') }}" class="nav-link-mobile {{ request()->routeIs('organiser.bookings.*') ? 'active' : '' }}">
                            <i class="fas fa-ticket-alt w-6"></i> Bookings
                        </a>
                        <a href="{{ route('organiser.payments.pending') }}" class="nav-link-mobile {{ request()->routeIs('organiser.payments.*') ? 'active' : '' }}">
                            <i class="fas fa-money-bill-wave w-6"></i> Payments
                        </a>
                    @else
                        <a href="{{ route('user.events.index') }}" class="nav-link-mobile {{ request()->routeIs('user.events.*') ? 'active' : '' }}">
                            <i class="fas fa-calendar-alt w-6"></i> Events
                        </a>
                        <a href="{{ route('user.bookings.history') }}" class="nav-link-mobile {{ request()->routeIs('user.bookings.*') ? 'active' : '' }}">
                            <i class="fas fa-ticket-alt w-6"></i> My Bookings
                        </a>
                    @endif
                    
                    <!-- Mobile Logout -->
                    <form action="{{ route('logout') }}" method="POST" class="mt-2">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-3 text-red-400 hover:text-red-300 hover:bg-red-500/10 font-semibold transition-all rounded-lg">
                            <i class="fas fa-sign-out-alt w-6"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
            @endauth
        </div>
    </nav>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="container mx-auto px-4 mt-6">
            <div class="bg-gradient-to-r from-green-500/20 to-emerald-500/20 border-l-4 border-green-400 text-green-100 p-5 rounded-xl shadow-xl flex items-center justify-between animate-fade-in backdrop-blur">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-4 text-2xl text-green-400"></i>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.parentElement.remove()" class="text-green-300 hover:text-green-100">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="container mx-auto px-4 mt-6">
            <div class="bg-gradient-to-r from-red-500/20 to-rose-500/20 border-l-4 border-red-400 text-red-100 p-5 rounded-xl shadow-xl flex items-center justify-between animate-fade-in backdrop-blur">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-4 text-2xl text-red-400"></i>
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
                <button onclick="this.parentElement.parentElement.remove()" class="text-red-300 hover:text-red-100">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="container mx-auto px-4 mt-6">
            <div class="bg-gradient-to-r from-red-500/20 to-rose-500/20 border-l-4 border-red-400 text-red-100 p-5 rounded-xl shadow-xl backdrop-blur">
                <div class="flex items-center mb-3">
                    <i class="fas fa-exclamation-triangle mr-3 text-xl text-red-400"></i>
                    <span class="font-semibold">Please fix the following errors:</span>
                </div>
                <ul class="list-disc list-inside ml-6 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
        @if(!request()->routeIs('home'))
        <div class="mb-4">
            <button type="button" onclick="window.history.back()" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-white rounded-lg font-semibold transition-all">
                <i class="fas fa-arrow-left mr-2"></i> Go Back
            </button>
        </div>
        @endif
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gradient-to-r from-slate-800 to-slate-700 shadow-2xl border-t border-purple-500/30 mt-16">
        <div class="container mx-auto px-4 py-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-6">
                <div>
                    <h3 class="text-xl font-bold bg-gradient-to-r from-amber-400 to-yellow-300 text-transparent bg-clip-text mb-3">EventBook</h3>
                    <p class="text-gray-400 text-sm">Experience Luxury Jain Religious Events</p>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-3">Quick Links</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="{{ route('about') }}" class="hover:text-amber-400 transition-colors">About Us</a></li>
                        <li><a href="{{ route('contact') }}" class="hover:text-amber-400 transition-colors">Contact</a></li>
                        <li><a href="{{ route('privacy') }}" class="hover:text-amber-400 transition-colors">Privacy</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-3">Follow Us</h4>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-amber-400 transition-colors text-lg"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="text-gray-400 hover:text-amber-400 transition-colors text-lg"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-gray-400 hover:text-amber-400 transition-colors text-lg"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
            <div class="border-t border-slate-600 pt-6 text-center text-gray-400 text-sm">
                <p>&copy; {{ date('Y') }} Experience Luxury Jain Religious Events. All rights reserved.</p>
                <p class="mt-2">Crafted with <i class="fas fa-heart text-red-400"></i> for premium experiences</p>
            </div>
        </div>
    </footer>

    <script>
        // Mobile Menu Toggle
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuBtn = document.getElementById('mobile-menu-btn');
            const mobileMenu = document.getElementById('mobile-menu');
            
            if (mobileMenuBtn && mobileMenu) {
                mobileMenuBtn.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                    const icon = this.querySelector('i');
                    if (mobileMenu.classList.contains('hidden')) {
                        icon.classList.remove('fa-times');
                        icon.classList.add('fa-bars');
                    } else {
                        icon.classList.remove('fa-bars');
                        icon.classList.add('fa-times');
                    }
                });
            }
        });
    </script>

    <style>
        .nav-link-luxury {
            @apply text-gray-300 hover:text-amber-400 font-medium px-4 py-2 rounded-lg transition-all duration-200 relative group;
        }
        .nav-link-luxury::after {
            @apply absolute bottom-0 left-0 w-0 h-0.5 bg-gradient-to-r from-amber-400 to-yellow-300 group-hover:w-full transition-all duration-300;
            content: '';
        }
        .nav-link-luxury.active {
            @apply text-amber-400;
        }
        .nav-link-luxury.active::after {
            @apply w-full;
        }
        .nav-link-mobile {
            @apply flex items-center text-gray-300 hover:text-amber-400 hover:bg-slate-700/50 font-medium px-4 py-3 rounded-lg transition-all duration-200;
        }
        .nav-link-mobile.active {
            @apply text-amber-400 bg-slate-700/50 border-l-4 border-amber-400;
        }
        .btn-primary-luxury {
            @apply bg-gradient-to-r from-amber-400 to-yellow-400 text-slate-900 px-6 py-2 rounded-xl font-semibold hover:from-amber-300 hover:to-yellow-300 transition-all duration-200 inline-block;
        }
        .btn-secondary-luxury {
            @apply bg-slate-700/50 backdrop-blur text-gray-200 px-6 py-2 rounded-xl font-semibold hover:bg-slate-600 transition-all duration-200 border-2 border-gray-600 inline-block;
        }
        .btn-primary {
            @apply bg-gradient-to-r from-amber-400 to-yellow-400 text-slate-900 px-6 py-2 rounded-xl font-semibold hover:from-amber-300 hover:to-yellow-300 transition-all duration-200 inline-block;
        }
        .btn-secondary {
            @apply bg-slate-700/50 backdrop-blur text-gray-200 px-6 py-2 rounded-xl font-semibold hover:bg-slate-600 transition-all duration-200 border-2 border-gray-600 inline-block;
        }
        .btn-success {
            @apply bg-gradient-to-r from-emerald-500 to-green-600 text-white px-4 py-2 rounded-xl font-semibold hover:from-emerald-600 hover:to-green-700 transition-all duration-200 inline-block;
        }
        .btn-danger {
            @apply bg-gradient-to-r from-red-500 to-rose-600 text-white px-4 py-2 rounded-xl font-semibold hover:from-red-600 hover:to-rose-700 transition-all duration-200 inline-block;
        }
        .btn-primary-luxury,
        .btn-secondary-luxury,
        .btn-primary,
        .btn-secondary,
        .btn-success,
        .btn-danger {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            position: relative;
            transform: translateY(0);
            box-shadow: 0 4px 0 rgba(15, 23, 42, 0.7), 0 10px 18px rgba(2, 6, 23, 0.35);
        }
        .btn-primary-luxury:hover,
        .btn-secondary-luxury:hover,
        .btn-primary:hover,
        .btn-secondary:hover,
        .btn-success:hover,
        .btn-danger:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 0 rgba(15, 23, 42, 0.75), 0 14px 22px rgba(2, 6, 23, 0.4);
        }
        .btn-primary-luxury:active,
        .btn-secondary-luxury:active,
        .btn-primary:active,
        .btn-secondary:active,
        .btn-success:active,
        .btn-danger:active {
            transform: translateY(2px);
            box-shadow: 0 2px 0 rgba(15, 23, 42, 0.75), 0 6px 10px rgba(2, 6, 23, 0.25);
        }
        .card {
            @apply bg-gradient-to-br from-slate-800/40 to-slate-900/40 backdrop-blur-md rounded-xl shadow-2xl p-6 hover:shadow-3xl transition-all duration-300 border border-slate-700/50 hover:border-amber-500/30;
        }
        .card-luxury {
            @apply bg-gradient-to-br from-slate-800/40 to-slate-900/40 backdrop-blur-md rounded-2xl shadow-2xl p-8 hover:shadow-3xl transition-all duration-300 border border-slate-700/50 hover:border-amber-500/50;
        }
        .input-field {
            @apply w-full px-4 py-3 bg-slate-700/50 backdrop-blur border-2 border-slate-600 text-white placeholder-gray-400 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all duration-200;
        }
        .label {
            @apply block text-gray-200 font-semibold mb-2;
        }
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fade-in 0.3s ease-out;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        .animate-float {
            animation: float 3s ease-in-out infinite;
        }
        .stat-card {
            @apply bg-gradient-to-br from-slate-800/60 to-slate-900/60 backdrop-blur-xl rounded-2xl p-8 border border-slate-700/50 hover:border-amber-500/30 transition-all duration-300;
        }
        .fa-solid{
                color: floralwhite;
        }
        a {
            color: white; 
                 }
        
        .space-x-1{
            gap: 10px;
        }

    </style>

@stack('scripts')
</body>
</html>
