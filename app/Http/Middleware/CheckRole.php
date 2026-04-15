<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        // Eager load role if not already loaded
        if (!$user->relationLoaded('role')) {
            $user->load('role');
        }

        // Debug logging
        \Log::info('CheckRole Middleware:', [
            'user_email' => $user->email,
            'user_role_id' => $user->role_id,
            'user_role_name' => $user->role?->name ?? 'NULL',
            'expected_role' => $role,
            'match' => ($user->role?->name === $role),
        ]);

        if (!$user->role || $user->role->name !== $role) {
            \Log::warning('Role check failed', [
                'user' => $user->email,
                'has_role' => $user->role?->name,
                'needs_role' => $role,
            ]);
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
