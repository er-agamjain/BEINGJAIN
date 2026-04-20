@extends('layouts.app')

@section('title', 'Create Event')

@section('content')
<div class="py-12 min-h-screen">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-10">
            <div class="flex items-center gap-3 mb-3">
                <div class="p-3 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl">
                    <i class="fas fa-plus text-white text-xl"></i>
                </div>
                <div>
                    <h1 class="text-4xl font-bold text-white" style="font-family: 'Playfair Display', serif;">Create Event</h1>
                    <p class="text-gray-400 text-sm mt-1">Bring your event to life with detailed information and engaging visuals</p>
                </div>
            </div>
        </div>

        <div class="card-luxury rounded-2xl p-10 lg:p-12">
            <form action="{{ route('organiser.events.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf

                <div class="pb-6 border-b border-slate-700">
                    <h2 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
                        <i class="fas fa-info-circle text-amber-400"></i> Basic Information
                    </h2>

                    <div class="space-y-5">
                        <div>
                            <label class="block text-amber-400 font-bold mb-2">Event Title <span class="text-red-400">*</span></label>
                            <input type="text" name="title" value="{{ old('title') }}" placeholder="Enter an engaging event title" class="w-full px-4 py-3 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 transition" required>
                            @error('title') <p class="text-red-400 text-sm mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-amber-400 font-bold mb-2">Description <span class="text-red-400">*</span></label>
                            <textarea name="description" rows="5" placeholder="Describe your event, what to expect, highlights, etc." class="w-full px-4 py-3 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 transition" required>{{ old('description') }}</textarea>
                            @error('description') <p class="text-red-400 text-sm mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-amber-400 font-bold mb-2">City</label>
                                <select name="city_id" class="w-full px-4 py-3 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
                                    <option value="">-- Select City --</option>
                                    @foreach($cities as $id => $name)
                                        <option value="{{ $id }}" {{ old('city_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                                @error('city_id') <p class="text-red-400 text-sm mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-amber-400 font-bold mb-2">Navigation Location <span class="text-gray-400 font-normal text-xs">(Google Maps)</span></label>
                                <input type="url" name="navigation_location" value="{{ old('navigation_location') }}" placeholder="https://maps.google.com/..." class="w-full px-4 py-3 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
                                @error('navigation_location') <p class="text-red-400 text-sm mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pb-6 border-b border-slate-700">
                    <h2 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
                        <i class="fas fa-tag text-amber-400"></i> Categorization
                    </h2>

                    <div class="space-y-5">
                        <div>
                            <label class="block text-amber-400 font-bold mb-2">Event Category</label>
                            <select name="category_id" class="w-full px-4 py-3 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
                                <option value="">-- Select Category --</option>
                                @foreach($categories as $id => $name)
                                    <option value="{{ $id }}" {{ old('category_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                            @error('category_id') <p class="text-red-400 text-sm mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                            <div>
                                <label class="block text-amber-400 font-bold mb-2">Community</label>
                                <select name="community" class="w-full px-4 py-3 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
                                    <option value="">-- Select --</option>
                                    @foreach($communities as $community)
                                        <option value="{{ $community }}" {{ old('community') == $community ? 'selected' : '' }}>{{ $community }}</option>
                                    @endforeach
                                </select>
                                @error('community') <p class="text-red-400 text-sm mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-amber-400 font-bold mb-2">Gacchh</label>
                                <select name="gacchh" class="w-full px-4 py-3 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
                                    <option value="">-- Select --</option>
                                    @foreach($gacchhs as $gacchh)
                                        <option value="{{ $gacchh }}" {{ old('gacchh') == $gacchh ? 'selected' : '' }}>{{ $gacchh }}</option>
                                    @endforeach
                                </select>
                                @error('gacchh') <p class="text-red-400 text-sm mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-amber-400 font-bold mb-2">Tags</label>
                                <select name="tags" class="w-full px-4 py-3 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
                                    <option value="">-- Select --</option>
                                    @foreach($tagsList as $tag)
                                        <option value="{{ $tag }}" {{ old('tags') == $tag ? 'selected' : '' }}>{{ $tag }}</option>
                                    @endforeach
                                </select>
                                @error('tags') <p class="text-red-400 text-sm mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pb-6 border-b border-slate-700">
                    <h2 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
                        <i class="fas fa-calendar text-amber-400"></i> Date & Time
                    </h2>

                    <div class="space-y-5">
                        <div class="flex items-center gap-4 p-4 bg-slate-800/50 rounded-lg border border-slate-700">
                            <input type="hidden" name="all_day" value="0">
                            <input type="checkbox" id="allDayEvent" name="all_day" value="1" {{ old('all_day') ? 'checked' : '' }} class="w-5 h-5 rounded border-slate-600 bg-slate-700 text-emerald-500 focus:ring-emerald-500 cursor-pointer">
                            <label for="allDayEvent" class="text-white font-semibold cursor-pointer select-none flex items-center gap-2">
                                <i class="fas fa-sun text-amber-400"></i> All Day Event
                            </label>
                        </div>

                        <div>
                            <label class="block text-amber-400 font-bold mb-2">Event Date <span class="text-red-400">*</span></label>
                            <input type="date" name="event_date" value="{{ old('event_date') }}" class="w-full px-4 py-3 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 transition" required>
                            @error('event_date') <p class="text-red-400 text-sm mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div id="startTimeDiv">
                                <label class="block text-amber-400 font-bold mb-2">Start Time <span class="text-red-400" id="startTimeRequired">*</span></label>
                                <div class="flex gap-3 items-center">
                                    <input type="time" name="start_time" value="{{ old('start_time') }}" id="startTime" class="flex-1 px-4 py-3 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 transition" required>
                                    <span id="startTimeDisplay" class="text-gray-400 font-semibold min-w-20 text-center">--:-- --</span>
                                </div>
                                @error('start_time') <p class="text-red-400 text-sm mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p> @enderror
                            </div>

                            <div id="endTimeDiv">
                                <label class="block text-amber-400 font-bold mb-2">End Time <span class="text-red-400" id="endTimeRequired">*</span></label>
                                <div class="flex gap-3 items-center">
                                    <input type="time" name="end_time" value="{{ old('end_time') }}" id="endTime" class="flex-1 px-4 py-3 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 transition" required>
                                    <span id="endTimeDisplay" class="text-gray-400 font-semibold min-w-20 text-center">--:-- --</span>
                                </div>
                                @error('end_time') <p class="text-red-400 text-sm mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pb-6 border-b border-slate-700">

                    <h2 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
                        <i class="fas fa-tag text-amber-400"></i> Pricing
                    </h2>

                    <div class="space-y-5">
                        <div>
                            <label class="block text-amber-400 font-bold mb-3">Event Type <span class="text-red-400">*</span></label>
                            <div class="flex gap-6">
                                <label class="flex items-center gap-3 cursor-pointer p-3 rounded-lg hover:bg-slate-800/50 transition">
                                    <input type="radio" name="is_free" value="0" {{ old('is_free', '0') == '0' ? 'checked' : '' }} class="w-5 h-5 text-emerald-500">
                                    <div>
                                        <p class="text-white font-semibold">Paid Event</p>
                                        <p class="text-gray-400 text-xs">Charge attendees a fee</p>
                                    </div>
                                </label>
                                <label class="flex items-center gap-3 cursor-pointer p-3 rounded-lg hover:bg-slate-800/50 transition">
                                    <input type="radio" name="is_free" value="1" {{ old('is_free') == '1' ? 'checked' : '' }} class="w-5 h-5 text-emerald-500">
                                    <div>
                                        <p class="text-white font-semibold">Free Event</p>
                                        <p class="text-gray-400 text-xs">No admission fee</p>
                                    </div>
                                </label>
                            </div>
                            @error('is_free') <p class="text-red-400 text-sm mt-2"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p> @enderror
                        </div>

                        <div id="priceDiv">
                            <label class="block text-amber-400 font-bold mb-2">Base Price <span id="priceRequired" class="text-red-400">*</span></label>
                            <div class="relative">
                                <span class="absolute left-4 top-3.5 text-gray-400 font-semibold">₹</span>
                                <input type="number" name="base_price" step="0.01" min="0" value="{{ old('base_price') }}" id="basePrice" placeholder="0.00" class="w-full pl-8 pr-4 py-3 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
                            </div>
                            @error('base_price') <p class="text-red-400 text-sm mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="pb-6 border-b border-slate-700">

                    <h2 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
                        <i class="fas fa-image text-amber-400"></i> Event Image
                    </h2>

                    <div class="space-y-5">
                        @if(old('temp_image'))
                            <div class="flex items-center gap-4 p-4 bg-emerald-500/10 border border-emerald-500/30 rounded-lg">
                                <img src="{{ asset('storage/' . old('temp_image')) }}" alt="Selected image" class="h-24 w-24 object-cover rounded-lg border border-emerald-500/50">
                                <div>
                                    <p class="text-emerald-400 text-sm font-semibold flex items-center gap-2"><i class="fas fa-check-circle"></i>Image Already Selected</p>
                                    <p class="text-gray-400 text-xs mt-1">Upload a new one below to replace it, or leave blank to keep this one.</p>
                                </div>
                            </div>
                            <input type="hidden" name="temp_image" value="{{ old('temp_image') }}">
                        @endif
                        <input type="file" name="image" id="imageInput" class="w-full px-4 py-3 bg-slate-700 border-2 border-dashed border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 transition cursor-pointer" accept="image/*">
                        <p class="text-gray-400 text-sm text-center">PNG, JPG or GIF (max. 5 MB recommended)</p>
                        @error('image') <p class="text-red-400 text-sm mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex gap-4 pt-6">
                    <button type="submit" class="flex-1 px-6 py-4 bg-gradient-to-r from-emerald-500 to-teal-500 hover:shadow-lg hover:shadow-emerald-500/30 text-white rounded-lg font-bold transition flex items-center justify-center gap-2">
                        <i class="fas fa-plus"></i>Create Event
                    </button>
                    <a href="{{ route('organiser.events.index') }}" class="px-6 py-4 bg-slate-700 hover:bg-slate-600 text-white rounded-lg font-medium transition flex items-center justify-center gap-2 min-w-24">
                        <i class="fas fa-times"></i>Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

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
            startDisplay.textContent = formatTo12Hour(startInput.value);
            startTimeDiv.style.opacity = '1';
            startTimeRequired.classList.remove('hidden');
            endInput.disabled = false;
            endInput.setAttribute('required', 'required');
            endDisplay.textContent = formatTo12Hour(endInput.value);
            endTimeDiv.style.opacity = '1';
            endTimeDiv.style.display = '';
            endTimeRequired.classList.remove('hidden');
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

    // Handle is_free radio buttons
    const radioButtons = document.querySelectorAll('input[name="is_free"]');
    const basePrice = document.getElementById('basePrice');
    const priceRequired = document.getElementById('priceRequired');
    const priceDiv = document.getElementById('priceDiv');

    function updatePriceField() {
        const isFree = document.querySelector('input[name="is_free"]:checked').value === '1';
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

    radioButtons.forEach(radio => {
        radio.addEventListener('change', updatePriceField);
    });

    // Initialize on page load
    updatePriceField();
});
</script>

<style>
    .card-luxury {
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.8) 0%, rgba(30, 41, 59, 0.8) 100%);
        border: 1px solid rgba(148, 113, 113, 0.2);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3);
    }
</style>
@endsection
