@extends('layouts.app')

@section('title', 'Edit Show Timing')

@section('content')
<div class="py-8">
    <div class="max-w-4xl mx-auto">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">
                    <i class="fas fa-clock text-blue-600"></i> Edit Show Timing
                </h1>
                <p class="text-gray-600">Update details for this scheduled show</p>
            </div>
            <a href="{{ route('organiser.show-timings.index') }}" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium rounded-lg transition-colors">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>

        <form method="POST" action="{{ route('organiser.show-timings.update', $showTiming) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Event & Venue -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-info-circle text-blue-600 mr-3"></i> Event & Venue
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-gray-700 font-bold mb-2">Event</label>
                        <input type="text" value="{{ $showTiming->event->name ?? 'N/A' }}" class="w-full px-4 py-2 border rounded-lg bg-gray-100 text-gray-700" disabled>
                        <small class="text-gray-600 mt-1 block">Event cannot be changed</small>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-bold mb-2">Venue</label>
                        <input type="text" value="{{ $showTiming->venue->name ?? 'N/A' }}" class="w-full px-4 py-2 border rounded-lg bg-gray-100 text-gray-700" disabled>
                        <small class="text-gray-600 mt-1 block">Venue cannot be changed</small>
                    </div>
                </div>
            </div>

            <!-- Show Timing Details -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-calendar-alt text-blue-600 mr-3"></i> Show Details
                </h2>

                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-gray-700 font-bold mb-2">Show Date & Time <span class="text-red-600">*</span></label>
                            <input type="datetime-local" name="show_date_time" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                   value="{{ old('show_date_time', optional($showTiming->show_date_time)->format('Y-m-d\\TH:i')) }}" required>
                            <small class="text-gray-600 mt-1 block">Must be in the future</small>
                            @error('show_date_time')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
                        </div>

                        <div>
                            <label class="block text-gray-700 font-bold mb-2">Duration (minutes) <span class="text-red-600">*</span></label>
                            <input type="number" name="duration_minutes" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                   placeholder="90" value="{{ old('duration_minutes', $showTiming->duration_minutes) }}" min="30" required>
                            <small class="text-gray-600 mt-1 block">Minimum 30 minutes</small>
                            @error('duration_minutes')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
                        </div>

                        <div>
                            <label class="block text-gray-700 font-bold mb-2">Available Seats <span class="text-red-600">*</span></label>
                            <input type="number" name="available_seats" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                   placeholder="500" value="{{ old('available_seats', $showTiming->available_seats) }}" min="1" required>
                            <small class="text-gray-600 mt-1 block">Total seats available for this show</small>
                            @error('available_seats')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
                        </div>

                        <div>
                            <label class="block text-gray-700 font-bold mb-2">Status <span class="text-red-600">*</span></label>
                            <select name="status" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                <option value="scheduled" {{ old('status', $showTiming->status) === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                <option value="cancelled" {{ old('status', $showTiming->status) === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                <option value="completed" {{ old('status', $showTiming->status) === 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                            @error('status')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-bold mb-2">Notes (Optional)</label>
                        <textarea name="notes" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                  placeholder="Any special notes about this show..." rows="4">{{ old('notes', $showTiming->notes) }}</textarea>
                        @error('notes')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex gap-4 justify-end pt-6 border-t border-gray-200">
                <a href="{{ route('organiser.show-timings.index') }}" class="px-6 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium rounded-lg transition-colors">
                    <i class="fas fa-times"></i> Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                    <i class="fas fa-save"></i> Update Show Timing
                </button>
            </div>
        </form>

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
    </div>
</div>
@endsection
