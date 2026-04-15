@extends('layouts.app')

@section('title', 'Cities Management')

@section('content')
<div class="mb-8 animate-fade-in">
    <h1 class="text-4xl font-bold text-transparent bg-gradient-to-r from-blue-400 via-cyan-300 to-blue-400 bg-clip-text mb-2" style="font-family: 'Playfair Display', serif;">
        <i class="fas fa-city text-blue-400"></i> Cities Management
    </h1>
    <p class="text-gray-400">Manage event locations and cities</p>
</div>

<!-- Add City Button -->
<div class="mb-6 flex justify-end">
    <button class="btn-primary" onclick="openCityModal()">
        <i class="fas fa-plus"></i> Add City
    </button>
</div>

<!-- Cities Table -->
<div class="card-luxury">
    <div class="mb-6 flex items-center justify-between">
        <h2 class="text-2xl font-bold text-white flex items-center">
            <i class="fas fa-list text-blue-400 mr-3"></i> Cities List
        </h2>
        <div class="text-sm text-gray-400">
            Total: <span class="text-blue-400 font-bold">{{ $cities->count() }}</span>
        </div>
    </div>

    @if($cities->isEmpty())
        <div class="text-center py-12">
            <i class="fas fa-inbox text-gray-600 text-4xl mb-4"></i>
            <p class="text-gray-400">No cities found. Create one to get started!</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-700/50 border-b-2 border-slate-600/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-blue-400 uppercase">City Name</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-blue-400 uppercase">Slug</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-blue-400 uppercase">Description</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-blue-400 uppercase">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-blue-400 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/30">
                    @foreach($cities as $city)
                        <tr class="hover:bg-slate-700/20 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-medium text-white">{{ $city->name }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-gray-400 font-mono text-sm">{{ $city->slug }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-gray-400 text-sm max-w-xs truncate">{{ $city->description ?? 'N/A' }}</p>
                            </td>
                            <td class="px-6 py-4">
                                @if($city->is_active)
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-green-500/20 text-green-400">
                                        <i class="fas fa-check-circle"></i> Active
                                    </span>
                                @else
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-red-500/20 text-red-400">
                                        <i class="fas fa-times-circle"></i> Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    <button onclick="editCity({{ $city->id }}, '{{ $city->name }}', '{{ $city->description }}')" class="btn-sm bg-blue-600 hover:bg-blue-700">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <form method="POST" action="{{ route('admin.cities.toggle', $city) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="btn-sm {{ $city->is_active ? 'bg-yellow-600 hover:bg-yellow-700' : 'bg-green-600 hover:bg-green-700' }}">
                                            <i class="fas {{ $city->is_active ? 'fa-eye-slash' : 'fa-eye' }}"></i> {{ $city->is_active ? 'Deactivate' : 'Activate' }}
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.cities.destroy', $city) }}" class="inline" onsubmit="return confirm('Delete this city?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-sm bg-red-600 hover:bg-red-700">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

<!-- Modal for Add/Edit City -->
<div id="cityModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-slate-800 rounded-xl p-8 w-full max-w-md border border-slate-700/50 shadow-2xl">
        <h2 class="text-2xl font-bold text-white mb-6">
            <span id="modalTitle">Add City</span>
        </h2>

        <form id="cityForm" method="POST" action="{{ route('admin.cities.store') }}">
            @csrf
            <input type="hidden" id="cityId" name="city_id">

            <div class="mb-4">
                <label class="label">City Name</label>
                <input type="text" id="cityName" name="name" class="input-field" placeholder="Enter city name" required>
                <small class="text-gray-500">Will auto-generate slug</small>
            </div>

            <div class="mb-4">
                <label class="label">Description</label>
                <textarea id="cityDescription" name="description" class="input-field" placeholder="Enter city description" rows="3"></textarea>
            </div>

            <div class="flex gap-3 justify-end">
                <button type="button" onclick="closeCityModal()" class="btn-secondary">Cancel</button>
                <button type="submit" class="btn-primary">Save City</button>
            </div>
        </form>
    </div>
</div>

@if($errors->any())
    <div class="mt-4 card-luxury border-l-4 border-red-500 bg-red-500/10">
        <h3 class="text-red-400 font-bold mb-2">Validation Errors:</h3>
        <ul class="text-red-400 text-sm">
            @foreach($errors->all() as $error)
                <li>• {{ $error }}</li>
            @endforeach
        </ul>
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
function openCityModal() {
    document.getElementById('cityForm').action = "{{ route('admin.cities.store') }}";
    document.getElementById('cityForm').method = 'POST';
    document.getElementById('modalTitle').textContent = 'Add City';
    document.getElementById('cityId').value = '';
    document.getElementById('cityName').value = '';
    document.getElementById('cityDescription').value = '';
    document.getElementById('cityModal').classList.remove('hidden');
}

function editCity(id, name, description) {
    document.getElementById('cityForm').action = `/admin/cities/${id}`;
    document.getElementById('cityForm').method = 'POST';
    document.getElementById('cityForm').innerHTML = '@csrf\n@method("PUT")' + document.getElementById('cityForm').innerHTML;
    document.getElementById('modalTitle').textContent = 'Edit City';
    document.getElementById('cityName').value = name;
    document.getElementById('cityDescription').value = description;
    document.getElementById('cityModal').classList.remove('hidden');
}

function closeCityModal() {
    document.getElementById('cityModal').classList.add('hidden');
}

document.getElementById('cityModal').addEventListener('click', function(e) {
    if(e.target === this) closeCityModal();
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
