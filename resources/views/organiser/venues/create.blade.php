@extends('layouts.app')

@section('content')
<div class="py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-white mb-2">Create Venue</h1>
            <p class="text-gray-400">Add a new venue for your events</p>
        </div>

        <div class="card-luxury rounded-lg p-8 max-w-2xl">
            <form action="{{ route('organiser.venues.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div>
                    <label class="block text-amber-400 font-bold mb-2">Venue Name <span class="text-red-400">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g., Crystal Hall, Taj Auditorium" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 transition" required>
                    @error('name') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-amber-400 font-bold mb-2">Search City/Location</label>
                    <div class="relative">
                        <input type="text" id="citySearch" placeholder="Type city or state name..." class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
                        <div id="citySuggestions" class="absolute top-full left-0 right-0 bg-slate-800 border border-t-0 border-slate-600 rounded-b-lg shadow-lg max-h-48 overflow-y-auto hidden z-10"></div>
                    </div>
                </div>

                <div>
                    <label class="block text-amber-400 font-bold mb-2">City <span class="text-red-400">*</span></label>
                    <select name="city_id" id="citySelect" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 transition" required>
                        <option value="" class="bg-slate-800">Select a city</option>
                        @foreach($cities as $city)
                            <option value="{{ $city->id }}" {{ old('city_id') == $city->id ? 'selected' : '' }} class="bg-slate-800">
                                {{ $city->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('city_id') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-amber-400 font-bold mb-2">Description</label>
                    <textarea name="description" rows="4" placeholder="Describe your venue..." class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">{{ old('description') }}</textarea>
                    @error('description') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-amber-400 font-bold mb-2">Address <span class="text-red-400">*</span></label>
                    <input type="text" name="address" value="{{ old('address') }}" placeholder="Complete address" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 transition" required>
                    @error('address') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-amber-400 font-bold mb-2">Phone</label>
                        <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="+91-9876543210" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
                        @error('phone') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-amber-400 font-bold mb-2">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="contact@venue.com" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
                        @error('email') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-amber-400 font-bold mb-2">Total Capacity <span class="text-red-400">*</span></label>
                    <input type="number" name="total_capacity" value="{{ old('total_capacity') }}" min="1" placeholder="500" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 transition" required>
                    <small class="text-gray-400 text-sm">Total number of seats in this venue</small>
                    @error('total_capacity') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-amber-400 font-bold mb-2">Seating Layout Image (Optional)</label>
                    <input type="file" name="seating_layout" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 transition" accept="image/*">
                    <small class="text-gray-400 text-sm">Upload a floor plan or seating chart image for reference</small>
                    @error('seating_layout') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="flex gap-4 pt-6 border-t border-slate-600">
                    <button type="submit" class="px-6 py-3 bg-gradient-to-r from-emerald-500 to-teal-500 hover:shadow-lg hover:shadow-emerald-500/20 text-white rounded-lg font-semibold transition">
                        <i class="fas fa-plus mr-2"></i>Create Venue
                    </button>
                    <a href="{{ route('organiser.venues.index') }}" class="px-6 py-3 bg-slate-700 hover:bg-slate-600 text-white rounded-lg font-medium transition">
                        <i class="fas fa-times mr-2"></i>Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const searchInput = document.getElementById('citySearch');
const suggestions = document.getElementById('citySuggestions');
const citySelect = document.getElementById('citySelect');

let searchTimeout;

// Show all cities when clicking on search input
searchInput.addEventListener('focus', function(e) {
    if (searchInput.value.length === 0) {
        loadCities('');
    }
});

searchInput.addEventListener('input', function(e) {
    clearTimeout(searchTimeout);
    const query = e.target.value.trim();
    
    searchTimeout = setTimeout(() => {
        loadCities(query);
    }, 300);
});

function loadCities(query) {
    const url = `{{ route('organiser.search-cities') }}?q=${encodeURIComponent(query)}`;
    console.log('Fetching:', url);
    
    fetch(url)
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Data received:', data);
            suggestions.innerHTML = '';
            
            if (!data || data.length === 0) {
                suggestions.innerHTML = '<div class="px-4 py-3 text-gray-500 text-sm">No cities found</div>';
            } else {
                data.forEach(city => {
                    const div = document.createElement('div');
                    div.className = 'px-4 py-3 hover:bg-blue-100 cursor-pointer border-b text-sm hover:text-blue-700 transition-colors';
                    div.textContent = city.label;
                    div.addEventListener('click', () => {
                        citySelect.value = city.id;
                        searchInput.value = city.label;
                        suggestions.classList.add('hidden');
                        console.log('Selected city:', city.id, city.label);
                    });
                    suggestions.appendChild(div);
                });
            }
            
            suggestions.classList.remove('hidden');
        })
        .catch(error => {
            console.error('Error fetching cities:', error);
            suggestions.innerHTML = '<div class="px-4 py-3 text-red-500 text-sm">Error loading suggestions</div>';
            suggestions.classList.remove('hidden');
        });
}

document.addEventListener('click', function(e) {
    if (e.target !== searchInput && !suggestions.contains(e.target)) {
        suggestions.classList.add('hidden');
    }
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
