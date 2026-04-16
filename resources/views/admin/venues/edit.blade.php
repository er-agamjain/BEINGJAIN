@extends('layouts.app')

@section('title', 'Edit Venue')

@section('content')
<div class="py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-white mb-2">
                    <i class="fas fa-building text-amber-400"></i> Edit Venue
                </h1>
                <p class="text-gray-400">Update venue information: {{ $venue->name }}</p>
            </div>
            <a href="{{ route('admin.venues.index') }}" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-white font-medium rounded-lg transition-colors">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>

        <form method="POST" action="{{ route('admin.venues.update', $venue) }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="card-luxury rounded-lg p-6">
                <h2 class="text-2xl font-bold text-white mb-6 flex items-center">
                    <i class="fas fa-info-circle text-amber-400 mr-3"></i> Basic Information
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-amber-400 font-bold mb-2">Venue Name <span class="text-red-400">*</span></label>
                        <input type="text" name="name" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" value="{{ old('name', $venue->name) }}" required>
                        @error('name')<span class="text-red-400 text-sm">{{ $message }}</span>@enderror
                    </div>

                    <div>
                        <label class="block text-amber-400 font-bold mb-2">City <span class="text-red-400">*</span></label>
                        <select name="city_id" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" required>
                            <option value="">Select a city</option>
                            @foreach($cities as $city)
                                <option value="{{ $city->id }}" {{ old('city_id', $venue->city_id) == $city->id ? 'selected' : '' }}>
                                    {{ $city->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('city_id')<span class="text-red-400 text-sm">{{ $message }}</span>@enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-amber-400 font-bold mb-2">Description</label>
                        <textarea name="description" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" rows="4">{{ old('description', $venue->description) }}</textarea>
                        @error('description')<span class="text-red-400 text-sm">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>

            <div class="card-luxury rounded-lg p-6">
                <h2 class="text-2xl font-bold text-white mb-6 flex items-center">
                    <i class="fas fa-map-marker-alt text-amber-400 mr-3"></i> Location Details
                </h2>

                <div class="space-y-4">
                    <div>
                        <label class="block text-amber-400 font-bold mb-2">Address <span class="text-red-400">*</span></label>
                        <input type="text" name="address" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" value="{{ old('address', $venue->address) }}" required>
                        @error('address')<span class="text-red-400 text-sm">{{ $message }}</span>@enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-amber-400 font-bold mb-2">Phone</label>
                            <input type="tel" name="phone" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" value="{{ old('phone', $venue->phone) }}">
                            @error('phone')<span class="text-red-400 text-sm">{{ $message }}</span>@enderror
                        </div>

                        <div>
                            <label class="block text-amber-400 font-bold mb-2">Email</label>
                            <input type="email" name="email" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" value="{{ old('email', $venue->email) }}">
                            @error('email')<span class="text-red-400 text-sm">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-luxury rounded-lg p-6">
                <h2 class="text-2xl font-bold text-white mb-6 flex items-center">
                    <i class="fas fa-chair text-amber-400 mr-3"></i> Capacity & Layout
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-amber-400 font-bold mb-2">Total Capacity <span class="text-red-400">*</span></label>
                        <input type="number" name="total_capacity" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" value="{{ old('total_capacity', $venue->total_capacity) }}" min="1" required>
                        <small class="text-gray-400 mt-1 block">Total number of seats in this venue</small>
                        @error('total_capacity')<span class="text-red-400 text-sm">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="border-t border-slate-600 pt-6">
                    <label class="block text-amber-400 font-bold mb-2">Seating Layout Image</label>

                    @if($venue->seating_layout)
                        <div class="mt-4 mb-6 p-4 bg-slate-800 rounded-lg border border-slate-600">
                            <p class="text-gray-400 text-sm mb-3">Current Layout:</p>
                            <img src="{{ asset('storage/' . $venue->seating_layout) }}" alt="Seating Layout" class="max-h-48 rounded-lg">
                        </div>
                    @endif

                    <div class="mt-4">
                        <input type="file" id="seating_layout" name="seating_layout" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" accept="image/*">
                        @error('seating_layout')<span class="text-red-400 text-sm">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>

            <div class="flex gap-4 justify-end pt-6 border-t border-slate-600">
                <a href="{{ route('admin.venues.index') }}" class="px-6 py-2 bg-slate-700 hover:bg-slate-600 text-white font-medium rounded-lg transition-colors">
                    <i class="fas fa-times"></i> Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-gradient-to-r from-emerald-500 to-teal-500 hover:shadow-lg hover:shadow-emerald-500/20 text-white font-medium rounded-lg transition-colors">
                    <i class="fas fa-save"></i> Update Venue
                </button>
            </div>
        </form>
    </div>
</div>

<style>
.card-luxury {
    background: linear-gradient(135deg, rgba(15, 23, 42, 0.8) 0%, rgba(30, 41, 59, 0.8) 100%);
    border: 1px solid rgba(148, 113, 113, 0.2);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3);
}
</style>
@endsection
