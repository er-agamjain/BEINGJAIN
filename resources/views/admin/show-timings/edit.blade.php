@extends('layouts.app')

@section('title', 'Edit Show Timing')

@section('content')
<div class="py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-white mb-1" style="font-family:'Playfair Display',serif;">
                    <i class="fas fa-clock text-amber-400 mr-2"></i>Edit Show Timing
                </h1>
                <p class="text-gray-400 text-sm">{{ $showTiming->event->title ?? 'Event' }} | {{ optional($showTiming->show_date_time)->format('M d, Y h:i A') }}</p>
            </div>
            <a href="{{ route('admin.events.edit', $showTiming->event) }}" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-white font-medium rounded-lg transition">
                <i class="fas fa-arrow-left mr-2"></i>Back to Event
            </a>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/30 rounded-lg text-emerald-300 flex items-center gap-2">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 p-4 bg-red-500/10 border border-red-500/30 rounded-lg text-red-300">
                <ul class="list-disc list-inside space-y-1 text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card-luxury rounded-lg p-6 mb-8">
            <h2 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
                <i class="fas fa-calendar-alt text-amber-400"></i> Show Details
            </h2>

            <form method="POST" action="{{ route('admin.show-timings.update', $showTiming) }}" class="space-y-5">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-amber-400 font-bold mb-1 text-sm">Event</label>
                        <input type="text" value="{{ $showTiming->event->title ?? 'N/A' }}" class="w-full px-4 py-2 bg-slate-800 border border-slate-600 text-gray-400 rounded-lg text-sm cursor-not-allowed" disabled>
                    </div>
                    <div>
                        <label class="block text-amber-400 font-bold mb-1 text-sm">Venue</label>
                        <input type="text" value="{{ $showTiming->venue->name ?? 'N/A' }}" class="w-full px-4 py-2 bg-slate-800 border border-slate-600 text-gray-400 rounded-lg text-sm cursor-not-allowed" disabled>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-amber-400 font-bold mb-1 text-sm">Show Date & Time <span class="text-red-400">*</span></label>
                        <input type="datetime-local" name="show_date_time" value="{{ old('show_date_time', optional($showTiming->show_date_time)->format('Y-m-d\TH:i')) }}" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 transition" required>
                    </div>
                    <div>
                        <label class="block text-amber-400 font-bold mb-1 text-sm">Duration (minutes) <span class="text-red-400">*</span></label>
                        <input type="number" name="duration_minutes" min="30" value="{{ old('duration_minutes', $showTiming->duration_minutes) }}" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 transition" required>
                    </div>
                    <div>
                        <label class="block text-amber-400 font-bold mb-1 text-sm">Available Seats <span class="text-red-400">*</span></label>
                        <input type="number" name="available_seats" min="1" value="{{ old('available_seats', $showTiming->available_seats) }}" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 transition" required>
                    </div>
                    <div>
                        <label class="block text-amber-400 font-bold mb-1 text-sm">Status <span class="text-red-400">*</span></label>
                        <select name="status" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 transition" required>
                            <option value="scheduled" {{ old('status', $showTiming->status) === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                            <option value="cancelled" {{ old('status', $showTiming->status) === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            <option value="completed" {{ old('status', $showTiming->status) === 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-amber-400 font-bold mb-1 text-sm">Notes</label>
                    <textarea name="notes" rows="3" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">{{ old('notes', $showTiming->notes) }}</textarea>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="submit" class="px-6 py-2 bg-gradient-to-r from-emerald-500 to-teal-500 hover:shadow-lg hover:shadow-emerald-500/20 text-white font-semibold rounded-lg transition">
                        <i class="fas fa-save mr-2"></i>Update Show Timing
                    </button>
                    <a href="{{ route('admin.events.edit', $showTiming->event) }}" class="px-6 py-2 bg-slate-700 hover:bg-slate-600 text-gray-300 border border-slate-600 font-medium rounded-lg transition">
                        <i class="fas fa-times mr-2"></i>Cancel
                    </a>
                </div>
            </form>
        </div>

        @php
            $usedSeats = $tickets->sum('quantity');
            $freeSeats = $showTiming->available_seats - $usedSeats;
            $pct = $showTiming->available_seats > 0 ? min(100, round($usedSeats / $showTiming->available_seats * 100)) : 0;
        @endphp

        <div class="card-luxury rounded-lg p-6">
            <div class="flex items-center justify-between mb-2">
                <h2 class="text-xl font-bold text-white flex items-center gap-2">
                    <i class="fas fa-ticket-alt text-amber-400"></i> Ticket Categories
                </h2>
                <span class="{{ $freeSeats <= 0 ? 'text-red-400' : 'text-emerald-400' }} text-sm font-semibold">{{ $usedSeats }} / {{ $showTiming->available_seats }} seats allocated</span>
            </div>

            <div class="mb-6">
                <div class="w-full bg-slate-700 rounded-full h-2">
                    <div class="h-2 rounded-full transition-all {{ $pct >= 100 ? 'bg-red-500' : ($pct >= 80 ? 'bg-amber-400' : 'bg-emerald-500') }} js-capacity-bar" data-progress="{{ $pct }}"></div>
                </div>
            </div>

            @if($tickets->isNotEmpty())
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-6">
                    @foreach($tickets as $ticket)
                        <div class="flex items-center justify-between bg-slate-700/60 rounded-xl px-4 py-3 border border-slate-600/40">
                            <div>
                                <p class="text-white font-semibold">{{ $ticket->name }}</p>
                                <p class="text-gray-400 text-xs mt-0.5">
                                    {{ $ticket->ticket_type === 'free' ? 'Free' : '₹'.number_format($ticket->price, 2) }}
                                    | <span class="text-amber-300">{{ $ticket->quantity }} seats</span>
                                    @if($ticket->description) | {{ $ticket->description }} @endif
                                </p>
                            </div>
                            <form action="{{ route('admin.show-timings.tickets.destroy', [$showTiming, $ticket]) }}" method="POST" class="ml-3 flex-shrink-0">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Remove this ticket category?')" class="p-2 text-red-400 hover:text-red-300 hover:bg-red-500/10 rounded-lg transition text-sm">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-400 text-sm mb-6 italic">No ticket categories added yet for this show.</p>
            @endif

            @if($freeSeats > 0)
                <div class="border-t border-slate-600 pt-5">
                    <h3 class="text-amber-400 font-semibold mb-3"><i class="fas fa-plus-circle mr-1"></i>Add Ticket Category <span class="text-gray-400 font-normal text-sm ml-2">(max {{ $freeSeats }} seats remaining)</span></h3>
                    <form action="{{ route('admin.show-timings.tickets.store', $showTiming) }}" method="POST" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-gray-300 text-sm mb-1">Category Name <span class="text-red-400">*</span></label>
                                <input type="text" name="name" value="{{ old('name') }}" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 transition" required>
                            </div>
                            <div>
                                <label class="block text-gray-300 text-sm mb-1">Description</label>
                                <input type="text" name="description" value="{{ old('description') }}" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-gray-300 text-sm mb-1">Type <span class="text-red-400">*</span></label>
                                <select name="ticket_type" id="adminTimingTicketType" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 transition" required>
                                    <option value="paid">Paid</option>
                                    <option value="free" {{ old('ticket_type') === 'free' ? 'selected' : '' }}>Free</option>
                                </select>
                            </div>
                            <div id="adminTimingTicketPriceWrap">
                                <label class="block text-gray-300 text-sm mb-1">Price (₹)</label>
                                <input type="number" name="price" id="adminTimingTicketPrice" step="0.01" min="0" value="{{ old('price') }}" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
                            </div>
                            <div>
                                <label class="block text-gray-300 text-sm mb-1">Quantity (seats) <span class="text-red-400">*</span></label>
                                <input type="number" name="quantity" min="1" max="{{ $freeSeats }}" value="{{ old('quantity') }}" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 transition" required>
                            </div>
                        </div>
                        <button type="submit" class="px-6 py-2 bg-gradient-to-r from-emerald-500 to-teal-500 hover:shadow-lg hover:shadow-emerald-500/20 text-white font-semibold rounded-lg transition">
                            <i class="fas fa-plus mr-2"></i>Add Ticket Category
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.card-luxury {
    background: linear-gradient(135deg, rgba(15,23,42,0.8) 0%, rgba(30,41,59,0.8) 100%);
    border: 1px solid rgba(148,113,113,0.2);
    box-shadow: 0 20px 25px -5px rgba(0,0,0,0.3);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.js-capacity-bar').forEach(function (bar) {
        bar.style.width = `${bar.dataset.progress}%`;
    });

    const typeSelect = document.getElementById('adminTimingTicketType');
    const priceInput = document.getElementById('adminTimingTicketPrice');
    const priceWrap = document.getElementById('adminTimingTicketPriceWrap');

    if (typeSelect && priceInput && priceWrap) {
        const togglePrice = () => {
            const isFree = typeSelect.value === 'free';
            priceInput.disabled = isFree;
            priceWrap.style.opacity = isFree ? '0.5' : '1';
            if (isFree) priceInput.value = '0';
        };
        typeSelect.addEventListener('change', togglePrice);
        togglePrice();
    }
});
</script>
@endsection
