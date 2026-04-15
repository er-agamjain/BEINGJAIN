@extends('layouts.app')

@section('content')
<div class="py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-white mb-2">Create Event</h1>
            <p class="text-gray-400">Add a new event to your portfolio</p>
        </div>

        <div class="card-luxury rounded-lg p-8 max-w-2xl">
            <form action="{{ route('organiser.events.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div>
                    <label class="block text-amber-400 font-bold mb-2">Event Title <span class="text-red-400">*</span></label>
                    <input type="text" name="title" value="{{ old('title') }}" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 transition" required>
                    @error('title') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-amber-400 font-bold mb-2">Description <span class="text-red-400">*</span></label>
                    <textarea name="description" rows="4" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 transition" required>{{ old('description') }}</textarea>
                    @error('description') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-amber-400 font-bold mb-2">City</label>
                        <select name="city_id" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
                            <option value="">Select City</option>
                            @foreach($cities as $id => $name)
                                <option value="{{ $id }}" {{ old('city_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                        @error('city_id') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-amber-400 font-bold mb-2">Navigation Location
                            <span class="text-gray-400 font-normal text-xs">(Google Maps link)</span>
                        </label>
                        <input type="url" name="navigation_location" value="{{ old('navigation_location') }}" placeholder="https://maps.google.com/..." class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
                        @error('navigation_location') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4">
                   <!--<div>
                        <label class="block text-amber-400 font-bold mb-2">Location <span class="text-red-400">*</span></label>
                        <input type="text" name="location" value="{{ old('location') }}" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 transition" required>
                        @error('location') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>-->

                <div>
                    <label class="block text-amber-400 font-bold mb-2">Event Category</label>
                    <select name="category_id" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
                        <option value="">Select a category</option>
                        @foreach($categories as $id => $name)
                            <option value="{{ $id }}" {{ old('category_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-amber-400 font-bold mb-2">Community</label>
                        <select name="community" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
                            <option value="">Select Community</option>
                            @foreach($communities as $community)
                                <option value="{{ $community }}" {{ old('community') == $community ? 'selected' : '' }}>{{ $community }}</option>
                            @endforeach
                        </select>
                        @error('community') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-amber-400 font-bold mb-2">Gacchh</label>
                        <select name="gacchh" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
                            <option value="">Select Gacchh</option>
                            @foreach($gacchhs as $gacchh)
                                <option value="{{ $gacchh }}" {{ old('gacchh') == $gacchh ? 'selected' : '' }}>{{ $gacchh }}</option>
                            @endforeach
                        </select>
                        @error('gacchh') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-amber-400 font-bold mb-2">Tags</label>
                        <select name="tags" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
                            <option value="">Select Tags</option>
                            @foreach($tagsList as $tag)
                                <option value="{{ $tag }}" {{ old('tags') == $tag ? 'selected' : '' }}>{{ $tag }}</option>
                            @endforeach
                        </select>
                        @error('tags') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
                
                <div class="flex items-center gap-3">
                    <input type="hidden" name="all_day" value="0">
                    <input type="checkbox" id="allDayEvent" name="all_day" value="1" {{ old('all_day') ? 'checked' : '' }} class="w-4 h-4 rounded border-slate-600 bg-slate-700 text-emerald-500 focus:ring-emerald-500 cursor-pointer">
                    <label for="allDayEvent" class="text-amber-400 font-bold cursor-pointer select-none">All Day Event</label>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-amber-400 font-bold mb-2">Event Date <span class="text-red-400">*</span></label>
                        <input type="date" name="event_date" value="{{ old('event_date') }}" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 transition" required>
                        @error('event_date') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div id="startTimeDiv">
                        <label class="block text-amber-400 font-bold mb-2">Start Time <span class="text-red-400" id="startTimeRequired">*</span></label>
                        <div class="flex gap-2 items-center">
                            <input type="time" name="start_time" value="{{ old('start_time') }}" id="startTime" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 transition" required>
                            <span id="startTimeDisplay" class="text-gray-400 font-semibold min-w-16 text-center">--:-- --</span>
                        </div>
                        @error('start_time') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>

                   <div id="endTimeDiv">
                        <label class="block text-amber-400 font-bold mb-2">End Time <span class="text-red-400" id="endTimeRequired">*</span></label>
                        <div class="flex gap-2 items-center">
                            <input type="time" name="end_time" value="{{ old('end_time') }}" id="endTime" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 transition" required>
                            <span id="endTimeDisplay" class="text-gray-400 font-semibold min-w-16 text-center">--:-- --</span>
                        </div>
                        @error('end_time') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-amber-400 font-bold mb-2">Event Type <span class="text-red-400">*</span></label>
                    <div class="flex gap-6">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="is_free" value="0" {{ old('is_free', '0') == '0' ? 'checked' : '' }} class="w-4 h-4 text-amber-400">
                            <span class="text-white">Paid Event</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="is_free" value="1" {{ old('is_free') == '1' ? 'checked' : '' }} class="w-4 h-4 text-amber-400">
                            <span class="text-white">Free Event</span>
                        </label>
                    </div>
                    @error('is_free') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div id="priceDiv">
                        <label class="block text-amber-400 font-bold mb-2">Base Price <span id="priceRequired" class="text-red-400">*</span></label>
                        <input type="number" name="base_price" step="0.01" min="0" value="{{ old('base_price') }}" id="basePrice" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
                        @error('base_price') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-amber-400 font-bold mb-2">Event Image</label>
                    @if(old('temp_image'))
                        <div class="mb-3 flex items-center gap-3">
                            <img src="{{ asset('storage/' . old('temp_image')) }}" alt="Selected image" class="h-20 w-20 object-cover rounded-lg border border-slate-600">
                            <div>
                                <p class="text-emerald-400 text-sm font-semibold"><i class="fas fa-check-circle mr-1"></i>Image already selected</p>
                                <p class="text-gray-400 text-xs">Upload a new one below to replace it, or leave blank to keep this one.</p>
                            </div>
                        </div>
                        <input type="hidden" name="temp_image" value="{{ old('temp_image') }}">
                    @endif
                    <input type="file" name="image" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 transition" accept="image/*">
                    @error('image') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="flex gap-4 pt-6 border-t border-slate-600">
                    <button type="submit" class="px-6 py-3 bg-gradient-to-r from-emerald-500 to-teal-500 hover:shadow-lg hover:shadow-emerald-500/20 text-white rounded-lg font-semibold transition">
                        <i class="fas fa-plus mr-2"></i>Create Event
                    </button>
                    <a href="{{ route('organiser.events.index') }}" class="px-6 py-3 bg-slate-700 hover:bg-slate-600 text-white rounded-lg font-medium transition">
                        <i class="fas fa-times mr-2"></i>Cancel
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
