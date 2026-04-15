<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $user = Socialite::driver('google')->user();

            // Check if user exists
            $findUser = User::where('google_id', $user->id)->first();

            if ($findUser) {
                Auth::login($findUser);
                return redirect()->route('user.events.index')->with('success', 'Logged in successfully!');
            }

            // Check if email exists
            $existingUser = User::where('email', $user->email)->first();

            if ($existingUser) {
                // Link Google account
                $existingUser->update([
                    'google_id' => $user->id,
                    'google_token' => $user->token,
                ]);
                Auth::login($existingUser);
                return redirect()->route('user.events.index')->with('success', 'Google account linked!');
            }

            // Create new user
            $userRole = Role::where('name', 'User')->first();

            $newUser = User::create([
                'name' => $user->name,
                'email' => $user->email,
                'google_id' => $user->id,
                'google_token' => $user->token,
                'role_id' => $userRole->id,
                'is_active' => true,
                'password' => bcrypt('google-' . $user->id),
            ]);

            Auth::login($newUser);
            return redirect()->route('user.events.index')->with('success', 'Account created with Google!');

        } catch (Exception $e) {
            return redirect()->route('login')->with('error', 'Unable to login with Google');
        }
    }
}
