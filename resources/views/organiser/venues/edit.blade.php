@extends('layouts.app')

@section('title', 'Edit Venue')

@section('content')
<div class="py-8">
    <div class="max-w-4xl mx-auto">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">
                    <i class="fas fa-building text-blue-600"></i> Edit Venue
                </h1>
                <p class="text-gray-600">Update venue information: {{ $venue->name }}</p>
            </div>
            <a href="{{ route('organiser.venues.index') }}" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium rounded-lg transition-colors">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>

        <form method="POST" action="{{ route('organiser.venues.update', $venue) }}" enctype="multipart/form-data" class="space-y-6">
    @csrf
    @method('PUT')

        <!-- Basic Information -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                <i class="fas fa-info-circle text-blue-600 mr-3"></i> Basic Information
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Venue Name <span class="text-red-600">*</span></label>
                    <input type="text" name="name" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., Crystal Hall, Taj Auditorium" 
                           value="{{ old('name', $venue->name) }}" required>
                    @error('name')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
                </div>

                <div>
                    <label class="block text-gray-700 font-bold mb-2">City <span class="text-red-600">*</span></label>
                    <select name="city_id" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Select a city</option>
                        @foreach($cities as $city)
                            <option value="{{ $city->id }}" {{ old('city_id', $venue->city_id) == $city->id ? 'selected' : '' }}>
                                {{ $city->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('city_id')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-gray-700 font-bold mb-2">Description</label>
                    <textarea name="description" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Describe your venue..." rows="4">{{ old('description', $venue->description) }}</textarea>
                    @error('description')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
                </div>
            </div>
        </div>

        <!-- Location Details -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                <i class="fas fa-map-marker-alt text-blue-600 mr-3"></i> Location Details
            </h2>

            <div class="space-y-4">
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Address <span class="text-red-600">*</span></label>
                    <input type="text" name="address" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Complete address" 
                           value="{{ old('address', $venue->address) }}" required>
                    @error('address')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-gray-700 font-bold mb-2">Phone</label>
                        <input type="tel" name="phone" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="+91-9876543210" 
                               value="{{ old('phone', $venue->phone) }}">
                        @error('phone')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
                    </div>

                    <div>
                        <label class="block text-gray-700 font-bold mb-2">Email</label>
                        <input type="email" name="email" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="contact@venue.com" 
                               value="{{ old('email', $venue->email) }}">
                        @error('email')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Capacity & Layout -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                <i class="fas fa-chair text-blue-600 mr-3"></i> Capacity & Layout
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Total Capacity <span class="text-red-600">*</span></label>
                    <input type="number" name="total_capacity" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="500" 
                           value="{{ old('total_capacity', $venue->total_capacity) }}" min="1" required>
                    <small class="text-gray-600 mt-1 block">Total number of seats in this venue</small>
                    @error('total_capacity')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="border-t border-gray-200 pt-6">
                <label class="block text-gray-700 font-bold mb-2">Seating Layout Image</label>
                
                @if($venue->seating_layout)
                    <div class="mt-4 mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <p class="text-gray-600 text-sm mb-3">Current Layout:</p>
                        <img src="{{ asset($venue->seating_layout) }}" alt="Seating Layout" class="max-h-48 rounded-lg">
                    </div>
                @endif

                <div class="mt-4">
                    <div class="relative border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-blue-400 transition-colors"
                         ondragover="this.classList.add('border-blue-400', 'bg-blue-50')"
                         ondragleave="this.classList.remove('border-blue-400', 'bg-blue-50')"
                         ondrop="this.classList.remove('border-blue-400', 'bg-blue-50'); handleFileUpload(event)">
                        <input type="file" id="seating_layout" name="seating_layout" class="hidden" accept="image/*">
                        <label for="seating_layout" class="cursor-pointer">
                            <i class="fas fa-cloud-upload-alt text-4xl text-blue-600 mb-3"></i>
                            <p class="text-gray-800 font-semibold mt-2">Click to upload or drag and drop</p>
                            <p class="text-gray-600 text-sm">PNG, JPG, GIF up to 5MB (Optional)</p>
                        </label>
                    </div>
                    @error('seating_layout')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex gap-4 justify-end pt-6 border-t border-gray-200">
            <a href="{{ route('organiser.venues.index') }}" class="px-6 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium rounded-lg transition-colors">
                <i class="fas fa-times"></i> Cancel
            </a>
            <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                <i class="fas fa-save"></i> Update Venue
            </button>
        </div>
        </form>
    </div>
</div>

@if($errors->any())
    <div class="mt-6 bg-white rounded-lg border-l-4 border-red-500 p-4 shadow">
        <h3 class="text-red-600 font-bold mb-2">Validation Errors:</h3>
        <ul class="text-red-600 text-sm">
            @foreach($errors->all() as $error)
                <li>• {{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if(session('success'))
    <div class="mt-6 bg-white rounded-lg border-l-4 border-green-500 p-4 shadow">
        <p class="text-green-600 flex items-center">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </p>
    </div>
@endif

<script>
function handleFileUpload(e) {
    e.preventDefault();
    const files = e.dataTransfer?.files || e.target.files;
    document.getElementById('seating_layout').files = files;
}
</script>

<style>
    .btn-secondary {
        @apply px-4 py-3 rounded-lg bg-slate-700 hover:bg-slate-600 text-white font-medium transition-colors flex items-center gap-2;
    }
</style>
@endsection
