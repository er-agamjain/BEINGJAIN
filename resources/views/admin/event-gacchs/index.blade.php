@extends('layouts.app')

@section('title', 'Event Gacchs')

@section('content')
<div class="mb-8 animate-fade-in">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-white mb-2" style="font-family: 'Playfair Display', serif;">
                <i class="fas fa-layer-group text-amber-400"></i> Event Gacchs
            </h1>
            <p class="text-gray-400">Manage event gacchs (categories/types)</p>
        </div>
        <div class="flex space-x-3">
            <button onclick="document.getElementById('importModal').classList.remove('hidden')" class="px-6 py-2 bg-gradient-to-r from-blue-500 to-blue-600 hover:shadow-lg hover:shadow-blue-500/20 text-white rounded-lg font-semibold transition-all">
                <i class="fas fa-file-upload"></i> Import Excel/CSV
            </button>
            <button onclick="document.getElementById('addGacchModal').classList.remove('hidden')" class="px-6 py-2 bg-gradient-to-r from-emerald-500 to-teal-500 hover:shadow-lg hover:shadow-emerald-500/20 text-white rounded-lg font-semibold transition-all">
                <i class="fas fa-plus"></i> Add Gacchh
            </button>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="mb-6 p-4 bg-green-500/20 border border-green-500/30 rounded-lg text-green-200 flex items-center animate-fade-in">
        <i class="fas fa-check-circle mr-3 text-xl"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif

@if(session('error'))
    <div class="mb-6 p-4 bg-red-500/20 border border-red-500/30 rounded-lg text-red-200 flex items-center animate-fade-in">
        <i class="fas fa-exclamation-circle mr-3 text-xl"></i>
        <span>{{ session('error') }}</span>
    </div>
@endif

<!-- Gacchs Table -->
<div class="card-luxury mb-8">
    <div class="p-6">
        <div class="mb-6 flex items-center justify-between">
            <h2 class="text-2xl font-bold text-white flex items-center">
                <i class="fas fa-list text-amber-400 mr-3"></i> Gacchs List
            </h2>
            <span class="text-sm text-gray-400">Total: <span class="text-amber-400 font-bold">{{ $gacchs->total() }}</span></span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-700/50 border-b-2 border-slate-600/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-amber-400 uppercase">Gacchh Name</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-amber-400 uppercase">Status</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-amber-400 uppercase">Events</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-amber-400 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/30">
                    @forelse($gacchs as $gacchh)
                        <tr class="hover:bg-slate-700/20 transition-colors">
                            <td class="px-6 py-4">
                                <span class="text-white font-semibold">{{ $gacchh->name }}</span>
                            </td>
                        
                            <td class="px-6 py-4">
                                @if($gacchh->is_active)
                                    <span class="px-3 py-1 bg-green-500/20 text-green-200 rounded-lg text-xs font-semibold border border-green-500/30">
                                        <i class="fas fa-check-circle"></i> Active
                                    </span>
                                @else
                                    <span class="px-3 py-1 bg-red-500/20 text-red-200 rounded-lg text-xs font-semibold border border-red-500/30">
                                        <i class="fas fa-times-circle"></i> Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-amber-400 font-bold">0</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    <button onclick="openEditModal({{ $gacchh->id }}, '{{ addslashes($gacchh->name) }}', '{{ addslashes($gacchh->description ?? '') }}')" class="text-emerald-400 hover:text-emerald-300 transition-colors" title="Edit">
                                        <i class="fas fa-edit text-lg"></i>
                                    </button>
                                    <form action="{{ route('admin.event-gacchs.toggle', $gacchh) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="{{ $gacchh->is_active ? 'text-amber-400 hover:text-amber-300' : 'text-green-400 hover:text-green-300' }} transition-colors" title="{{ $gacchh->is_active ? 'Deactivate' : 'Activate' }}">
                                            <i class="fas {{ $gacchh->is_active ? 'fa-toggle-on' : 'fa-toggle-off' }} text-lg"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.event-gacchs.destroy', $gacchh) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Delete this gacchh?')" class="text-red-400 hover:text-red-300 transition-colors" title="Delete">
                                            <i class="fas fa-trash text-lg"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-16 text-center text-gray-400">
                                <i class="fas fa-layer-group text-6xl text-gray-600 mb-4 block"></i>
                                <p class="text-lg">No gacchs found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Summary Stats -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="card-luxury p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-400 text-sm mb-2">Total Gacchs</p>
                <p class="text-4xl font-bold text-amber-400">{{ $gacchs->total() }}</p>
            </div>
            <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-amber-500/20 to-orange-600/20 flex items-center justify-center text-amber-400 text-3xl border border-amber-500/30">
                <i class="fas fa-layer-group"></i>
            </div>
        </div>
    </div>

    <div class="card-luxury p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-400 text-sm mb-2">Active Gacchs</p>
                <p class="text-4xl font-bold text-emerald-400">{{ \App\Models\EventGacchh::where('is_active', true)->count() }}</p>
            </div>
            <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-emerald-500/20 to-teal-600/20 flex items-center justify-center text-emerald-400 text-3xl border border-emerald-500/30">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
    </div>

    <div class="card-luxury p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-400 text-sm mb-2">Total Events</p>
                <p class="text-4xl font-bold text-blue-400">{{ \App\Models\Event::count() }}</p>
            </div>
            <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-blue-500/20 to-blue-600/20 flex items-center justify-center text-blue-400 text-3xl border border-blue-500/30">
                <i class="fas fa-calendar-alt"></i>
            </div>
        </div>
    </div>
