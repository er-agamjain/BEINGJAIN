<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();
            
            // Explicitly load the role relationship
            $user->load('role');
            
            // Debug log
            \Log::info('User logged in: ' . $user->email . ' - Role: ' . ($user->role->name ?? 'NULL'));
            
            // Redirect based on role - DO NOT use intended()
            if ($user->isAdmin()) {
                \Log::info('Redirecting admin to admin.dashboard');
                return redirect()->route('admin.dashboard');
            } elseif ($user->isOrganiser()) {
                \Log::info('Redirecting organiser to organiser.events.index');
                return redirect()->route('organiser.events.index');
            } else {
                \Log::info('Redirecting user to user.events.index');
                return redirect()->route('user.events.index');
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
