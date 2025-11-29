<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;


class HqMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // User logged in nahi to redirect
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Please login first.');
        }

        // User role check
        $role = Auth::user()->roles()->pluck('role_name')->first();

        if ($role !== 'hq') {
            abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}