</div>

<!-- Import Modal -->
<div id="importModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50">
    <div class="card-luxury rounded-2xl p-8 max-w-md w-full mx-4">
        <h3 class="text-2xl font-bold text-white mb-6">Import Gacchs from Excel/CSV</h3>
        <form action="{{ route('admin.event-gacchs.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-gray-300 font-bold mb-2">Upload File</label>
                <input type="file" name="file" accept=".xlsx,.xls,.csv" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-500 file:text-white hover:file:bg-blue-600" required>
                <p class="text-xs text-gray-400 mt-2">
                    <i class="fas fa-info-circle"></i> File must have a header row with column: <strong>name</strong> (optional: description, is_active)
                </p>
            </div>

            <div class="bg-slate-700/50 p-4 rounded-lg border border-slate-600">
                <h4 class="text-white font-semibold mb-2 flex items-center">
                    <i class="fas fa-file-excel text-green-400 mr-2"></i> Sample Format
                </h4>
                <div class="text-xs text-gray-300 font-mono">
                    <div class="grid grid-cols-3 gap-2 p-2 bg-slate-800 rounded">
                        <span class="font-bold">name</span>
                        <span class="font-bold">description</span>
                        <span class="font-bold">is_active</span>
                    </div>
                    <div class="grid grid-cols-3 gap-2 p-2">
                        <span>Gacchh 1</span>
                        <span>Description</span>
                        <span>1</span>
                    </div>
                </div>
            </div>

            <div class="flex space-x-3 pt-4">
                <button type="submit" class="flex-1 px-6 py-2 bg-gradient-to-r from-blue-500 to-blue-600 hover:shadow-lg hover:shadow-blue-500/20 text-white rounded-lg font-semibold transition-all">
                    <i class="fas fa-upload mr-2"></i> Import
                </button>
                <button type="button" onclick="document.getElementById('importModal').classList.add('hidden')" class="flex-1 px-6 py-2 bg-slate-700 hover:bg-slate-600 text-gray-300 rounded-lg font-semibold transition-all border border-slate-600">
                    <i class="fas fa-times mr-2"></i> Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Add Gacchh Modal -->
<div id="addGacchModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50">
    <div class="card-luxury rounded-2xl p-8 max-w-md w-full mx-4">
        <h3 class="text-2xl font-bold text-white mb-6">Add New Gacchh</h3>
        <form action="{{ route('admin.event-gacchs.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-gray-300 font-bold mb-2">Gacchh Name</label>
                <input type="text" name="name" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" required>
            </div>

            <div class="flex space-x-3 pt-4">
                <button type="submit" class="flex-1 px-6 py-2 bg-gradient-to-r from-emerald-500 to-teal-500 hover:shadow-lg hover:shadow-emerald-500/20 text-white rounded-lg font-semibold transition-all">
                    <i class="fas fa-check mr-2"></i> Create Gacchh
                </button>
                <button type="button" onclick="document.getElementById('addGacchModal').classList.add('hidden')" class="flex-1 px-6 py-2 bg-slate-700 hover:bg-slate-600 text-gray-300 rounded-lg font-semibold transition-all border border-slate-600">
                    <i class="fas fa-times mr-2"></i> Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Gacchh Modal -->
<div id="editGacchModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50">
    <div class="card-luxury rounded-2xl p-8 max-w-md w-full mx-4">
        <h3 class="text-2xl font-bold text-white mb-6">Edit Gacchh</h3>
        <form id="editForm" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-gray-300 font-bold mb-2">Gacchh Name</label>
                <input type="text" id="edit_gacchh_name" name="name" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" required>
            </div>

            <div class="flex space-x-3 pt-4">
                <button type="submit" class="flex-1 px-6 py-2 bg-gradient-to-r from-emerald-500 to-teal-500 hover:shadow-lg hover:shadow-emerald-500/20 text-white rounded-lg font-semibold transition-all">
                    <i class="fas fa-save mr-2"></i> Update Gacchh
                </button>
                <button type="button" onclick="document.getElementById('editGacchModal').classList.add('hidden')" class="flex-1 px-6 py-2 bg-slate-700 hover:bg-slate-600 text-gray-300 rounded-lg font-semibold transition-all border border-slate-600">
                    <i class="fas fa-times mr-2"></i> Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditModal(id, name, description) {
    document.getElementById('edit_gacchh_name').value = name;
    document.getElementById('editForm').action = `/admin/event-gacchs/${id}`;
    document.getElementById('editGacchModal').classList.remove('hidden');
}
</script>

<style>
.animate-fade-in {
    animation: fadeIn 0.5s ease-in-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

    .card-luxury {
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.8) 0%, rgba(30, 41, 59, 0.8) 100%);
        border: 1px solid rgba(148, 113, 113, 0.2);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3);
        border-radius: 0.75rem;
        transition: all 0.3s ease;
    }
    
    .card-luxury:hover {
        border-color: rgba(217, 119, 6, 0.4);
        box-shadow: 0 25px 30px -5px rgba(217, 119, 6, 0.1);
    }
    .fa-edit:before, .fa-pen-to-square:before{
        color: aqua;
    }

</style>
@endsection
