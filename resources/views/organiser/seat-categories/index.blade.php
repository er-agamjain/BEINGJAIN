@extends('layouts.app')

@section('title', 'Seat Categories')

@section('content')
<div class="mb-8 animate-fade-in flex items-center justify-between">
    <div>
        <h1 class="text-4xl font-bold text-transparent bg-gradient-to-r from-indigo-400 via-purple-300 to-indigo-400 bg-clip-text mb-2" style="font-family: 'Playfair Display', serif;">
            <i class="fas fa-chair text-indigo-400"></i> Seat Categories
        </h1>
        <p class="text-gray-400">Manage seating tiers for {{ $venue->name }}</p>
    </div>
    <button onclick="openCategoryModal()" class="btn-primary">
        <i class="fas fa-plus"></i> Add Category
    </button>
</div>

<!-- Venue Info -->
<div class="bg-white rounded-lg shadow p-6 mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-bold text-gray-800">{{ $venue->name }}</h3>
            <p class="text-gray-600 text-sm mt-1">{{ $venue->city->name }} • Capacity: {{ $venue->total_capacity }} seats</p>
        </div>
        <a href="{{ route('organiser.venues.edit', $venue) }}" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium rounded-lg transition-colors">
            <i class="fas fa-arrow-left"></i> Back to Venue
        </a>
    </div>
</div>

<!-- Categories Grid -->
@if($seatCategories->isEmpty())
    <div class="bg-white rounded-lg shadow text-center py-12">
        <i class="fas fa-inbox text-gray-400 text-4xl mb-4"></i>
        <p class="text-gray-600 mb-6">No seat categories created yet</p>
        <button onclick="openCategoryModal()" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors inline-flex">
            <i class="fas fa-plus mr-2"></i> Create First Category
        </button>
    </div>
