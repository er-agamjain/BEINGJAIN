<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureOrganiserIsActive
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() && $request->user()->isOrganiser() && !$request->user()->is_active) {
            auth()->logout();
            return redirect()->route('login')->with('error', 'Your organiser account has been deactivated.');
        }

        return $next($request);
    }
}
