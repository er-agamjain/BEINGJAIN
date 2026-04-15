<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class OrganiserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::whereHas('role', function ($q) {
            $q->where('name', 'Organiser');
        });

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('phone', 'like', '%' . $search . '%');
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active' ? 1 : 0);
        }

        $organisers = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.organisers', compact('organisers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone' => 'required|string',
            'commission_rate' => 'required|numeric|min:0|max:100',
        ]);

        $organiserRole = \App\Models\Role::where('name', 'Organiser')->first();

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make('password123'),
            'role_id' => $organiserRole->id,
            'commission_rate' => $validated['commission_rate'],
            'is_active' => true,
        ]);

        return redirect()->route('admin.organisers.index')->with('success', 'Organiser created successfully');
    }

    public function activate(User $user)
    {
        if ($user->isOrganiser()) {
            $user->update(['is_active' => true]);
            return redirect()->back()->with('success', 'Organiser activated');
        }

        return redirect()->back()->with('error', 'User is not an organiser');
    }

    public function deactivate(User $user)
    {
        if ($user->isOrganiser()) {
            $user->update(['is_active' => false]);
            return redirect()->back()->with('success', 'Organiser deactivated');
        }

        return redirect()->back()->with('error', 'User is not an organiser');
    }

    public function updateCommission(Request $request, User $user)
    {
        if (!$user->isOrganiser()) {
            return redirect()->back()->with('error', 'User is not an organiser');
        }

        $validated = $request->validate([
            'commission_rate' => 'required|numeric|min:0|max:100',
        ]);

        $user->update(['commission_rate' => $validated['commission_rate']]);

        return redirect()->back()->with('success', 'Commission rate updated');
    }
}
