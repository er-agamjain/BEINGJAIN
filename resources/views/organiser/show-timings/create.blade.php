@extends('layouts.app')

@section('title', 'Add Show Timing')

@section('content')
<div class="py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold text-white mb-2">
                    <i class="fas fa-clock text-emerald-400 mr-3"></i>Add Show Timing
                </h1>
                <p class="text-gray-400">Schedule a new show for your event</p>
            </div>
            <a href="{{ route('organiser.show-timings.index') }}" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-white font-medium rounded-lg transition-colors">
                <i class="fas fa-arrow-left mr-2"></i> Back
            </a>
        </div>

        <form method="POST" action="{{ route('organiser.show-timings.store') }}" class="space-y-6">
            @csrf

            <!-- Event & Venue Selection -->
            <div class="card-luxury rounded-lg p-6">
                <h2 class="text-2xl font-bold text-amber-400 mb-6 flex items-center">
                    <i class="fas fa-info-circle text-emerald-400 mr-3"></i> Event & Venue
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-amber-400 font-bold mb-2">Select Event <span class="text-red-400">*</span></label>
                        <select name="event_id" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 transition" required>
                            <option value="" class="bg-slate-800">Choose an event</option>
                            @foreach($events as $event)
                                <option value="{{ $event->id }}" {{ old('event_id') == $event->id ? 'selected' : '' }} class="bg-slate-800">
                                    {{ $event->title }} ({{ $event->eventCategory->name ?? 'N/A' }})
                                </option>
                            @endforeach
                        </select>
                        @error('event_id')<span class="text-red-400 text-sm">{{ $message }}</span>@enderror
                    </div>

                    <div>
                        <label class="block text-amber-400 font-bold mb-2">Select Venue <span class="text-red-400">*</span></label>
                        <select name="venue_id" id="venueSelect" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 transition" required>
                            <option value="" class="bg-slate-800">Choose a venue</option>
                            @foreach($venues as $venue)
                                <option value="{{ $venue->id }}" data-seat-categories="{{ $venue->seat_categories_count }}" {{ old('venue_id') == $venue->id ? 'selected' : '' }} class="bg-slate-800">
                                    {{ $venue->name }} ({{ $venue->city->name ?? 'N/A' }})
                                </option>
                            @endforeach
                        </select>
                        @error('venue_id')<span class="text-red-400 text-sm">{{ $message }}</span>@enderror
                        <!-- Warning shown when venue has no seat categories -->
                        <div id="noSeatCategoriesWarning" class="hidden mt-2 p-3 bg-amber-500/10 border border-amber-400/40 rounded-lg text-amber-200 text-sm">
                            <i class="fas fa-exclamation-triangle mr-2 text-amber-400"></i>
                            <strong>No seat categories configured for this venue.</strong>
                            Seats cannot be generated without categories.
                            <a href="#" id="seatCategoryLink" class="underline text-amber-300 hover:text-amber-100 ml-1">Add seat categories →</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Show Timing Details -->
            <div class="card-luxury rounded-lg p-6">
                <h2 class="text-2xl font-bold text-amber-400 mb-6 flex items-center">
                    <i class="fas fa-calendar-alt text-emerald-400 mr-3"></i> Show Details
                </h2>

                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-amber-400 font-bold mb-2">Show Date & Time <span class="text-red-400">*</span></label>
                            <input type="datetime-local" name="show_date_time" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 transition" 
                                   value="{{ old('show_date_time') }}" required>
                            <small class="text-gray-400 mt-1 block">Must be in the future</small>
                            @error('show_date_time')<span class="text-red-400 text-sm">{{ $message }}</span>@enderror
                        </div>

                        <div>
                            <label class="block text-amber-400 font-bold mb-2">Duration (minutes) <span class="text-red-400">*</span></label>
                            <input type="number" name="duration_minutes" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 transition" 
                                   placeholder="90" value="{{ old('duration_minutes') }}" min="30" required>
                            <small class="text-gray-400 mt-1 block">Minimum 30 minutes</small>
                            @error('duration_minutes')<span class="text-red-400 text-sm">{{ $message }}</span>@enderror
                        </div>

                        <div>
                            <label class="block text-amber-400 font-bold mb-2">Available Seats <span class="text-red-400">*</span></label>
                            <input type="number" name="available_seats" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 transition" 
                                   placeholder="500" value="{{ old('available_seats') }}" min="1" required>
                            <small class="text-gray-400 mt-1 block">Total seats available for this show</small>
                            @error('available_seats')<span class="text-red-400 text-sm">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-amber-400 font-bold mb-2">Notes (Optional)</label>
                        <textarea name="notes" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 transition" 
                                  placeholder="Any special notes about this show..." rows="4">{{ old('notes') }}</textarea>
                        @error('notes')<span class="text-red-400 text-sm">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex gap-4 justify-end pt-6 border-t border-slate-600">
                <a href="{{ route('organiser.show-timings.index') }}" class="px-6 py-3 bg-slate-700 hover:bg-slate-600 text-white font-medium rounded-lg transition-colors">
                    <i class="fas fa-times mr-2"></i> Cancel
                </a>
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-emerald-500 to-teal-500 hover:shadow-lg hover:shadow-emerald-500/20 text-white font-semibold rounded-lg transition">
                    <i class="fas fa-plus mr-2"></i> Create Show Timing
                </button>
            </div>
        </form>

        @if($errors->any())
            <div class="mt-6 card-luxury rounded-lg border-l-4 border-red-500 p-4">
                <h3 class="text-red-400 font-bold mb-2">Validation Errors:</h3>
                <ul class="text-red-400 text-sm">
                    @foreach($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
</div>

<style>
    .card-luxury {
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.8) 0%, rgba(30, 41, 59, 0.8) 100%);
        border: 1px solid rgba(148, 113, 113, 0.2);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3);
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const venueSelect = document.getElementById('venueSelect');
    const warning = document.getElementById('noSeatCategoriesWarning');
    const seatCategoryLink = document.getElementById('seatCategoryLink');

    function checkVenueSeatCategories() {
        const selected = venueSelect.options[venueSelect.selectedIndex];
        const count = parseInt(selected.dataset.seatCategories ?? '-1');
        if (venueSelect.value && count === 0) {
            // Build the seat-categories URL for this venue
            seatCategoryLink.href = `/organiser/venues/${venueSelect.value}/seat-categories`;
            warning.classList.remove('hidden');
        } else {
            warning.classList.add('hidden');
        }
    }

    venueSelect.addEventListener('change', checkVenueSeatCategories);
    // Check on page load in case old() pre-selects a venue
    checkVenueSeatCategories();
});
</script>
@endsection