@else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        @foreach($seatCategories as $category)
            <div class="bg-white rounded-lg shadow p-6 border-l-4" style="border-left-color: {{ $category->color }}">
                <!-- Header -->
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded" style="background-color: {{ $category->color }}"></div>
                        <h3 class="text-xl font-bold text-gray-800">{{ $category->name }}</h3>
                    </div>
                </div>

                <!-- Details -->
                <div class="space-y-3 mb-4 pb-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600 text-sm">Base Price</span>
                        <span class="text-blue-600 font-bold text-lg">₹{{ number_format($category->base_price, 2) }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600 text-sm">Total Seats</span>
                        <span class="text-gray-800 font-semibold">{{ $category->total_seats }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600 text-sm">Assigned Seats</span>
                        <span class="text-gray-700">{{ $category->seats->count() }}</span>
                    </div>
                </div>

                <!-- Description -->
                @if($category->description)
                    <div class="mb-4">
                        <p class="text-gray-600 text-sm">{{ $category->description }}</p>
                    </div>
                @endif

                <!-- Actions -->
                <div class="flex gap-2">
                    <button onclick="editCategory({{ $category->id }}, '{{ $category->name }}', '{{ $category->base_price }}', '{{ $category->total_seats }}', '{{ $category->color }}', '{{ $category->description }}')" 
                            class="btn-sm bg-blue-600 hover:bg-blue-700 flex-1 text-center">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    <form method="POST" action="{{ route('organiser.seat-categories.destroy', [$venue, $category]) }}" 
                          class="flex-1" onsubmit="return confirm('Delete this category?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-sm bg-red-600 hover:bg-red-700 w-full">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Summary -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Summary</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                <p class="text-gray-600 text-sm">Total Categories</p>
                <p class="text-blue-600 text-2xl font-bold">{{ $seatCategories->count() }}</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                <p class="text-gray-600 text-sm">Total Revenue Potential</p>
                <p class="text-green-600 text-2xl font-bold">₹{{ number_format($seatCategories->sum(fn($c) => $c->base_price * $c->total_seats), 2) }}</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                <p class="text-gray-600 text-sm">Total Seats Configured</p>
                <p class="text-blue-600 text-2xl font-bold">{{ $seatCategories->sum('total_seats') }}</p>
            </div>
        </div>
    </div>
@endif

<!-- Modal for Add/Edit Category -->
<div id="categoryModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white rounded-xl p-8 w-full max-w-md shadow-2xl">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">
            <span id="modalTitle">Add Seat Category</span>
        </h2>

        <form id="categoryForm" method="POST" action="{{ route('organiser.seat-categories.store', $venue) }}">
            @csrf

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Category Name</label>
                <input type="text" id="categoryName" name="name" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., VIP, Premium, Standard" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Base Price (₹)</label>
                <input type="number" id="categoryPrice" name="base_price" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="500" 
                       min="0" step="0.01" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Total Seats</label>
                <input type="number" id="categorySeats" name="total_seats" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="100" 
                       min="1" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Color</label>
                <div class="flex gap-2">
                    <input type="color" id="categoryColor" name="color" class="w-12 h-10 rounded-lg cursor-pointer" value="#3B82F6" required>
                    <input type="text" id="categoryColorText" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="#3B82F6" readonly>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 font-bold mb-2">Description</label>
                <textarea id="categoryDescription" name="description" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Optional description" rows="2"></textarea>
            </div>

            <div class="flex gap-3 justify-end">
                <button type="button" onclick="closeCategoryModal()" class="px-4 py-2 rounded-lg bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium transition-colors">Cancel</button>
                <button type="submit" class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-medium transition-colors">Save Category</button>
            </div>
        </form>
    </div>
</div>

@if($errors->any())
    <div class="mt-4 card-luxury border-l-4 border-red-500 bg-red-500/10">
        <h3 class="text-red-400 font-bold mb-2">Error:</h3>
        <p class="text-red-400">{{ $errors->first() }}</p>
    </div>
@endif

@if(session('success'))
    <div class="mt-4 card-luxury border-l-4 border-green-500 bg-green-500/10">
        <p class="text-green-400 flex items-center">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </p>
    </div>
@endif

<script>
function openCategoryModal() {
    document.getElementById('categoryForm').action = "{{ route('organiser.seat-categories.store', $venue) }}";
    document.getElementById('categoryForm').method = 'POST';
    document.getElementById('modalTitle').textContent = 'Add Seat Category';
    document.getElementById('categoryName').value = '';
    document.getElementById('categoryPrice').value = '';
    document.getElementById('categorySeats').value = '';
    document.getElementById('categoryColor').value = '#3B82F6';
    document.getElementById('categoryColorText').value = '#3B82F6';
    document.getElementById('categoryDescription').value = '';
    clearMethodField();
    document.getElementById('categoryModal').classList.remove('hidden');
}

function editCategory(id, name, price, seats, color, description) {
    document.getElementById('categoryForm').action = `/organiser/venues/{{ $venue->id }}/seat-categories/${id}`;
    document.getElementById('categoryForm').method = 'POST';
    addMethodField('PUT');
    document.getElementById('modalTitle').textContent = 'Edit Seat Category';
    document.getElementById('categoryName').value = name;
    document.getElementById('categoryPrice').value = price;
    document.getElementById('categorySeats').value = seats;
    document.getElementById('categoryColor').value = color;
    document.getElementById('categoryColorText').value = color;
    document.getElementById('categoryDescription').value = description;
    document.getElementById('categoryModal').classList.remove('hidden');
}

function closeCategoryModal() {
    document.getElementById('categoryModal').classList.add('hidden');
}

function addMethodField(method) {
    let methodField = document.getElementById('methodField');
    if (methodField) methodField.remove();
    const input = document.createElement('input');
    input.id = 'methodField';
    input.type = 'hidden';
    input.name = '_method';
    input.value = method;
    document.getElementById('categoryForm').appendChild(input);
}

function clearMethodField() {
    let methodField = document.getElementById('methodField');
    if (methodField) methodField.remove();
}

document.getElementById('categoryColor').addEventListener('input', function() {
    document.getElementById('categoryColorText').value = this.value;
});

document.getElementById('categoryModal').addEventListener('click', function(e) {
    if(e.target === this) closeCategoryModal();
});
</script>

<style>
    .btn-sm {
        @apply px-3 py-2 rounded-lg text-sm font-medium text-white transition-colors;
    }

    .btn-secondary {
        @apply px-4 py-2 rounded-lg bg-slate-700 hover:bg-slate-600 text-white font-medium transition-colors;
    }
</style>
@endsection
