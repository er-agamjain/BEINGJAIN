@extends('layouts.app')

@section('title', 'Users Management')

@section('content')
<div class="mb-6 sm:mb-8 animate-fade-in">
    <h1 class="text-2xl sm:text-3xl font-bold text-white mb-2">
        <i class="fas fa-users text-emerald-400"></i> Users Management
    </h1>
    <p class="text-sm sm:text-base text-gray-400 mt-2">Manage all registered users and their activities</p>
</div>

<!-- Search & Filter Section -->
<div class="card-luxury p-4 sm:p-6 mb-6">
    <form method="GET" action="{{ route('admin.users.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-amber-400 font-bold mb-2 text-sm">Search Users</label>
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
            <a href="{{ route('admin.users.index') }}" class="px-3 sm:px-4 py-2 bg-slate-700 hover:bg-slate-600 text-white rounded-lg transition text-sm">
                <i class="fas fa-redo"></i>
            </a>
        </div>
    </form>
</div>

<div class="card-luxury">
    <div class="p-4 sm:p-6 border-b border-slate-700/30">
        <h2 class="text-xl sm:text-2xl font-bold text-white flex items-center">
            <i class="fas fa-list text-emerald-400 mr-2 sm:mr-3"></i> User Records
        </h2>
        <p class="text-gray-400 text-sm mt-1">Found <span class="text-emerald-400 font-bold">{{ $users->total() }}</span> user(s)</p>
    </div>
    <div class="p-4 sm:p-6 overflow-x-auto">
        <table class="w-full">
            <thead class="bg-slate-700/50 border-b-2 border-slate-600/50">
                <tr>
                    <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-semibold text-amber-400 uppercase">Name</th>
                    <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-semibold text-amber-400 uppercase hidden md:table-cell">Email</th>
                    <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-semibold text-amber-400 uppercase hidden lg:table-cell">Phone</th>
                    <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-semibold text-amber-400 uppercase">Status</th>
                    <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-semibold text-amber-400 uppercase hidden sm:table-cell">Joined</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-700/30">
                @forelse($users as $user)
                    <tr class="hover:bg-slate-700/40 hover:shadow-lg hover:shadow-emerald-500/10 transition-all duration-200">
                        <td class="px-3 sm:px-6 py-3 sm:py-4">
                            <div class="flex items-center">
                                <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center text-white font-bold mr-2 sm:mr-3 text-sm sm:text-base">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <span class="font-medium text-white text-sm sm:text-base">{{ $user->name }}</span>
                                    <div class="text-gray-400 text-xs md:hidden">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-3 sm:px-6 py-3 sm:py-4 text-gray-300 text-sm hidden md:table-cell">{{ $user->email }}</td>
                        <td class="px-3 sm:px-6 py-3 sm:py-4 text-gray-400 text-sm hidden lg:table-cell">{{ $user->phone ?? 'N/A' }}</td>
                        <td class="px-3 sm:px-6 py-3 sm:py-4">
                            @if($user->is_active)
                                <span class="px-2 sm:px-3 py-1 bg-green-500/20 text-green-200 rounded-lg text-xs font-semibold border border-green-500/30">
                                    <i class="fas fa-check-circle"></i> <span class="hidden sm:inline">Active</span>
                                </span>
                            @else
                                <span class="px-2 sm:px-3 py-1 bg-red-500/20 text-red-200 rounded-lg text-xs font-semibold border border-red-500/30">
                                    <i class="fas fa-times-circle"></i> <span class="hidden sm:inline">Inactive</span>
                                </span>
                            @endif
                        </td>
                        <td class="px-3 sm:px-6 py-3 sm:py-4 text-gray-400 text-xs sm:text-sm hidden sm:table-cell">{{ $user->created_at->format('M d, Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center text-gray-400">
                            <i class="fas fa-users text-6xl text-gray-600 mb-4 block"></i>
                            <p class="text-lg">No users found</p>
                            @if(request('search'))
                                <p class="text-sm mt-2">Try adjusting your search criteria</p>
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4 sm:p-6 border-t border-slate-700/30">
        {{ $users->links() }}
    </div>
</div>

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
