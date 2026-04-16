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

        $organisers = $query->withCount('events')->orderBy('created_at', 'desc')->paginate(15);

        $allOrganisers = User::whereHas('role', function ($q) {
            $q->where('name', 'Organiser');
        })->where('is_active', true)->orderBy('name')->get(['id', 'name', 'email']);

        return view('admin.organisers', compact('organisers', 'allOrganisers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone' => 'required|string',
            'password' => 'required|string|min:8',
            'commission_rate' => 'required|numeric|min:0|max:100',
        ]);

        $organiserRole = \App\Models\Role::where('name', 'Organiser')->first();

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
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

    public function destroy(Request $request, User $user)
    {
        if (!$user->isOrganiser()) {
            return redirect()->back()->with('error', 'User is not an organiser');
        }

        $eventsCount = $user->events()->count();

        if ($eventsCount > 0) {
            $validated = $request->validate([
                'transfer_to' => 'required|exists:users,id',
            ]);

            $newOrganiser = User::findOrFail($validated['transfer_to']);

            if (!$newOrganiser->isOrganiser()) {
                return redirect()->back()->with('error', 'Selected user is not an organiser.');
            }

            if ((int) $newOrganiser->id === (int) $user->id) {
                return redirect()->back()->with('error', 'Cannot transfer events to the same organiser.');
            }

            // Transfer all events owned by this organiser
            $user->events()->update(['organiser_id' => $newOrganiser->id]);

            // Transfer all venues owned by this organiser
            \App\Models\Venue::where('organiser_id', $user->id)
                ->update(['organiser_id' => $newOrganiser->id]);

            // Transfer commission records
            \App\Models\Commission::where('organiser_id', $user->id)
                ->update(['organiser_id' => $newOrganiser->id]);
        }

        $user->delete();

        $message = $eventsCount > 0
            ? 'Organiser removed and ' . $eventsCount . ' event(s) transferred successfully.'
            : 'Organiser removed successfully.';

        return redirect()->route('admin.organisers.index')->with('success', $message);
    }
}
