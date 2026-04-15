@extends('layouts.app')

@section('title', 'Edit Event - ' . $event->title)

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold text-white mb-2" style="font-family: 'Playfair Display', serif;">
                    <i class="fas fa-edit text-amber-400"></i> Edit Event
                </h1>
                <p class="text-gray-400">Editing: {{ $event->title }}</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.events.show', $event) }}" class="px-5 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-lg font-medium transition">
                    <i class="fas fa-eye mr-2"></i>View
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

        @if($errors->any())
            <div class="mb-6 p-4 bg-red-500/10 border border-red-500/30 rounded-lg text-red-300">
                <ul class="list-disc list-inside space-y-1 text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Edit Form -->
            <div class="lg:col-span-2">
                <div class="card-luxury rounded-lg p-8">
                    <h2 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
                        <i class="fas fa-info-circle text-amber-400"></i> Event Details
                    </h2>

                    <form action="{{ route('admin.events.update', $event) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-amber-400 font-bold mb-2">Event Title <span class="text-red-400">*</span></label>
                            <input type="text" name="title" value="{{ old('title', $event->title) }}"
                                class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 transition" required>
                        </div>

                        <div>
                            <label class="block text-amber-400 font-bold mb-2">Description <span class="text-red-400">*</span></label>
                            <textarea name="description" rows="4"
                                class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 transition" required>{{ old('description', $event->description) }}</textarea>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-amber-400 font-bold mb-2">City</label>
                                <select name="city_id" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
                                    <option value="">Select City</option>
                                    @foreach($cities as $id => $name)
                                        <option value="{{ $id }}" {{ old('city_id', $event->city_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-amber-400 font-bold mb-2">Navigation Location
                                    <span class="text-gray-400 font-normal text-xs">(Google Maps link)</span>
                                </label>
                                <input type="url" name="navigation_location" value="{{ old('navigation_location', $event->navigation_location) }}" placeholder="https://maps.google.com/..."
                                    class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
                            </div>
                        </div>

                        <!--<div>
                            <label class="block text-amber-400 font-bold mb-2">Location <span class="text-red-400">*</span></label>
                            <input type="text" name="location" value="{{ old('location', $event->location) }}"
                                class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 transition" required>
                        </div>-->

                        <div>
                            <label class="block text-amber-400 font-bold mb-2">Event Category</label>
                            <select name="category_id" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
                                <option value="">Select a category</option>
                                @foreach($categories as $id => $name)
                                    <option value="{{ $id }}" {{ old('category_id', $event->category_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-amber-400 font-bold mb-2">Community</label>
                                <select name="community" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
                                    <option value="">Select Community</option>
                                    @foreach($communities as $community)
                                        <option value="{{ $community }}" {{ old('community', $event->community) == $community ? 'selected' : '' }}>{{ $community }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-amber-400 font-bold mb-2">Gacchh</label>
                                <select name="gacchh" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
                                    <option value="">Select Gacchh</option>
                                    @foreach($gacchhs as $gacchh)
                                        <option value="{{ $gacchh }}" {{ old('gacchh', $event->gacchh) == $gacchh ? 'selected' : '' }}>{{ $gacchh }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-amber-400 font-bold mb-2">Tags</label>
                                <select name="tags" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
                                    <option value="">Select Tags</option>
                                    @foreach($tagsList as $tag)
                                        <option value="{{ $tag }}" {{ old('tags', $event->tags) == $tag ? 'selected' : '' }}>{{ $tag }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-3">
                            <input type="checkbox" id="allDayEvent" name="all_day" value="1" {{ old('all_day', $event->all_day ?? false) ? 'checked' : '' }} class="w-4 h-4 rounded border-slate-600 bg-slate-700 text-emerald-500 focus:ring-emerald-500 cursor-pointer">
                            <label for="allDayEvent" class="text-amber-400 font-bold cursor-pointer select-none">All Day Event</label>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-amber-400 font-bold mb-2">Event Date <span class="text-red-400">*</span></label>
                                <input type="date" name="event_date" value="{{ old('event_date', \Carbon\Carbon::parse($event->event_date)->format('Y-m-d')) }}"
                                    class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 transition" required>
                            </div>
                            <div id="startTimeDiv">
                                <label class="block text-amber-400 font-bold mb-2">Start Time <span class="text-red-400" id="startTimeRequired">*</span></label>
                                <div class="flex gap-2 items-center">
                                    <input type="time" name="start_time" id="startTime" value="{{ old('start_time', \Carbon\Carbon::parse($event->start_time)->format('H:i')) }}"
                                        class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 transition" required>
                                    <span id="startTimeDisplay" class="text-gray-400 font-semibold min-w-16 text-center">--:-- --</span>
                                </div>
                            </div>
                            <div id="endTimeDiv">
                                <label class="block text-amber-400 font-bold mb-2">End Time <span class="text-red-400" id="endTimeRequired">*</span></label>
                                <div class="flex gap-2 items-center">
                                    <input type="time" name="end_time" id="endTime" value="{{ old('end_time', \Carbon\Carbon::parse($event->end_time)->format('H:i')) }}"
                                        class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 transition" required>
                                    <span id="endTimeDisplay" class="text-gray-400 font-semibold min-w-16 text-center">--:-- --</span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-amber-400 font-bold mb-2">Event Type <span class="text-red-400">*</span></label>
                            <div class="flex gap-6">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="is_free" value="0" {{ old('is_free', $event->is_free) == '0' ? 'checked' : '' }} class="w-4 h-4 text-amber-400">
                                    <span class="text-white">Paid Event</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="is_free" value="1" {{ old('is_free', $event->is_free) == '1' ? 'checked' : '' }} class="w-4 h-4 text-amber-400">
                                    <span class="text-white">Free Event</span>
                                </label>
                            </div>
                        </div>

                        <div id="priceDiv" class="{{ $event->is_free ? 'opacity-60' : '' }}">
                            <label class="block text-amber-400 font-bold mb-2">Base Price <span id="priceRequired" class="text-red-400 {{ $event->is_free ? 'hidden' : '' }}">*</span></label>
                            <input type="number" name="base_price" step="0.01" min="0" id="basePrice"
                                value="{{ old('base_price', $event->base_price) }}"
                                {{ $event->is_free ? 'disabled' : '' }}
                                class="w-full md:w-1/2 px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
                        </div>

                        <div>
                            <label class="block text-amber-400 font-bold mb-2">Event Image</label>
                            @if($event->image)
                                <div class="mb-3">
                                    <img src="{{ asset('storage/' . $event->image) }}" alt="Current image" class="h-32 w-32 object-cover rounded-lg border border-slate-600">
                                    <p class="text-gray-400 text-xs mt-1">Current image — upload a new one to replace</p>
                                </div>
                            @endif
                            <input type="file" name="image" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 transition" accept="image/*">
                        </div>

                        <div class="flex gap-4 pt-6 border-t border-slate-600">
                            <button type="submit" class="px-6 py-3 bg-gradient-to-r from-emerald-500 to-teal-500 hover:shadow-lg hover:shadow-emerald-500/20 text-white rounded-lg font-semibold transition">
                                <i class="fas fa-save mr-2"></i> Save Changes
                            </button>
                            <a href="{{ route('admin.events.show', $event) }}" class="px-6 py-3 bg-slate-700 hover:bg-slate-600 text-white rounded-lg font-medium transition">
                                <i class="fas fa-times mr-2"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tickets / Seats -->
            <div>
                <div class="card-luxury rounded-lg p-6">
                    <h2 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                        <i class="fas fa-ticket-alt text-amber-400"></i> Tickets / Seats
                    </h2>

                    <!-- Existing Tickets -->
                    @if($event->tickets->isNotEmpty())
                        <div class="space-y-2 mb-6">
                            @foreach($event->tickets as $ticket)
                                <div class="flex items-center justify-between bg-slate-700/60 rounded-lg px-4 py-3">
                                    <div>
                                        <p class="text-white font-semibold">{{ $ticket->name }}</p>
                                        <p class="text-gray-400 text-xs">
                                            {{ $ticket->ticket_type === 'free' ? 'Free' : '₹' . number_format($ticket->price, 2) }}
                                            &bull; {{ $ticket->getAvailableQuantity() }}/{{ $ticket->quantity }} available
                                        </p>
                                    </div>
                                    <form action="{{ route('admin.events.tickets.destroy', $ticket) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="submit" onclick="return confirm('Delete this ticket?')"
                                            class="p-2 text-red-400 hover:text-red-300 hover:bg-red-500/10 rounded-lg transition">
                                            <i class="fas fa-trash text-sm"></i>
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-400 text-sm mb-6">No tickets yet.</p>
                    @endif

                    <!-- Add Ticket Form -->
                    <div class="border-t border-slate-600 pt-5">
                        <h3 class="text-amber-400 font-semibold mb-3">Add New Ticket</h3>
                        <form action="{{ route('admin.events.tickets.store', $event) }}" method="POST" class="space-y-3">
                            @csrf
                            <div>
                                <label class="block text-gray-300 text-sm mb-1">Ticket Name <span class="text-red-400">*</span></label>
                                <input type="text" name="name" placeholder="e.g. General Admission"
                                    class="w-full px-3 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 transition" required>
                            </div>
                            <div>
                                <label class="block text-gray-300 text-sm mb-1">Description</label>
                                <input type="text" name="description" placeholder="Optional description"
                                    class="w-full px-3 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-gray-300 text-sm mb-1">Type <span class="text-red-400">*</span></label>
                                    <select name="ticket_type" id="ticketType"
                                        class="w-full px-3 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 transition" required>
                                        <option value="paid">Paid</option>
                                        <option value="free">Free</option>
                                    </select>
                                </div>
                                <div id="ticketPriceDiv">
                                    <label class="block text-gray-300 text-sm mb-1">Price (₹) <span class="text-red-400">*</span></label>
                                    <input type="number" name="price" step="0.01" min="0" id="ticketPrice" placeholder="0.00"
                                        class="w-full px-3 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 transition" required>
                                </div>
                            </div>
                            <div>
                                <label class="block text-gray-300 text-sm mb-1">Quantity <span class="text-red-400">*</span></label>
                                <input type="number" name="quantity" min="1" placeholder="Number of seats"
                                    class="w-full px-3 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 transition" required>
                            </div>
                            <button type="submit" class="w-full py-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg text-sm font-semibold transition mt-2">
                                <i class="fas fa-plus mr-1"></i> Add Ticket
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Show Timings -->
            <div>
                <div class="card-luxury rounded-lg p-6">
                    <h2 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                        <i class="fas fa-clock text-amber-400"></i> Show Timings
                    </h2>

                    <!-- Existing Timings -->
                    @if($event->showTimings->isNotEmpty())
                        <div class="space-y-2 mb-6">
                            @foreach($event->showTimings as $timing)
                                <div class="flex items-center justify-between bg-slate-700/60 rounded-lg px-4 py-3">
                                    <div>
                                        <p class="text-white font-semibold">
                                            {{ \Carbon\Carbon::parse($timing->show_date_time)->format('M d, Y') }}
                                            <span class="text-amber-400 ml-1">{{ \Carbon\Carbon::parse($timing->show_date_time)->format('h:i A') }}</span>
                                        </p>
                                        <p class="text-gray-400 text-xs">
                                            {{ $timing->duration_minutes }} min
                                            &bull; {{ $timing->available_seats }} seats
                                            @if($timing->notes) &bull; {{ $timing->notes }} @endif
                                        </p>
                                    </div>
                                    <form action="{{ route('admin.events.timings.destroy', $timing) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="submit" onclick="return confirm('Delete this timing?')"
                                            class="p-2 text-red-400 hover:text-red-300 hover:bg-red-500/10 rounded-lg transition">
                                            <i class="fas fa-trash text-sm"></i>
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-400 text-sm mb-6">No show timings yet.</p>
                    @endif

                    <!-- Add Timing Form -->
                    <div class="border-t border-slate-600 pt-5">
                        <h3 class="text-amber-400 font-semibold mb-3">Add Show Timing</h3>
                        <form action="{{ route('admin.events.timings.store', $event) }}" method="POST" class="space-y-3">
                            @csrf
                            <div>
                                <label class="block text-gray-300 text-sm mb-1">Date & Time <span class="text-red-400">*</span></label>
                                <input type="datetime-local" name="show_date_time"
                                    class="w-full px-3 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 transition" required>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-gray-300 text-sm mb-1">Duration (minutes) <span class="text-red-400">*</span></label>
                                    <input type="number" name="duration_minutes" min="1" placeholder="e.g. 120"
                                        class="w-full px-3 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 transition" required>
                                </div>
                                <div>
                                    <label class="block text-gray-300 text-sm mb-1">Available Seats <span class="text-red-400">*</span></label>
                                    <input type="number" name="available_seats" min="1" placeholder="e.g. 200"
                                        class="w-full px-3 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 transition" required>
                                </div>
                            </div>
                            <div>
                                <label class="block text-gray-300 text-sm mb-1">Notes</label>
                                <input type="text" name="notes" placeholder="Optional notes"
                                    class="w-full px-3 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
                            </div>
                            <button type="submit" class="w-full py-2 bg-amber-600 hover:bg-amber-500 text-white rounded-lg text-sm font-semibold transition mt-2">
                                <i class="fas fa-plus mr-1"></i> Add Show Timing
                            </button>
                        </form>
                    </div>
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

<script>
function formatTo12Hour(time24) {
    if (!time24) return '--:-- --';
    const [hours, minutes] = time24.split(':');
    const hour = parseInt(hours);
    const ampm = hour >= 12 ? 'PM' : 'AM';
    const hour12 = hour % 12 || 12;
    return `${String(hour12).padStart(2, '0')}:${minutes} ${ampm}`;
}

document.addEventListener('DOMContentLoaded', function() {
    // 12-hr time displays for start/end time
    const startInput = document.getElementById('startTime');
    const endInput = document.getElementById('endTime');
    const startDisplay = document.getElementById('startTimeDisplay');
    const endDisplay = document.getElementById('endTimeDisplay');
    const allDayCheckbox = document.getElementById('allDayEvent');
    const startTimeDiv = document.getElementById('startTimeDiv');
    const endTimeDiv = document.getElementById('endTimeDiv');
    const startTimeRequired = document.getElementById('startTimeRequired');
    const endTimeRequired = document.getElementById('endTimeRequired');

 function updateAllDay() {
        const isAllDay = allDayCheckbox.checked;
        if (isAllDay) {
            startInput.disabled = false;
            startInput.setAttribute('required', 'required');
            startTimeDiv.style.opacity = '1';
            startTimeRequired.classList.remove('hidden');
            endInput.disabled = true;
            endInput.removeAttribute('required');
            endInput.value = '';
            endDisplay.textContent = '--:-- --';
            endTimeDiv.style.opacity = '0.5';
            endTimeRequired.classList.add('hidden');
        } else {
            startInput.disabled = false;
            startInput.setAttribute('required', 'required');
            startTimeDiv.style.opacity = '1';
            startTimeRequired.classList.remove('hidden');
            endInput.disabled = false;
            endInput.setAttribute('required', 'required');
            endTimeDiv.style.opacity = '1';
            endTimeRequired.classList.remove('hidden');
            endDisplay.textContent = formatTo12Hour(endInput.value);
        }
    }

    allDayCheckbox.addEventListener('change', updateAllDay);
    updateAllDay();

    if (startInput && startDisplay) {
        startInput.addEventListener('change', function() {
            startDisplay.textContent = formatTo12Hour(this.value);
        });
        if (!allDayCheckbox.checked) startDisplay.textContent = formatTo12Hour(startInput.value);
    }

    if (endInput && endDisplay) {
        endInput.addEventListener('change', function() {
            endDisplay.textContent = formatTo12Hour(this.value);
        });
        if (!allDayCheckbox.checked) endDisplay.textContent = formatTo12Hour(endInput.value);
    }

    // is_free toggle for base_price
    const radioButtons = document.querySelectorAll('input[name="is_free"]');
    const basePrice = document.getElementById('basePrice');
    const priceRequired = document.getElementById('priceRequired');
    const priceDiv = document.getElementById('priceDiv');

    function updatePriceField() {
        const isFree = document.querySelector('input[name="is_free"]:checked')?.value === '1';
        if (isFree) {
            basePrice.removeAttribute('required');
            basePrice.value = '0';
            basePrice.disabled = true;
            priceRequired.classList.add('hidden');
            priceDiv.style.opacity = '0.6';
        } else {
            basePrice.setAttribute('required', 'required');
            basePrice.disabled = false;
            priceRequired.classList.remove('hidden');
            priceDiv.style.opacity = '1';
        }
    }

    radioButtons.forEach(rb => rb.addEventListener('change', updatePriceField));
    updatePriceField();

    // ticket_type toggle for price
    const ticketType = document.getElementById('ticketType');
    const ticketPrice = document.getElementById('ticketPrice');
    const ticketPriceDiv = document.getElementById('ticketPriceDiv');

    function updateTicketPrice() {
        if (ticketType.value === 'free') {
            ticketPrice.value = '0';
            ticketPrice.readOnly = true;
            ticketPrice.removeAttribute('required');
            ticketPriceDiv.style.opacity = '0.6';
        } else {
            ticketPrice.value = '';
            ticketPrice.readOnly = false;
            ticketPrice.setAttribute('required', '');
            ticketPriceDiv.style.opacity = '1';
        }
    }

    ticketType.addEventListener('change', updateTicketPrice);
});
</script>
@endsection
