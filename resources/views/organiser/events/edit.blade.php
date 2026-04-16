@extends('layouts.app')

@section('title', 'Edit Event - ' . $event->title)

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold text-white mb-2" style="font-family: 'Playfair Display', serif;">
                    <i class="fas fa-edit text-amber-400"></i> Edit Event
                </h1>
                <p class="text-gray-400">Editing: {{ $event->title }}</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('organiser.events.show', $event) }}" class="px-5 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-lg font-medium transition">
                    <i class="fas fa-eye mr-2"></i>View
                </a>
                <a href="{{ route('organiser.events.index') }}" class="px-5 py-2 bg-slate-700 hover:bg-slate-600 text-white rounded-lg font-medium transition">
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
            <div class="lg:col-span-2">
                <div class="card-luxury rounded-xl p-8">
                    <h2 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
                        <i class="fas fa-info-circle text-amber-400"></i> Event Details
                    </h2>

                    <form action="{{ route('organiser.events.update', $event) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-amber-400 font-bold mb-2">Event Title <span class="text-red-400">*</span></label>
                            <input type="text" name="title" value="{{ old('title', $event->title) }}" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 transition" required>
                        </div>

                        <div>
                            <label class="block text-amber-400 font-bold mb-2">Description <span class="text-red-400">*</span></label>
                            <textarea name="description" rows="4" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 transition" required>{{ old('description', $event->description) }}</textarea>
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
                                <label class="block text-amber-400 font-bold mb-2">Navigation Location <span class="text-gray-400 font-normal text-xs">(Google Maps link)</span></label>
                                <input type="url" name="navigation_location" value="{{ old('navigation_location', $event->navigation_location) }}" placeholder="https://maps.google.com/..." class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
                            </div>
                        </div>

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
                            <input type="hidden" name="all_day" value="0">
                            <input type="checkbox" id="allDayEvent" name="all_day" value="1" {{ old('all_day', $event->all_day) ? 'checked' : '' }} class="w-4 h-4 rounded border-slate-600 bg-slate-700 text-emerald-500 focus:ring-emerald-500 cursor-pointer">
                            <label for="allDayEvent" class="text-amber-400 font-bold cursor-pointer select-none">All Day Event</label>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-amber-400 font-bold mb-2">Event Date <span class="text-red-400">*</span></label>
                                <input type="date" name="event_date" value="{{ old('event_date', $event->event_date?->format('Y-m-d')) }}" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 transition" required>
                            </div>
                            <div id="startTimeDiv">
                                <label class="block text-amber-400 font-bold mb-2">Start Time <span class="text-red-400" id="startTimeRequired">*</span></label>
                                <div class="flex gap-2 items-center">
                                    <input type="time" name="start_time" id="startTime" value="{{ old('start_time', substr($event->start_time ?? '', 0, 5)) }}" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 transition" required>
                                    <span id="startTimeDisplay" class="text-gray-400 font-semibold min-w-16 text-center">--:-- --</span>
                                </div>
                            </div>
                            <div id="endTimeDiv">
                                <label class="block text-amber-400 font-bold mb-2">End Time <span class="text-red-400" id="endTimeRequired">*</span></label>
                                <div class="flex gap-2 items-center">
                                    <input type="time" name="end_time" id="endTime" value="{{ old('end_time', substr($event->end_time ?? '', 0, 5)) }}" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 transition" required>
                                    <span id="endTimeDisplay" class="text-gray-400 font-semibold min-w-16 text-center">--:-- --</span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-amber-400 font-bold mb-2">Event Type <span class="text-red-400">*</span></label>
                            <div class="flex gap-6">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="is_free" value="0" {{ old('is_free', $event->is_free ? '1' : '0') == '0' ? 'checked' : '' }} class="w-4 h-4 text-amber-400">
                                    <span class="text-white">Paid Event</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="is_free" value="1" {{ old('is_free', $event->is_free ? '1' : '0') == '1' ? 'checked' : '' }} class="w-4 h-4 text-amber-400">
                                    <span class="text-white">Free Event</span>
                                </label>
                            </div>
                        </div>

                        <div id="priceDiv" class="{{ $event->is_free ? 'opacity-60' : '' }}">
                            <label class="block text-amber-400 font-bold mb-2">Base Price <span id="priceRequired" class="text-red-400 {{ $event->is_free ? 'hidden' : '' }}">*</span></label>
                            <input type="number" name="base_price" step="0.01" min="0" id="basePrice" value="{{ old('base_price', $event->base_price) }}" {{ $event->is_free ? 'disabled' : '' }} class="w-full md:w-1/2 px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
                        </div>

                        <div>
                            <label class="block text-amber-400 font-bold mb-2">Event Image</label>
                            @if($event->image)
                                <div class="mb-3">
                                    <img src="{{ asset('storage/' . $event->image) }}" alt="Current image" class="h-32 w-32 object-cover rounded-lg border border-slate-600">
                                    <p class="text-gray-400 text-xs mt-1">Current image - upload a new one to replace</p>
                                </div>
                            @endif
                            <input type="file" name="image" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 transition" accept="image/*">
                        </div>

                        <div class="flex gap-4 pt-6 border-t border-slate-600">
                            <button type="submit" class="px-6 py-3 bg-gradient-to-r from-emerald-500 to-teal-500 hover:shadow-lg hover:shadow-emerald-500/20 text-white rounded-lg font-semibold transition">
                                <i class="fas fa-save mr-2"></i> Save Changes
                            </button>
                            <a href="{{ route('organiser.events.show', $event) }}" class="px-6 py-3 bg-slate-700 hover:bg-slate-600 text-white rounded-lg font-medium transition">
                                <i class="fas fa-times mr-2"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="lg:col-span-2">
                <div class="card-luxury rounded-xl p-7 lg:p-8">
                    <div class="mb-7 pb-5 border-b border-slate-600/60">
                        <h2 class="text-2xl font-bold text-white flex items-center gap-2">
                            <i class="fas fa-clock text-amber-400"></i> Show Timings & Ticket Categories
                        </h2>
                        <p class="text-sm text-gray-400 mt-1">Manage timing-wise ticket categories within venue capacity.</p>
                    </div>

                    @if($event->showTimings->isNotEmpty())
                        <div class="space-y-6 mb-8">
                            @foreach($event->showTimings as $timing)
                                @php
                                    $usedSeats = $timing->tickets->sum('quantity');
                                    $freeSeats = max(0, $timing->available_seats - $usedSeats);
                                @endphp

                                <div class="rounded-2xl border border-slate-600/60 bg-slate-800/45 overflow-hidden">
                                    <div class="flex flex-wrap items-start justify-between gap-4 px-6 py-5 bg-slate-700/60 border-b border-slate-600/50">
                                        <div>
                                            <p class="text-white font-bold text-base sm:text-lg">
                                                <i class="fas fa-calendar-alt text-emerald-400 mr-1"></i>
                                                {{ \Carbon\Carbon::parse($timing->show_date_time)->format('D, M d Y') }}
                                                <span class="text-amber-400 ml-2">{{ \Carbon\Carbon::parse($timing->show_date_time)->format('h:i A') }}</span>
                                            </p>
                                            <p class="text-gray-400 text-sm mt-1">
                                                <i class="fas fa-map-marker-alt mr-1"></i>{{ $timing->venue->name ?? '-' }}
                                                <span class="mx-2">|</span>{{ $timing->duration_minutes }} min
                                                <span class="mx-2">|</span>
                                                <span class="{{ $freeSeats <= 0 ? 'text-red-400' : 'text-emerald-400' }} font-semibold">
                                                    {{ $usedSeats }}/{{ $timing->available_seats }} seats allocated
                                                </span>
                                                @if($timing->notes)
                                                    <span class="mx-2">|</span>{{ $timing->notes }}
                                                @endif
                                            </p>
                                        </div>

                                        <div class="flex items-center gap-2">
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                                {{ $timing->status === 'scheduled' ? 'bg-blue-500/20 text-blue-200 border border-blue-500/30' : '' }}
                                                {{ $timing->status === 'cancelled' ? 'bg-red-500/20 text-red-200 border border-red-500/30' : '' }}
                                                {{ $timing->status === 'completed' ? 'bg-green-500/20 text-green-200 border border-green-500/30' : '' }}">
                                                {{ ucfirst($timing->status) }}
                                            </span>
                                            <a href="{{ route('organiser.show-timings.edit', $timing) }}" class="p-2 text-amber-400 hover:text-amber-300 hover:bg-amber-500/10 rounded-lg transition" title="Edit timing">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('organiser.events.timings.destroy', $timing) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" onclick="return confirm('Delete this show timing and all its tickets?')" class="p-2 text-red-400 hover:text-red-300 hover:bg-red-500/10 rounded-lg transition" title="Delete timing">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>

                                    <div class="px-6 py-6">
                                        @if($timing->available_seats > 0)
                                            @php $pct = min(100, round(($usedSeats / $timing->available_seats) * 100)); @endphp
                                            <div class="mb-5">
                                                <div class="flex justify-between text-xs text-gray-400 mb-1">
                                                    <span>Allocated: {{ $usedSeats }}</span>
                                                    <span>Capacity: {{ $timing->available_seats }}</span>
                                                </div>
                                                <div class="w-full bg-slate-700 rounded-full h-2">
                                                    <div class="h-2 rounded-full transition-all {{ $pct >= 100 ? 'bg-red-500' : ($pct >= 80 ? 'bg-amber-400' : 'bg-emerald-500') }}" style="width: {{ $pct }}%"></div>
                                                </div>
                                            </div>
                                        @endif

                                        @if($timing->tickets->isNotEmpty())
                                            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-3 mb-5">
                                                @foreach($timing->tickets as $ticket)
                                                    @php
                                                        $maxForThisTicket = max(1, $freeSeats + $ticket->quantity);
                                                    @endphp
                                                    <div class="bg-slate-700/45 border border-slate-600/40 rounded-xl p-4 flex flex-col justify-between gap-3">
                                                        <div>
                                                            <p class="text-white font-semibold leading-tight">{{ $ticket->name }}</p>
                                                            <p class="text-xs text-gray-400 mt-1">
                                                                {{ $ticket->ticket_type === 'free' ? 'Free' : '₹'.number_format($ticket->price, 2) }}
                                                                <span class="mx-1">|</span>Qty: {{ $ticket->quantity }}
                                                                <span class="mx-1">|</span>Sold: {{ $ticket->quantity_sold }}
                                                            </p>
                                                            @if($ticket->description)
                                                                <p class="text-xs text-slate-300 mt-2">{{ $ticket->description }}</p>
                                                            @endif
                                                        </div>
                                                        <div class="flex items-center justify-end gap-2">
                                                            <button type="button"
                                                                class="js-open-ticket-modal p-2 text-amber-400 hover:text-amber-300 hover:bg-amber-500/10 rounded-lg transition"
                                                                title="Edit ticket"
                                                                data-action="{{ route('organiser.show-timings.tickets.update', [$timing, $ticket]) }}"
                                                                data-name="{{ $ticket->name }}"
                                                                data-description="{{ $ticket->description }}"
                                                                data-ticket-type="{{ $ticket->ticket_type }}"
                                                                data-price="{{ $ticket->price }}"
                                                                data-quantity="{{ $ticket->quantity }}"
                                                                data-min="{{ max(1, $ticket->quantity_sold) }}"
                                                                data-max="{{ $maxForThisTicket }}">
                                                                <i class="fas fa-pen"></i>
                                                            </button>
                                                            <form action="{{ route('organiser.show-timings.tickets.destroy', [$timing, $ticket]) }}" method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" onclick="return confirm('Remove this ticket category?')" class="p-2 text-red-400 hover:text-red-300 hover:bg-red-500/10 rounded-lg transition" title="Delete ticket">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <p class="text-sm text-gray-400 italic mb-5">No ticket categories added for this timing.</p>
                                        @endif

                                        @if($freeSeats > 0)
                                            <div class="border-t border-slate-600/50 pt-5">
                                                <button type="button" onclick="toggleTicketForm({{ $timing->id }}, this)" class="flex items-center gap-2 text-emerald-400 hover:text-emerald-300 text-sm font-semibold transition">
                                                    <i class="fas fa-plus-circle"></i>
                                                    <span>{{ session('timing_error_id') == $timing->id ? 'Cancel' : 'Add Ticket Category' }}</span>
                                                </button>

                                                <div id="ticket-form-{{ $timing->id }}" class="{{ session('timing_error_id') == $timing->id ? '' : 'hidden' }} mt-4 border border-slate-600/60 rounded-xl p-4 bg-slate-900/40">
                                                    @if(session('timing_error_id') == $timing->id)
                                                        <div class="mb-3 p-2 bg-red-500/10 border border-red-500/30 rounded text-red-300 text-xs">
                                                            @foreach($errors->get('quantity') as $err)
                                                                {{ $err }}
                                                            @endforeach
                                                        </div>
                                                    @endif

                                                    <form action="{{ route('organiser.show-timings.tickets.store', $timing) }}" method="POST" class="space-y-3">
                                                        @csrf
                                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                                            <div>
                                                                <label class="block text-gray-300 text-xs mb-1">Category Name <span class="text-red-400">*</span></label>
                                                                <input type="text" name="name" placeholder="e.g. Gold, Silver, General" class="w-full px-3 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500" required>
                                                            </div>
                                                            <div>
                                                                <label class="block text-gray-300 text-xs mb-1">Description</label>
                                                                <input type="text" name="description" placeholder="Optional" class="w-full px-3 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                                            </div>
                                                        </div>
                                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                                            <div>
                                                                <label class="block text-gray-300 text-xs mb-1">Type <span class="text-red-400">*</span></label>
                                                                <select name="ticket_type" id="ttype-{{ $timing->id }}" onchange="toggleTimingPrice({{ $timing->id }}, this.value)" class="w-full px-3 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500" required>
                                                                    <option value="paid">Paid</option>
                                                                    <option value="free">Free</option>
                                                                </select>
                                                            </div>
                                                            <div id="tprice-wrap-{{ $timing->id }}">
                                                                <label class="block text-gray-300 text-xs mb-1">Price (₹)</label>
                                                                <input type="number" name="price" id="tprice-{{ $timing->id }}" step="0.01" min="0" placeholder="0.00" class="w-full px-3 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                                            </div>
                                                            <div>
                                                                <label class="block text-gray-300 text-xs mb-1">Quantity (max {{ $freeSeats }}) <span class="text-red-400">*</span></label>
                                                                <input type="number" name="quantity" min="1" max="{{ $freeSeats }}" placeholder="{{ $freeSeats }}" class="w-full px-3 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500" required>
                                                            </div>
                                                        </div>
                                                        <button type="submit" class="w-full py-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg text-sm font-semibold transition">
                                                            <i class="fas fa-plus mr-1"></i> Add Category
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        @else
                                            <p class="text-amber-400 text-xs font-semibold"><i class="fas fa-lock mr-1"></i>Capacity fully allocated. Remove or edit a category to re-allocate seats.</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-400 text-sm mb-6">No show timings yet. Add one below to start managing tickets.</p>
                    @endif

                    <div class="border-t border-slate-600 pt-6">
                        <h3 class="text-amber-400 font-semibold mb-4"><i class="fas fa-plus-circle mr-1"></i>Add Show Timing</h3>
                        <form action="{{ route('organiser.events.timings.store', $event) }}" method="POST" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            @csrf
                            <div>
                                <label class="block text-gray-300 text-sm mb-1">Venue <span class="text-red-400">*</span></label>
                                <select name="venue_id" class="w-full px-3 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500" required>
                                    <option value="">-- Select Venue --</option>
                                    @foreach($venues as $venue)
                                        <option value="{{ $venue->id }}">{{ $venue->name }}</option>
                                    @endforeach
                                </select>
                                @error('venue_id')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-gray-300 text-sm mb-1">Date & Time <span class="text-red-400">*</span></label>
                                <input type="datetime-local" name="show_date_time" class="w-full px-3 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500" required>
                            </div>
                            <div>
                                <label class="block text-gray-300 text-sm mb-1">Duration (min) <span class="text-red-400">*</span></label>
                                <input type="number" name="duration_minutes" min="1" placeholder="120" class="w-full px-3 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500" required>
                            </div>
                            <div>
                                <label class="block text-gray-300 text-sm mb-1">Available Seats <span class="text-red-400">*</span></label>
                                <input type="number" name="available_seats" min="1" placeholder="200" class="w-full px-3 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500" required>
                            </div>
                            <div>
                                <label class="block text-gray-300 text-sm mb-1">Notes</label>
                                <input type="text" name="notes" placeholder="Optional" class="w-full px-3 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            </div>
                            <div class="flex items-end">
                                <button type="submit" class="w-full py-2 bg-amber-600 hover:bg-amber-500 text-white rounded-lg text-sm font-semibold transition">
                                    <i class="fas fa-plus mr-1"></i> Add Show Timing
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="ticketEditModal" class="hidden fixed inset-0 z-50 bg-black/60 backdrop-blur-sm px-4">
    <div class="min-h-full flex items-center justify-center">
        <div class="card-luxury rounded-xl w-full max-w-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-white"><i class="fas fa-pen text-amber-400 mr-1"></i>Edit Ticket Category</h3>
                <button type="button" id="closeTicketEditModal" class="text-gray-400 hover:text-white">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="ticketEditForm" method="POST" class="space-y-3">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-gray-300 text-xs mb-1">Category Name <span class="text-red-400">*</span></label>
                    <input type="text" id="modalTicketName" name="name" class="w-full px-3 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500" required>
                </div>
                <div>
                    <label class="block text-gray-300 text-xs mb-1">Description</label>
                    <input type="text" id="modalTicketDescription" name="description" class="w-full px-3 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block text-gray-300 text-xs mb-1">Type <span class="text-red-400">*</span></label>
                        <select id="modalTicketType" name="ticket_type" class="w-full px-3 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500" required>
                            <option value="paid">Paid</option>
                            <option value="free">Free</option>
                        </select>
                    </div>
                    <div id="modalPriceWrap">
                        <label class="block text-gray-300 text-xs mb-1">Price (₹)</label>
                        <input type="number" id="modalTicketPrice" name="price" step="0.01" min="0" class="w-full px-3 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="block text-gray-300 text-xs mb-1">Quantity <span class="text-red-400">*</span></label>
                        <input type="number" id="modalTicketQuantity" name="quantity" min="1" class="w-full px-3 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500" required>
                        <p id="modalQtyHint" class="text-[11px] text-gray-400 mt-1"></p>
                    </div>
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" id="cancelTicketEditModal" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-gray-200 rounded-lg text-sm">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg text-sm font-semibold">Update Ticket</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.card-luxury {
    background: linear-gradient(135deg, rgba(15, 23, 42, 0.82) 0%, rgba(30, 41, 59, 0.82) 100%);
    border: 1px solid rgba(148, 113, 113, 0.2);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3);
}
</style>

<script>
function formatTo12Hour(time24) {
    if (!time24) return '--:-- --';
    const [hours, minutes] = time24.split(':');
    const hour = parseInt(hours, 10);
    const ampm = hour >= 12 ? 'PM' : 'AM';
    const hour12 = hour % 12 || 12;
    return `${String(hour12).padStart(2, '0')}:${minutes} ${ampm}`;
}

function toggleTicketForm(timingId, btn) {
    const form = document.getElementById('ticket-form-' + timingId);
    if (!form) return;
    const isHidden = form.classList.contains('hidden');
    form.classList.toggle('hidden', !isHidden);
    btn.querySelector('span').textContent = isHidden ? 'Cancel' : 'Add Ticket Category';
}

function toggleTimingPrice(timingId, value) {
    const priceInput = document.getElementById('tprice-' + timingId);
    const priceWrap = document.getElementById('tprice-wrap-' + timingId);
    if (!priceInput || !priceWrap) return;
    const isFree = value === 'free';
    priceInput.disabled = isFree;
    priceWrap.style.opacity = isFree ? '0.5' : '1';
    if (isFree) priceInput.value = '0';
}

document.addEventListener('DOMContentLoaded', function() {
    const startInput = document.getElementById('startTime');
    const endInput = document.getElementById('endTime');
    const startDisplay = document.getElementById('startTimeDisplay');
    const endDisplay = document.getElementById('endTimeDisplay');
    const allDayCheckbox = document.getElementById('allDayEvent');
    const endTimeDiv = document.getElementById('endTimeDiv');
    const endTimeRequired = document.getElementById('endTimeRequired');

    function updateAllDay() {
        const isAllDay = allDayCheckbox.checked;
        if (isAllDay) {
            endInput.disabled = true;
            endInput.removeAttribute('required');
            endInput.value = '';
            endDisplay.textContent = '--:-- --';
            endTimeDiv.style.opacity = '0.5';
            endTimeRequired.classList.add('hidden');
        } else {
            endInput.disabled = false;
            endInput.setAttribute('required', 'required');
            endDisplay.textContent = formatTo12Hour(endInput.value);
            endTimeDiv.style.opacity = '1';
            endTimeRequired.classList.remove('hidden');
        }
    }

    if (allDayCheckbox) {
        allDayCheckbox.addEventListener('change', updateAllDay);
        updateAllDay();
    }

    if (startInput && startDisplay) {
        startInput.addEventListener('change', function() { startDisplay.textContent = formatTo12Hour(this.value); });
        startDisplay.textContent = formatTo12Hour(startInput.value);
    }

    if (endInput && endDisplay) {
        endInput.addEventListener('change', function() { endDisplay.textContent = formatTo12Hour(this.value); });
        if (!allDayCheckbox || !allDayCheckbox.checked) {
            endDisplay.textContent = formatTo12Hour(endInput.value);
        }
    }

    const radioButtons = document.querySelectorAll('input[name="is_free"]');
    const basePrice = document.getElementById('basePrice');
    const priceRequired = document.getElementById('priceRequired');
    const priceDiv = document.getElementById('priceDiv');

    function updatePriceField() {
        const checked = document.querySelector('input[name="is_free"]:checked');
        if (!checked) return;
        const isFree = checked.value === '1';
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

    radioButtons.forEach(radio => radio.addEventListener('change', updatePriceField));
    updatePriceField();

    const modal = document.getElementById('ticketEditModal');
    const modalForm = document.getElementById('ticketEditForm');
    const closeModalBtn = document.getElementById('closeTicketEditModal');
    const cancelModalBtn = document.getElementById('cancelTicketEditModal');
    const modalType = document.getElementById('modalTicketType');
    const modalPrice = document.getElementById('modalTicketPrice');
    const modalPriceWrap = document.getElementById('modalPriceWrap');
    const modalQty = document.getElementById('modalTicketQuantity');
    const modalQtyHint = document.getElementById('modalQtyHint');

    function closeModal() {
        modal.classList.add('hidden');
    }

    function toggleModalPrice() {
        const isFree = modalType.value === 'free';
        modalPrice.disabled = isFree;
        modalPriceWrap.style.opacity = isFree ? '0.5' : '1';
        if (isFree) modalPrice.value = '0';
    }

    document.querySelectorAll('.js-open-ticket-modal').forEach(btn => {
        btn.addEventListener('click', function() {
            modalForm.action = this.dataset.action;
            document.getElementById('modalTicketName').value = this.dataset.name || '';
            document.getElementById('modalTicketDescription').value = this.dataset.description || '';
            modalType.value = this.dataset.ticketType || 'paid';
            modalPrice.value = this.dataset.price || '0';
            modalQty.value = this.dataset.quantity || '1';
            modalQty.min = this.dataset.min || '1';
            modalQty.max = this.dataset.max || '1';
            modalQtyHint.textContent = `Allowed range: ${modalQty.min} to ${modalQty.max}`;
            toggleModalPrice();
            modal.classList.remove('hidden');
        });
    });

    modalType.addEventListener('change', toggleModalPrice);
    closeModalBtn.addEventListener('click', closeModal);
    cancelModalBtn.addEventListener('click', closeModal);
    modal.addEventListener('click', function(e) {
        if (e.target === modal) closeModal();
    });
});
</script>
@endsection
