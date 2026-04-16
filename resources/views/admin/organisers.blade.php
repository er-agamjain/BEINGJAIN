@extends('layouts.app')

@section('title', 'Organisers Management')

@section('content')
<div class="mb-6 sm:mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 animate-fade-in">
    <div>
        <h1 class="text-2xl sm:text-3xl font-bold text-white mb-2">
            <i class="fas fa-user-tie text-emerald-400"></i> Organisers Management
        </h1>
        <p class="text-sm sm:text-base text-gray-400 mt-2">Manage event organisers, activate accounts, and set commissions</p>
    </div>
    <button onclick="document.getElementById('addOrganiserModal').classList.remove('hidden')" class="w-full sm:w-auto px-4 py-2 bg-gradient-to-r from-emerald-500 to-teal-500 hover:shadow-lg hover:shadow-emerald-500/20 text-white font-medium rounded-lg transition-all">
        <i class="fas fa-plus"></i> Add Organiser
    </button>
</div>

<!-- Search & Filter Section -->
<div class="card-luxury p-4 sm:p-6 mb-6">
    <form method="GET" action="{{ route('admin.organisers.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-amber-400 font-bold mb-2 text-sm">Search Organisers</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, Email, or Phone..." class="w-full px-3 sm:px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 transition text-sm placeholder-gray-500">
        </div>
        
        <div>
            <label class="block text-amber-400 font-bold mb-2 text-sm">Status</label>
            <select name="status" class="w-full px-3 sm:px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 transition text-sm">
                <option value="">All Status</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <div class="flex items-end gap-2">
            <button type="submit" class="px-4 sm:px-6 py-2 bg-gradient-to-r from-emerald-500 to-teal-500 text-white rounded-lg hover:shadow-lg hover:shadow-emerald-500/20 font-semibold transition flex-1 text-sm">
                <i class="fas fa-search mr-2"></i> Search
            </button>
            <a href="{{ route('admin.organisers.index') }}" class="px-3 sm:px-4 py-2 bg-slate-700 hover:bg-slate-600 text-white rounded-lg transition text-sm">
                <i class="fas fa-redo"></i>
            </a>
        </div>
    </form>
</div>

