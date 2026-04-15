@extends('layouts.app')

@section('title', 'Create Ticket')

@section('content')
<div class="py-8">
    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('organiser.events.edit', $event) }}" class="text-blue-600 hover:text-blue-800">
                <i class="fas fa-arrow-left"></i> Back to Event
            </a>
            <h1 class="text-3xl font-bold text-gray-800 mt-2">Create Ticket for {{ $event->name }}</h1>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <form action="{{ route('organiser.tickets.store', $event) }}" method="POST" class="space-y-6">
            @csrf
            
            <div>
                <label class="block text-gray-700 font-bold mb-2">Ticket Name</label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g., VIP, General Admission, Early Bird" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                <p class="text-sm text-gray-600 mt-1">Give your ticket a descriptive name</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Ticket Type</label>
                    <select name="ticket_type" id="ticket_type" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="free" {{ old('ticket_type') == 'free' ? 'selected' : '' }}>Free</option>
                        <option value="paid" {{ old('ticket_type', 'paid') == 'paid' ? 'selected' : '' }}>Paid</option>
                    </select>
                </div>
                
                <div id="price_field" style="{{ old('ticket_type', 'paid') == 'free' ? 'display:none;' : '' }}">
                    <label class="block text-gray-700 font-bold mb-2">Price (₹)</label>
                    <input type="number" name="price" id="price" value="{{ old('price', 0) }}" step="0.01" min="0" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            
            <div>
                <label class="block text-gray-700 font-bold mb-2">Quantity Available</label>
                <input type="number" name="quantity" value="{{ old('quantity') }}" min="1" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                <p class="text-sm text-gray-600 mt-1">Total number of tickets available for sale</p>
            </div>
            
            <div class="pt-4 flex space-x-4 border-t border-gray-200">
                <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors flex-1">
                    <i class="fas fa-plus-circle"></i> Create Ticket
                </button>
                <a href="{{ route('organiser.events.edit', $event) }}" class="px-6 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium rounded-lg transition-colors flex-1 text-center">
                    Cancel
                </a>
            </div>
            </form>
        </div>
    </div>
</div>
@push('scripts')
<script>
    const ticketType = document.getElementById('ticket_type');
    const priceField = document.getElementById('price_field');
    const priceInput = document.getElementById('price');

    function togglePriceField() {
        if (ticketType.value === 'free') {
            priceField.style.display = 'none';
            priceInput.value = 0;
        } else {
            priceField.style.display = 'block';
        }
    }

    ticketType.addEventListener('change', togglePriceField);
    togglePriceField();
</script>
@endpush

@endsection
