@extends('layouts.app')

@section('title', 'Edit Seat')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <!-- Header -->
    <div>
        <a href="{{ route('admin.seats.index') }}" class="text-blue-600 hover:text-blue-800 mb-4 inline-block">
            <i class="fas fa-arrow-left mr-2"></i>Back to Seats
        </a>
        <h1 class="text-3xl font-bold text-gray-800">Edit Seat</h1>
    </div>

    <!-- Seat Info -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <div>
                <p class="text-gray-600 text-sm">Seat Number</p>
                <p class="text-lg font-bold">{{ $seat->seat_number }}</p>
            </div>
            <div>
                <p class="text-gray-600 text-sm">Row</p>
                <p class="text-lg font-bold">{{ $seat->row_number }}</p>
            </div>
            <div>
                <p class="text-gray-600 text-sm">Column</p>
                <p class="text-lg font-bold">{{ $seat->column_number }}</p>
            </div>
            <div>
                <p class="text-gray-600 text-sm">Category</p>
                <p class="text-lg font-bold">{{ $seat->seatCategory->name }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6 pb-6 border-b border-gray-200">
            <div>
                <p class="text-gray-600 text-sm">Event</p>
                <p class="text-lg font-bold">{{ $seat->showTiming->event->name }}</p>
            </div>
            <div>
                <p class="text-gray-600 text-sm">Show Timing</p>
                <p class="text-lg font-bold">{{ $seat->showTiming->show_date_time->format('M d, Y H:i') }}</p>
            </div>
        </div>

        <!-- Edit Form -->
        <form action="{{ route('admin.seats.update', $seat) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="status" class="block text-gray-700 font-bold mb-2">Seat Status</label>
                <select id="status" name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="available" {{ $seat->status === 'available' ? 'selected' : '' }}>Available</option>
                    <option value="reserved" {{ $seat->status === 'reserved' ? 'selected' : '' }}>Reserved</option>
                    <option value="booked" {{ $seat->status === 'booked' ? 'selected' : '' }}>Booked</option>
                    <option value="blocked" {{ $seat->status === 'blocked' ? 'selected' : '' }}>Blocked</option>
                </select>
                <p class="text-sm text-gray-600 mt-1">Change seat availability status</p>
            </div>

            <div>
                <label for="current_price" class="block text-gray-700 font-bold mb-2">Custom Price (Optional)</label>
                <input type="number" id="current_price" name="current_price" step="0.01" min="0" value="{{ $seat->current_price }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <p class="text-sm text-gray-600 mt-1">Leave empty to use category base price (₹{{ number_format($seat->seatCategory->base_price, 2) }})</p>
            </div>

            <div class="flex gap-4 pt-4 border-t border-gray-200">
                <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-save mr-2"></i>Save Changes
                </button>
                <a href="{{ route('admin.seats.index') }}" class="flex-1 px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition text-center">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
