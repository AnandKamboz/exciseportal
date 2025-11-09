<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserMiddleware
{
   
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }

        $role = Auth::user()->roles()->pluck('role_name')->first();


        if ($role !== 'user') {
            abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}
