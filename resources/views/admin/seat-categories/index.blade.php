@extends('layouts.app')

@section('title', 'Seat Categories')

@section('content')
<div class="mb-8 animate-fade-in flex items-center justify-between max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8">
    <div>
        <h1 class="text-4xl font-bold text-white mb-2" style="font-family: 'Playfair Display', serif;">
            <i class="fas fa-chair text-amber-400"></i> Seat Categories
        </h1>
        <p class="text-gray-400">Manage seating tiers for {{ $venue->name }}</p>
    </div>
    <button onclick="openCategoryModal()" class="px-5 py-3 rounded-lg bg-gradient-to-r from-emerald-500 to-teal-500 text-white font-semibold hover:shadow-lg hover:shadow-emerald-500/20 transition">
        <i class="fas fa-plus"></i> Add Category
    </button>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="card-luxury rounded-lg p-6 mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-bold text-white">{{ $venue->name }}</h3>
                <p class="text-gray-400 text-sm mt-1">{{ $venue->city->name }} | Capacity: {{ $venue->total_capacity }} seats</p>
            </div>
            <a href="{{ route('admin.venues.index') }}" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-white font-medium rounded-lg transition-colors">
                <i class="fas fa-arrow-left"></i> Back to Venues
            </a>
        </div>
    </div>

    @if($seatCategories->isEmpty())
        <div class="card-luxury rounded-lg text-center py-12">
            <i class="fas fa-inbox text-gray-400 text-4xl mb-4"></i>
            <p class="text-gray-300 mb-6">No seat categories created yet</p>
            <button onclick="openCategoryModal()" class="px-6 py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-medium rounded-lg transition-colors inline-flex">
                <i class="fas fa-plus mr-2"></i> Create First Category
            </button>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            @foreach($seatCategories as $category)
                <div class="card-luxury rounded-lg p-6 border-l-4" style="border-left-color: {{ $category->color }}">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded" style="background-color: {{ $category->color }}"></div>
                            <h3 class="text-xl font-bold text-white">{{ $category->name }}</h3>
                        </div>
                    </div>

                    <div class="space-y-3 mb-4 pb-4 border-b border-slate-600/60">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-400 text-sm">Base Price</span>
                            <span class="text-amber-300 font-bold text-lg">₹{{ number_format($category->base_price, 2) }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-400 text-sm">Total Seats</span>
                            <span class="text-white font-semibold">{{ $category->total_seats }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-400 text-sm">Assigned Seats</span>
                            <span class="text-gray-300">{{ $category->seats->count() }}</span>
                        </div>
                    </div>

                    @if($category->description)
                        <div class="mb-4">
                            <p class="text-gray-300 text-sm">{{ $category->description }}</p>
                        </div>
                    @endif

                    <div class="flex gap-2">
                        <button onclick="editCategory({{ $category->id }}, '{{ $category->name }}', '{{ $category->base_price }}', '{{ $category->total_seats }}', '{{ $category->color }}', @js($category->description))" class="px-3 py-2 rounded-lg text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-500 flex-1 text-center transition-colors">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <form method="POST" action="{{ route('admin.seat-categories.destroy', [$venue, $category]) }}" class="flex-1" onsubmit="return confirm('Delete this category?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3 py-2 rounded-lg text-sm font-medium text-white bg-red-600 hover:bg-red-500 w-full transition-colors">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<div id="categoryModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 px-4">
    <div class="card-luxury rounded-xl p-8 w-full max-w-md shadow-2xl">
        <h2 class="text-2xl font-bold text-white mb-6"><span id="modalTitle">Add Seat Category</span></h2>

        <form id="categoryForm" method="POST" action="{{ route('admin.seat-categories.store', $venue) }}">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-300 font-bold mb-2">Category Name</label>
                <input type="text" id="categoryName" name="name" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-300 font-bold mb-2">Base Price (₹)</label>
                <input type="number" id="categoryPrice" name="base_price" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" min="0" step="0.01" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-300 font-bold mb-2">Total Seats</label>
                <input type="number" id="categorySeats" name="total_seats" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" min="1" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-300 font-bold mb-2">Color</label>
                <div class="flex gap-2">
                    <input type="color" id="categoryColor" name="color" class="w-12 h-10 rounded-lg cursor-pointer" value="#3B82F6" required>
                    <input type="text" id="categoryColorText" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg" readonly>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-gray-300 font-bold mb-2">Description</label>
                <textarea id="categoryDescription" name="description" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" rows="2"></textarea>
            </div>

            <div class="flex gap-3 justify-end">
                <button type="button" onclick="closeCategoryModal()" class="px-4 py-2 rounded-lg bg-slate-700 hover:bg-slate-600 text-white font-medium transition-colors">Cancel</button>
                <button type="submit" class="px-4 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white font-medium transition-colors">Save Category</button>
            </div>
        </form>
    </div>
</div>

<script>
function openCategoryModal() {
    document.getElementById('categoryForm').action = "{{ route('admin.seat-categories.store', $venue) }}";
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
    document.getElementById('categoryForm').action = `/admin/venues/{{ $venue->id }}/seat-categories/${id}`;
    addMethodField('PUT');
    document.getElementById('modalTitle').textContent = 'Edit Seat Category';
    document.getElementById('categoryName').value = name;
    document.getElementById('categoryPrice').value = price;
    document.getElementById('categorySeats').value = seats;
    document.getElementById('categoryColor').value = color;
    document.getElementById('categoryColorText').value = color;
    document.getElementById('categoryDescription').value = description || '';
    document.getElementById('categoryModal').classList.remove('hidden');
}

function closeCategoryModal() {
    document.getElementById('categoryModal').classList.add('hidden');
}

function addMethodField(method) {
    clearMethodField();
    const input = document.createElement('input');
    input.id = 'methodField';
    input.type = 'hidden';
    input.name = '_method';
    input.value = method;
    document.getElementById('categoryForm').appendChild(input);
}

function clearMethodField() {
    const methodField = document.getElementById('methodField');
    if (methodField) methodField.remove();
}

document.getElementById('categoryColor').addEventListener('input', function () {
    document.getElementById('categoryColorText').value = this.value;
});

document.getElementById('categoryModal').addEventListener('click', function (e) {
    if (e.target === this) closeCategoryModal();
});

document.getElementById('categoryColorText').value = document.getElementById('categoryColor').value;
</script>

<style>
.card-luxury {
    background: linear-gradient(135deg, rgba(15, 23, 42, 0.8) 0%, rgba(30, 41, 59, 0.8) 100%);
    border: 1px solid rgba(148, 113, 113, 0.2);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3);
}
</style>
@endsection