<div class="card-luxury p-4 sm:p-6">
    <div class="mb-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2">
        <h2 class="text-xl sm:text-2xl font-bold text-white flex items-center">
            <i class="fas fa-list text-emerald-400 mr-2 sm:mr-3"></i> Organiser List
        </h2>
        <span class="text-sm text-gray-400">Found: <span class="text-emerald-400 font-bold">{{ $organisers->total() }}</span> organiser(s)</span>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-slate-700/50 border-b-2 border-slate-600/50">
                <tr>
                    <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-semibold text-amber-400 uppercase">Organiser</th>
                    <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-semibold text-amber-400 uppercase hidden lg:table-cell">Contact</th>
                    <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-semibold text-amber-400 uppercase hidden md:table-cell">Commission</th>
                    <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-semibold text-amber-400 uppercase">Status</th>
                    <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-semibold text-amber-400 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-700/30">
                @forelse($organisers as $organiser)
                    <tr class="hover:bg-slate-700/40 hover:shadow-lg hover:shadow-emerald-500/10 transition-all duration-200">
                        <td class="px-3 sm:px-6 py-3 sm:py-4">
                            <div class="flex items-center">
                                <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white font-bold mr-2 sm:mr-3 text-sm sm:text-base">
                                    {{ strtoupper(substr($organiser->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-medium text-white text-sm sm:text-base">{{ $organiser->name }}</div>
                                    <div class="text-xs text-gray-400">{{ $organiser->email }}</div>
                                    <div class="text-xs text-gray-400 lg:hidden">{{ $organiser->phone ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-3 sm:px-6 py-3 sm:py-4 text-gray-300 text-sm hidden lg:table-cell">{{ $organiser->phone ?? 'N/A' }}</td>
                        <td class="px-3 sm:px-6 py-3 sm:py-4 hidden md:table-cell">
                            <span class="text-base sm:text-lg font-bold text-emerald-400">{{ $organiser->commission_rate }}%</span>
                        </td>
                        <td class="px-3 sm:px-6 py-3 sm:py-4">
                            @if($organiser->is_active)
                                <span class="px-2 sm:px-3 py-1 bg-green-500/20 text-green-200 rounded-lg text-xs font-semibold border border-green-500/30">
                                    <i class="fas fa-check-circle"></i> <span class="hidden sm:inline">Active</span>
                                </span>
                            @else
                                <span class="px-2 sm:px-3 py-1 bg-red-500/20 text-red-200 rounded-lg text-xs font-semibold border border-red-500/30">
                                    <i class="fas fa-times-circle"></i> <span class="hidden sm:inline">Inactive</span>
                                </span>
                            @endif
                        </td>
                        <td class="px-3 sm:px-6 py-3 sm:py-4">
                            <div class="flex items-center space-x-2 sm:space-x-3">
                                @if($organiser->is_active)
                                    <form action="{{ route('admin.organisers.deactivate', $organiser) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="text-red-400 hover:text-red-300 transition-colors" title="Deactivate">
                                            <i class="fas fa-ban text-lg"></i>
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.organisers.activate', $organiser) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="text-green-400 hover:text-green-300 transition-colors" title="Activate">
                                            <i class="fas fa-check-circle text-lg"></i>
                                        </button>
                                    </form>
                                @endif
                                <button onclick="openCommissionModal({{ $organiser->id }}, {{ $organiser->commission_rate }})" class="text-emerald-400 hover:text-emerald-300 transition-colors" title="Update Commission">
                                    <i class="fas fa-percentage text-lg"></i>
                                </button>
                                <button
                                    data-id="{{ $organiser->id }}"
                                    data-name="{{ $organiser->name }}"
                                    data-count="{{ $organiser->events_count }}"
                                    onclick="openRemoveModal(this.dataset.id, this.dataset.name, this.dataset.count)"
                                    class="text-rose-400 hover:text-rose-300 transition-colors" title="Remove Organiser">
                                    <i class="fas fa-trash text-lg"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center text-gray-400">
                            <i class="fas fa-user-tie text-6xl text-gray-600 mb-4 block"></i>
                            <p class="text-lg">No organisers found</p>
                            @if(request('search'))
                                <p class="text-sm mt-2">Try adjusting your search criteria</p>
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-6">
    {{ $organisers->links() }}
</div>

<!-- Add Organiser Modal -->
<div id="addOrganiserModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50">
    <div class="card-luxury rounded-2xl p-8 max-w-md w-full mx-4">
        <h3 class="text-2xl font-bold text-white mb-6">Add New Organiser</h3>
        <form action="{{ route('admin.organisers.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-gray-300 font-bold mb-2">Name</label>
                <input type="text" name="name" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" required>
            </div>
            <div>
                <label class="block text-gray-300 font-bold mb-2">Email</label>
                <input type="email" name="email" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" required>
            </div>
            <div>
                <label class="block text-gray-300 font-bold mb-2">Phone</label>
                <input type="text" name="phone" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" required>
            </div>
            <div>
                <label class="block text-gray-300 font-bold mb-2">Password</label>
                <input type="password" name="password" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" required>
            </div>
            <div>
                <label class="block text-gray-300 font-bold mb-2">Commission Rate (%)</label>
                <input type="number" name="commission_rate" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" min="0" max="100" step="0.01" value="10" required>
            </div>
            <div class="flex space-x-3 pt-4">
                <button type="submit" class="flex-1 px-6 py-2 bg-gradient-to-r from-emerald-500 to-teal-500 hover:shadow-lg hover:shadow-emerald-500/20 text-white font-medium rounded-lg transition-all">
                    <i class="fas fa-check mr-2"></i> Create
                </button>
                <button type="button" onclick="document.getElementById('addOrganiserModal').classList.add('hidden')" class="flex-1 px-6 py-2 bg-slate-700 hover:bg-slate-600 text-gray-300 border border-slate-600 font-medium rounded-lg transition-all">
                    <i class="fas fa-times mr-2"></i> Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Remove Organiser Modal -->
<div id="removeOrganiserModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50">
    <div class="card-luxury rounded-2xl p-8 max-w-lg w-full mx-4">
        <h3 class="text-2xl font-bold text-white mb-2">
            <i class="fas fa-trash text-rose-400 mr-2"></i> Remove Organiser
        </h3>
        <p class="text-gray-400 mb-5">You are about to permanently remove <span id="removeOrganiserName" class="text-white font-bold"></span>.</p>

        <!-- Events count warning -->
        <div id="eventsCountWarning" class="hidden mb-4 p-4 bg-amber-500/10 border border-amber-500/30 rounded-xl">
            <p class="text-amber-400 text-sm font-semibold mb-1">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                This organiser has <span id="eventsCountText" class="font-bold"></span> event(s) linked to their account.
            </p>
            <p class="text-amber-300/80 text-xs">All events, venues, and bookings will be transferred to the organiser you select below.</p>
        </div>

        <!-- No events note -->
        <div id="noEventsNote" class="hidden mb-4 p-3 bg-slate-700/50 border border-slate-600 rounded-xl">
            <p class="text-gray-400 text-sm">
                <i class="fas fa-info-circle text-blue-400 mr-2"></i> This organiser has no events. They will be permanently removed.
            </p>
        </div>

        <form id="removeOrganiserForm" method="POST">
            @csrf
            @method('DELETE')

            <!-- Transfer dropdown -->
            <div id="transferSection" class="hidden mb-6">
                <label class="block text-gray-300 font-bold mb-2">
                    <i class="fas fa-exchange-alt text-emerald-400 mr-1"></i> Transfer All Events To
                </label>
                <select id="transfer_to" name="transfer_to" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="">— Select Replacement Organiser —</option>
                    @foreach($allOrganisers as $opt)
                        <option value="{{ $opt->id }}" data-id="{{ $opt->id }}">{{ $opt->name }} ({{ $opt->email }})</option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-500 mt-1">Events, venues, and commission records will be reassigned to this organiser.</p>
            </div>

            <div class="flex space-x-3">
                <button type="submit" class="flex-1 px-6 py-2 bg-gradient-to-r from-rose-600 to-red-600 hover:shadow-lg hover:shadow-rose-500/20 text-white font-medium rounded-lg transition-all">
                    <i class="fas fa-trash mr-2"></i> Remove Organiser
                </button>
                <button type="button" onclick="document.getElementById('removeOrganiserModal').classList.add('hidden')" class="flex-1 px-6 py-2 bg-slate-700 hover:bg-slate-600 text-gray-300 border border-slate-600 font-medium rounded-lg transition-all">
                    <i class="fas fa-times mr-2"></i> Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Commission Modal -->
<div id="commissionModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50">
    <div class="card-luxury rounded-2xl p-8 max-w-md w-full mx-4">
        <h3 class="text-2xl font-bold text-white mb-6">Update Commission Rate</h3>
        <form id="commissionForm" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-gray-300 font-bold mb-2">Commission Rate (%)</label>
                <input type="number" id="commission_rate_input" name="commission_rate" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" min="0" max="100" step="0.01" required>
            </div>
            <div class="flex space-x-3 pt-4">
                <button type="submit" class="flex-1 px-6 py-2 bg-gradient-to-r from-emerald-500 to-teal-500 hover:shadow-lg hover:shadow-emerald-500/20 text-white font-medium rounded-lg transition-all">
                    <i class="fas fa-save mr-2"></i> Update
                </button>
                <button type="button" onclick="document.getElementById('commissionModal').classList.add('hidden')" class="flex-1 px-6 py-2 bg-slate-700 hover:bg-slate-600 text-gray-300 border border-slate-600 font-medium rounded-lg transition-all">
                    <i class="fas fa-times mr-2"></i> Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openCommissionModal(organiserId, currentRate) {
    document.getElementById('commission_rate_input').value = currentRate;
    document.getElementById('commissionForm').action = `/admin/organisers/${organiserId}/commission`;
    document.getElementById('commissionModal').classList.remove('hidden');
}

function openRemoveModal(organiserId, organiserName, eventsCount) {
    eventsCount = parseInt(eventsCount, 10) || 0;
    document.getElementById('removeOrganiserName').textContent = organiserName;
    document.getElementById('removeOrganiserForm').action = `/admin/organisers/${organiserId}`;

    const transferSection = document.getElementById('transferSection');
    const transferSelect  = document.getElementById('transfer_to');
    const eventsWarning   = document.getElementById('eventsCountWarning');
    const noEventsNote    = document.getElementById('noEventsNote');

    // Hide the current organiser from the transfer dropdown
    Array.from(transferSelect.options).forEach(option => {
        option.hidden = (option.value == organiserId);
    });
    // Reset selection
    transferSelect.value = '';

    if (eventsCount > 0) {
        document.getElementById('eventsCountText').textContent = eventsCount;
        eventsWarning.classList.remove('hidden');
        noEventsNote.classList.add('hidden');
        transferSection.classList.remove('hidden');
        transferSelect.required = true;
    } else {
        eventsWarning.classList.add('hidden');
        noEventsNote.classList.remove('hidden');
        transferSection.classList.add('hidden');
        transferSelect.required = false;
    }

    document.getElementById('removeOrganiserModal').classList.remove('hidden');
}
</script>

<style>
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
</style>
@endsection
