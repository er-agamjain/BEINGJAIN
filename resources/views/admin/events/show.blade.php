@extends('layouts.app')

@section('title', 'View Event - ' . $event->title)

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold text-white mb-2" style="font-family: 'Playfair Display', serif;">
                    <i class="fas fa-eye text-amber-400"></i> Event Details
                </h1>
                <p class="text-gray-400">Viewing: {{ $event->title }}</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.events.edit', $event) }}" class="px-5 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-lg font-semibold transition">
                    <i class="fas fa-edit mr-2"></i>Edit
                </a>
                <a href="{{ route('admin.events.index') }}" class="px-5 py-2 bg-slate-700 hover:bg-slate-600 text-white rounded-lg font-medium transition">
                    <i class="fas fa-arrow-left mr-2"></i>Back
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/30 rounded-lg text-emerald-300 flex items-center gap-2">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Event Image -->
                @if($event->image)
                    <div class="card-luxury rounded-lg overflow-hidden">
                        <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}" class="w-full h-64 object-cover">
                    </div>
                @else
                    <div class="card-luxury rounded-lg h-64 flex items-center justify-center">
                        <i class="fas fa-calendar-alt text-amber-400 text-7xl opacity-40"></i>
                    </div>
                @endif

                <!-- Basic Info -->
                <div class="card-luxury rounded-lg p-6">
                    <h2 class="text-2xl font-bold text-white mb-4">{{ $event->title }}</h2>
                    <p class="text-gray-300 leading-relaxed mb-4">{{ $event->description }}</p>

                    @if($event->terms_conditions)
                        <div class="mt-4 p-4 bg-slate-700/50 rounded-lg">
                            <h3 class="text-amber-400 font-semibold mb-2">Terms & Conditions</h3>
                            <p class="text-gray-300 text-sm">{{ $event->terms_conditions }}</p>
                        </div>
                    @endif
                </div>

                <!-- Tickets -->
                <div class="card-luxury rounded-lg p-6">
                    <h2 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                        <i class="fas fa-ticket-alt text-amber-400"></i> Tickets / Seats
                    </h2>
                    @if($event->tickets->isEmpty())
                        <p class="text-gray-400 text-sm">No tickets added yet.</p>
                    @else
                        <div class="space-y-3">
                            @foreach($event->tickets as $ticket)
                                <div class="flex items-center justify-between bg-slate-700/50 rounded-lg px-4 py-3">
                                    <div>
                                        <p class="text-white font-semibold">{{ $ticket->name }}</p>
                                        @if($ticket->description)
                                            <p class="text-gray-400 text-xs mt-0.5">{{ $ticket->description }}</p>
                                        @endif
                                        <span class="inline-block mt-1 px-2 py-0.5 text-xs rounded-full {{ $ticket->ticket_type === 'free' ? 'bg-emerald-500/20 text-emerald-300' : 'bg-amber-500/20 text-amber-300' }}">
                                            {{ ucfirst($ticket->ticket_type) }}
                                        </span>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-white font-bold">{{ $ticket->ticket_type === 'free' ? 'Free' : '₹' . number_format($ticket->price, 2) }}</p>
                                        <p class="text-gray-400 text-xs">{{ $ticket->getAvailableQuantity() }} / {{ $ticket->quantity }} available</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Show Timings -->
                <div class="card-luxury rounded-lg p-6">
                    <h2 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                        <i class="fas fa-clock text-amber-400"></i> Show Timings
                    </h2>
                    @if($event->showTimings->isEmpty())
                        <p class="text-gray-400 text-sm">No show timings added yet.</p>
                    @else
                        <div class="space-y-3">
                            @foreach($event->showTimings as $timing)
                                <div class="flex items-center justify-between bg-slate-700/50 rounded-lg px-4 py-3">
                                    <div>
                                        <p class="text-white font-semibold">
                                            {{ \Carbon\Carbon::parse($timing->show_date_time)->format('M d, Y') }}
                                            <span class="text-amber-400 ml-1">{{ \Carbon\Carbon::parse($timing->show_date_time)->format('h:i A') }}</span>
                                        </p>
                                        @if($timing->notes)
                                            <p class="text-gray-400 text-xs mt-0.5">{{ $timing->notes }}</p>
                                        @endif
                                        <span class="inline-block mt-1 px-2 py-0.5 text-xs rounded-full
                                            {{ $timing->status === 'active' ? 'bg-emerald-500/20 text-emerald-300' : 'bg-red-500/20 text-red-300' }}">
                                            {{ ucfirst($timing->status) }}
                                        </span>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-white font-bold">{{ $timing->duration_minutes }} min</p>
                                        <p class="text-gray-400 text-xs">{{ $timing->available_seats - $timing->booked_seats }} / {{ $timing->available_seats }} seats</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Status -->
                <div class="card-luxury rounded-lg p-6">
                    <h3 class="text-amber-400 font-bold mb-4 text-lg">Event Status</h3>
                    @if($event->status === 'pending')
                        <span class="inline-flex items-center gap-2 px-4 py-2 bg-yellow-500/20 text-yellow-300 rounded-lg font-semibold border border-yellow-500/30">
                            <i class="fas fa-clock"></i> Pending
                        </span>
                        <div class="mt-4 flex gap-2">
                            <form action="{{ route('admin.events.approve', $event) }}" method="POST" class="flex-1">
                                @csrf @method('PUT')
                                <button class="w-full py-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg text-sm font-semibold transition">
                                    <i class="fas fa-check mr-1"></i> Approve
                                </button>
                            </form>
                            <form action="{{ route('admin.events.reject', $event) }}" method="POST" class="flex-1">
                                @csrf @method('PUT')
                                <button class="w-full py-2 bg-red-600 hover:bg-red-500 text-white rounded-lg text-sm font-semibold transition">
                                    <i class="fas fa-times mr-1"></i> Reject
                                </button>
                            </form>
                        </div>
                    @elseif($event->status === 'approved' || $event->status === 'published')
                        <span class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-500/20 text-emerald-300 rounded-lg font-semibold border border-emerald-500/30">
                            <i class="fas fa-check-circle"></i> Published
                        </span>
                    @elseif($event->status === 'draft')
                        <span class="inline-flex items-center gap-2 px-4 py-2 bg-slate-500/20 text-slate-300 rounded-lg font-semibold border border-slate-500/30">
                            <i class="fas fa-file-alt"></i> Draft
                        </span>
                    @elseif($event->status === 'cancelled')
                        <span class="inline-flex items-center gap-2 px-4 py-2 bg-orange-500/20 text-orange-300 rounded-lg font-semibold border border-orange-500/30">
                            <i class="fas fa-times-circle"></i> Cancelled
                            </span>
                </div>
                @endif

                <!-- Event Meta -->
                <div class="card-luxury rounded-lg p-6 space-y-4">
                    <h3 class="text-amber-400 font-bold text-lg">Event Info</h3>

                    <div class="flex items-start gap-3">
                        <i class="fas fa-user-tie text-purple-400 mt-1"></i>
                        <div>
                            <p class="text-gray-400 text-xs">Organiser</p>
                            <p class="text-white font-medium">{{ $event->organiser->name }}</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <i class="fas fa-map-marker-alt text-blue-400 mt-1"></i>
                        <div>
                            <p class="text-gray-400 text-xs">Location</p>
                            <p class="text-white font-medium">{{ $event->city->name ?? $event->location ?? '—' }}</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <i class="fas fa-calendar text-amber-400 mt-1"></i>
                        <div>
                            <p class="text-gray-400 text-xs">Event Date</p>
                            <p class="text-white font-medium">{{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <i class="fas fa-clock text-emerald-400 mt-1"></i>
                        <div>
                            <p class="text-gray-400 text-xs">Time</p>
                            <p class="text-white font-medium">
                                {{ \Carbon\Carbon::parse($event->start_time)->format('h:i A') }} – {{ \Carbon\Carbon::parse($event->end_time)->format('h:i A') }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <i class="fas fa-tag text-emerald-400 mt-1"></i>
                        <div>
                            <p class="text-gray-400 text-xs">Price</p>
                            <p class="text-white font-medium">{{ $event->is_free ? 'Free' : '₹' . number_format($event->base_price, 2) }}</p>
                        </div>
                    </div>

                    @if($event->eventCategory)
                        <div class="flex items-start gap-3">
                            <i class="fas fa-folder text-yellow-400 mt-1"></i>
                            <div>
                                <p class="text-gray-400 text-xs">Category</p>
                                 <p class="text-white font-medium">{{ $event->eventCategory->category_name }}</p>
                            </div>
                        </div>
                    @endif

                    @if($event->community)
                        <div class="flex items-start gap-3">
                            <i class="fas fa-users text-pink-400 mt-1"></i>
                            <div>
                                <p class="text-gray-400 text-xs">Community</p>
                                <p class="text-white font-medium">{{ $event->community }}</p>
                            </div>
                        </div>
                    @endif

                    @if($event->tags)
                        <div class="flex items-start gap-3">
                            <i class="fas fa-hashtag text-cyan-400 mt-1"></i>
                            <div>
                                <p class="text-gray-400 text-xs">Tags</p>
                                <p class="text-white font-medium">{{ $event->tags }}</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Danger Zone -->
                <div class="card-luxury rounded-lg p-6 border border-red-500/30">
                    <h3 class="text-red-400 font-bold mb-4">Danger Zone</h3>
                    <form action="{{ route('admin.events.destroy', $event) }}" method="POST">
                        @csrf @method('DELETE')
                        <button type="submit" onclick="return confirm('Permanently delete this event?')"
                            class="w-full py-2 bg-red-600 hover:bg-red-500 text-white rounded-lg font-semibold transition">
                            <i class="fas fa-trash mr-2"></i> Delete Event
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card-luxury {
    background: linear-gradient(135deg, rgba(15, 23, 42, 0.8) 0%, rgba(30, 41, 59, 0.8) 100%);
}
</style>

@endsection
