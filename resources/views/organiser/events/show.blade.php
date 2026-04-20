@extends('layouts.app')

@section('title', $event->title)

@section('content')
<div class="py-8">
    <div class="max-w-6xl mx-auto">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-4xl font-bold">{{ $event->title }}</h1>
            <div class="space-x-2">
                <a href="{{ route('organiser.events.edit', $event) }}" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                    <i class="fas fa-edit mr-2"></i>Edit Event
                </a>
                <a href="{{ route('organiser.events.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Events
                </a>
            </div>
        </div>

        <div class="grid grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="col-span-2">
                <!-- Event Image -->
                @if($event->image)
                    <div class="mb-6 rounded-lg overflow-hidden bg-gray-200">
                        <img src="{{ $event->image_url }}" alt="{{ $event->title }}" class="w-full h-96 object-cover">
                    </div>
                @else
                    <div class="mb-6 rounded-lg bg-gray-200 h-96 flex items-center justify-center">
                        <span class="text-gray-500"><i class="fas fa-image text-4xl"></i></span>
                    </div>
                @endif

                <!-- Event Description -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-2xl font-bold mb-4">Description</h2>
                    <p class="text-gray-700 leading-relaxed">{{ $event->description }}</p>
                </div>

                <!-- Event Details -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-2xl font-bold mb-4">Event Details</h2>
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <p class="text-gray-600 text-sm font-semibold mb-1">Event Date</p>
                            <p class="text-lg font-bold">{{ $event->event_date->format('M d, Y') }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm font-semibold mb-1">Start Time</p>
                            <p class="text-lg font-bold">{{ $event->start_time ? \Carbon\Carbon::parse($event->start_time)->format('g:i A') : '—' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm font-semibold mb-1">End Time</p>
                            <p class="text-lg font-bold">{{ $event->end_time ? \Carbon\Carbon::parse($event->end_time)->format('g:i A') : '7:00 PM' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm font-semibold mb-1">Location</p>
                            <p class="text-lg font-bold">{{ $event->city->name ?? $event->location ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm font-semibold mb-1">Base Price</p>
                            <p class="text-lg font-bold">₹{{ number_format($event->base_price, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm font-semibold mb-1">Status</p>
                            <span class="px-3 py-1 rounded-full text-sm font-semibold
                                @if($event->status == 'published')
                                    bg-green-100 text-green-800
                                @elseif($event->status == 'draft')
                                    bg-yellow-100 text-yellow-800
                                @else
                                    bg-red-100 text-red-800
                                @endif
                            ">
                                {{ ucfirst($event->status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div>
                <!-- Status Card -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h3 class="text-xl font-bold mb-4">Event Status</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Current Status</span>
                            <span class="px-3 py-1 rounded-full text-sm font-semibold
                                @if($event->status == 'published')
                                    bg-green-100 text-green-800
                                @elseif($event->status == 'draft')
                                    bg-yellow-100 text-yellow-800
                                @else
                                    bg-red-100 text-red-800
                                @endif
                            ">
                                {{ ucfirst($event->status) }}
                            </span>
                        </div>
                        @if($event->status == 'draft')
                            <form action="{{ route('organiser.events.publish', $event) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">
                                    Publish Event
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                <!-- Bookings Summary -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-xl font-bold mb-4">Bookings Summary</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center pb-3 border-b">
                            <span class="text-gray-600">Total Bookings</span>
                            <span class="text-2xl font-bold">{{ $event->bookings()->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center pb-3 border-b">
                            <span class="text-gray-600">Confirmed</span>
                            <span class="text-lg font-semibold text-green-600">{{ $event->bookings()->where('status', 'confirmed')->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Cancelled</span>
                            <span class="text-lg font-semibold text-red-600">{{ $event->bookings()->where('status', 'cancelled')->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
