@extends('layouts.app')

@section('content')
<div class="py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-8">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold mb-2">{{ $booking->event->name }}</h1>
                <p class="text-gray-600">Booking Reference: {{ $booking->booking_reference }}</p>
            </div>

            <div class="border-2 border-dashed border-gray-300 p-8 rounded-lg text-center mb-8">
                <p class="text-4xl font-bold text-blue-600 mb-4">🎫</p>
                <h2 class="text-2xl font-bold mb-2">{{ $booking->ticket->name ?? 'General Admission' }}</h2>
                <p class="text-gray-600 mb-4">Quantity: {{ $booking->quantity }}</p>

                <div class="bg-gray-100 p-4 rounded mb-4">
                    <p class="text-sm text-gray-600">Order Date</p>
                    <p class="font-semibold">{{ $booking->created_at->format('F d, Y') }}</p>
                </div>

                <div class="bg-gray-100 p-4 rounded mb-4">
                    <p class="text-sm text-gray-600">Event Date</p>
                    <p class="font-semibold">{{ $booking->event->event_date->format('F d, Y H:i') }}</p>
                </div>

                <div class="bg-gray-100 p-4 rounded">
                    <p class="text-sm text-gray-600">Location</p>
                    <p class="font-semibold">{{ $booking->event->location }}</p>
                </div>
            </div>

            <div class="flex gap-4">
                <a href="{{ route('user.tickets.download', $booking) }}" class="flex-1 px-6 py-2 bg-green-500 text-white rounded-lg text-center hover:bg-green-600">
                    Download as PDF
                </a>
                <a href="{{ route('user.bookings.history') }}" class="flex-1 px-6 py-2 bg-gray-500 text-white rounded-lg text-center hover:bg-gray-600">
                    Back to Bookings
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
